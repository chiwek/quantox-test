<?php
require '../vendor/autoload.php';

\Josantonius\Session\Session::init(0);


$router = new \Klein\Klein();

$router->respond(function ($request, $response, $service, $app) {

    $app->register('user', function() {

        $user = \Josantonius\Session\Session::get('user');

        return $user;
    });

    $app->register('db', function() {

        $db = new Dibi\Connection([
            'driver'   => 'mysqli',
            'host'     => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'quantox',
        ]);

        return $db;
    });
});

$router->respond(function ($request, $response, $service, $app) {
    $app->register('userRepo', function() use ($app) {
        return new \App\Models\UserRepository($app->db);
    });
});

// Header
$router->respond('*', function ($request, $response, $service, $app) {

    $service->render('../app/views/layout/header.phtml', ['user' => $app->user, 'searchTerm' => $request->param('query')]);
});


// Homepage
$router->respond('GET', '/', function ($request, $response, $service, $app) {

    if (!$app->user) {
        $response->redirect('/login');
    }
    $controller = new \App\Controllers\HomeController();
    return $controller->index($request, $response, $service, $app);
});


// Login page
$router->respond('GET', '/login', function ($request, $response, $service, $app) {
    $controller = new \App\Controllers\LoginController();
    return $controller->index($request, $response, $service, $app);
});
$router->respond('POST', '/login', function ($request, $response, $service, $app) {

    $error = '';
    // validate input parameters
    try {

        $service->validateParam('email', 'Please enter a valid email')->isEmail();
        $service->validateParam('password', 'Please enter password')->notNull();
        $email = $request->param('email');
        $password = password_hash($request->param('password'), PASSWORD_BCRYPT);

        $user = $app->userRepo->loadUserByEmailPassword($email, $password);
        if ($user) {
            throw new \Klein\Exceptions\ValidationException("User already exists");
        }

    } catch(\Klein\Exceptions\ValidationException $e) {
        $error = $e->getMessage();
    }


    $controller = new \App\Controllers\LoginController();
    return $controller->authenticate($request, $response, $service, $app, $error);
});

$router->respond('GET', '/logout', function ($request, $response, $service, $app) {
    $controller = new \App\Controllers\LoginController();
    return $controller->logout($request, $response, $service, $app);
});


// Register
$router->respond('GET', '/register', function ($request, $response, $service, $app) {
    $controller = new \App\Controllers\RegisterController();
    return $controller->index($request, $response, $service, $app);
});
$router->respond('POST', '/register', function ($request, $response, $service, $app) {

    $error = '';
    // validate input parameters
    try {

        $service->validateParam('email', 'Please enter a valid email')->isEmail();
        $service->validateParam('name', 'Please enter valid name')->NotNull();
        $service->validateParam('password', 'Please enter password')->notNull();
        $service->validateParam('repeat_password', 'Please enter password')->notNull();


        $email = $request->param('email');
        $user = $app->userRepo->loadUserByEmail($email);

        if ($user) {
            throw new \Klein\Exceptions\ValidationException("Email already exists");
        }

        $password = $request->param('password');
        $rPassword = $request->param('repeat_password');

        if ($rPassword !== $password) {
            throw new \Klein\Exceptions\ValidationException("Passwords must match!");
        }


    } catch(\Klein\Exceptions\ValidationException $e) {
        $error = $e->getMessage();
    }


    $controller = new \App\Controllers\RegisterController();
    return $controller->register($request, $response, $service, $app, $error);
});


// Search
$router->respond('GET', '/search', function ($request, $response, $service, $app) {

    $controller = new \App\Controllers\SearchController();
    if ($app->user) {
        return $controller->index($request, $response, $service, $app);
    } else {
        return $controller->searchNotAllowed($request, $response, $service, $app);
    }

});


// Footer
$router->respond('*', function ($request, $response, $service) { $service->render('../app/views/layout/footer.phtml'); });

// Go
$router->dispatch();

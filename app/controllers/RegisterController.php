<?php
namespace App\Controllers;


use App\Models\User;

class RegisterController
{
    /**
     * @param $request
     * @param $response
     * @param $service
     * @param $app
     * @return mixed
     */
    public function index($request, $response, $service, $app) {

        return $service->render(
            '../app/views/register.phtml',
            [
                'email' => '',
                'name' => '',
                'error' => '',
                'success' => '',

            ]
        );
    }

    public function register($request, $response, $service, $app, $error) {

        if ($error != '') {
            return $service->render(
                '../app/views/register.phtml',
                [
                    'email' => $request->param('email'),
                    'name' => $request->param('name'),
                    'error' => $error,
                    'success' => '',
                ]
            );
        } else {
            $user = new User();
            $user->email = $request->param('email');
            $user->name = $request->param('name');
            $user->password = password_hash($request->param('password'), PASSWORD_BCRYPT);

            $app->userRepo->saveUser($user);

            return $service->render(
                '../app/views/register.phtml',
                [
                    'email' => '',
                    'name' => '',
                    'error' => '',
                    'success' => 'User saved. Go ahead, create another one!',
                ]
            );

        }
    }
}
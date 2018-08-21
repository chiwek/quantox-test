<?php

namespace App\Controllers;


use Josantonius\Session\Session;

class LoginController
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
            '../app/views/login.phtml',
            [
                'email' => '',
                'password' => '',
                'error' => '',
                'success' => '',

            ]
        );
    }

    public function authenticate($request, $response, $service, $app, $error) {

        if ($error != '') {
            return $service->render(
                '../app/views/login.phtml',
                [
                    'email' => $request->param('email'),
                    'password' => $request->param('email'),
                    'error' => $error,
                    'success' => '',
                ]
            );
        } else {
            $email = $request->param('email');
            $user = $app->userRepo->loadUserByEmail($email);
            Session::set('user', $user);

            $response->redirect('/');

        }

    }

    public function logout($request, $response, $service, $app) {
        Session::destroy('user');
        $response->redirect('/');
    }
}
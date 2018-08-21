<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 8/21/2018
 * Time: 4:31 PM
 */

namespace App\Controllers;


class SearchController
{
    public function index($request, $response, $service, $app) {
        $searchText = $request->param('query');
        $results = $app->userRepo->findUsers($searchText);
        return $service->render('../app/views/search.phtml', ['results' => $results]);
    }

    public function searchNotAllowed($request, $response, $service, $app) {

        return $service->render(
            '../app/views/login.phtml',
            [
                'email' => '',
                'password' => '',
                'error' => 'Please login first.',
                'success' => '',

            ]
        );
    }
}
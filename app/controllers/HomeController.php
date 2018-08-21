<?php
namespace App\Controllers;


class HomeController
{

    /**
     * @param $request
     * @param $response
     * @param $service
     * @param $app
     * @return mixed
     */
    public function index($request, $response, $service, $app) {

        return $service->render('../app/views/home.phtml');
    }
}
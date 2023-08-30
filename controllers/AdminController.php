<?php

namespace Controllers;

use MVC\Router;

class AdminController{
    public static function index( Router $router){
        if (!$_SESSION['nombre']) {
            session_start();
        }

        $router->render('admin/index',[
            'nombre' => $_SESSION['nombre']
        ]);
    }
}
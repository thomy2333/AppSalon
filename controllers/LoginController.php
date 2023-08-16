<?php

namespace Controllers;

use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $router->render('auth/login', [

        ]);
    }
    public static function logout(){
        echo "desde log";
    }
    public static function olvide(){
        echo "desde olvide";
    }
    public static function recuperar(){
        echo "desde olvide";
    }
    public static function crear(){
        echo "desde olvide";
    }
}
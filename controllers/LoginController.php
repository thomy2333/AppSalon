<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $router->render('auth/login', [

        ]);
    }

    public static function logout(){
        echo "desde log";
    }

    public static function olvide(Router $router){
        $router->render('auth/olvide', [

        ]);
    }

    public static function recuperar(){
        echo "desde olvide";
    }

    public static function crear(Router $router){
        $usuario = new Usuario($_POST);

        //alertas vacias
        $errores = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $errores = $usuario->validarNuevaCuenta();

            //revisar que las alertas esten vacias
            if(empty($errores)){
                echo "Pasaste la validacion";
            }
        }
        
        $router->render('auth/crear', [
            'usuario'=> $usuario,
            'errores'=> $errores,
        ]);
    }
}
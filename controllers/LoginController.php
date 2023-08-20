<?php

namespace Controllers;

use Classes\Email;
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
                //verificar que el usuario no este regisdtrado
                $resultado =  $usuario->existeUsuario();

                if($resultado->num_rows){
                    $errores = Usuario::getErrores();
                }else{
                    //hashear el password
                    $usuario->hashPassword();

                    //generar  un token unico
                    $usuario->crearToken();

                    //enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }                   

                }
            }
        }
        
        $router->render('auth/crear', [
            'usuario'=> $usuario,
            'errores'=> $errores,
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $errores = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);
        debuguear($usuario);

        $router->render('auth/confirmar-cuenta', [
            'errores' => $errores
        ]);
    }
}
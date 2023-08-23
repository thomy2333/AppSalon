<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $errores = [];
        $auth = new Usuario;
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $errores = $auth->validarLogin();

            if(empty($errores)){
                //comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //verificar el password
                    if($usuario->comprobarPasswordAndPassword($auth->password)) {
                        //autenticar el usuario
                        if(!isset($_SESSION)) {
                            session_start();
                        }

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        }else {
                            header('Location: /cita');
                        }
                    }
                }else{
                    Usuario::setErrores('error', 'Usuario no encontrado');
                }
            }
        }

        $errores = Usuario::getErrores();

        $router->render('auth/login', [
            "errores" => $errores,
            'auth' => $auth
        ]);
    }

    public static function logout(){
        echo "desde log";
    }

    public static function olvide(Router $router){
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $errores = $auth->validarEmail();

            if(empty($errores)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){
                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarIntrucciones();

                    //alerta de exito
                    Usuario::setErrores('exito', 'Revisar tu Email');
                }else{
                    Usuario::setErrores('error', 'El Usuario no existe o no esta confirmado');
                }                
            }
        }

        $errores = Usuario::getErrores();

        $router->render('auth/olvide', [
            'errores' => $errores
        ]);
    }

    public static function recuperar(Router $router){
        $errores = [];
        $error = false;
        $token = s($_GET['token']);

        //buscar usuario por un token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setErrores('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //leer el nuevo password
            $password = new Usuario($_POST);
            $errores = $password->validarPassword();

            if(empty($errores)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header('location: /');
                }

            }
        }

        $errores = Usuario::getErrores();
        $router->render('auth/recuperar-password', [
            'errores'=> $errores,
            'error'=> $error
        ]);
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

        if (empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setErrores('error', 'Token no Válido');
        } else {
            //modificar el usuario confirmado
            $usuario->confirmado = "1";       
            $usuario->token = "";       
            $usuario->guardar();
            Usuario::setErrores('exito', 'Cuenta Comprobada Correctamente');      
        }

        //obtener alerta
        $errores = Usuario::getErrores();

        //renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'errores' => $errores
        ]);
    }
}
<?php

namespace Model;

class Usuario extends ActiveRecord{
    //base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {   
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    
    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$errores['error'][] = 'El Nombre es Obligatorio';
        }
        if(!$this->apellido) {
            self::$errores['error'][] = 'El Apellido es Obligatorio';
        }
        if(!$this->email) {
            self::$errores['error'][] = 'El Email es Obligatorio';
        }
        if(!$this->password) {
            self::$errores['error'][] = 'El Password es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$errores['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$errores;
    }

    public function validarLogin(){
        if(!$this->email) {
            self::$errores['error'][] = 'El Email es Obligatorio';
        }
        if(!$this->password) {
            self::$errores['error'][] = 'El Password es Obligatorio';
        }

        return self::$errores;
    }

    public function validarEmail(){
        if(!$this->email) {
            self::$errores['error'][] = 'El Email es Obligatorio';
        }
        return self::$errores;
    }

    public function validarPassword(){
        if(!$this->password) {
            self::$errores['error'][] = 'El Password es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$errores['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$errores;
    }

    public function existeUsuario(){
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$errores['error'][] = "El Usuario ya esta registrado";
        }

        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndPassword($password){
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado){
            self::$errores['error'][] = "Password Incorrecto o tu cuenta no ha sido confirmada";
        }else{
            return true;
        }
    }
}
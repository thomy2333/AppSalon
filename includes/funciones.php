<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//usuario autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function esUltimo($actual, $proximo): bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}

function isAdmin() : void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}
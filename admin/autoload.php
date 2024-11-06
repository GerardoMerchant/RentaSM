<?php
// Se establece un autoloader que se encargue de cargar las clases.
spl_autoload_register(function($clase){
    
    $archivo = __DIR__."/".$clase.".php";
    $archivo = str_replace("\\", "/", $archivo);
    if(is_file($archivo)){
        require_once $archivo;
    }
});
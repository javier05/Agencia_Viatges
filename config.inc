<?php
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', realpath(dirname(__FILE__)) . DS);
    define('APP', ROOT . 'application' . DS);
    //no volem accedir directament a les variables globals
    $phpServer=  filter_input(INPUT_SERVER, 'SCRIPT_NAME',FILTER_SANITIZE_URL);
    define('APP_W',dirname($phpServer));
    define ('SYS',ROOT .'system'.DS);
    define('DEF_CONTROLLER',"Index");
    define('DEF_ACTION',"index");
    define('THEME',"modern-business");
    
    function __autoload($class_name)
    {
        //Les classes comencen per majúscula
        $file=SYS.ucfirst(strtolower($class_name)).'.php';
        if (file_exists($file)){
            require_once($file);
        }
        $file=APP.ucfirst(strtolower($class_name)).'.php';
        if (file_exists($file)){
            require_once($file);
        }
        $file=APP.'controllers/'.ucfirst(strtolower($class_name)).'.php';
        if (file_exists($file)){
            require_once($file);
        }
        $file=APP.'models/'.ucfirst(strtolower($class_name)).'.php';
        if (file_exists($file)){
            require_once($file);
        }
     }

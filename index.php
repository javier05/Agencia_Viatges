<?php
    
    
    
     require 'config.inc';
     set_include_path(get_include_path().DS.ROOT.'application');
     set_include_path(get_include_path().DS.ROOT.'application/controllers');
     set_include_path(get_include_path().DS.ROOT.'application/models');
     // en config.php desem la configuració de l'aplicació
     // en constants.php desem la informació rellevant d'enllaços
     // i títols
     // Es requereix config.php per configurar l'accés a BBDD
     //require_once APP.'config.php';
     // Es requereix constants.php per definir diccionari de l'aplicació
     
     
    abstract class Index{
               
        static function run(){
            try{
                Session::init();
                $conf=Config::getInstance();
                $conf->JSON();
                
                $front= Bootstrap::getInstance();
                $front->route();
            } catch(Exception $e){
            echo $e->getMessage();
            }
        }
    }
  
    Index::run();


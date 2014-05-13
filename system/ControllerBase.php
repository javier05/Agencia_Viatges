<?php


/**
 * Description of ControllerBase
 *
 * @author toni
 */
class ControllerBase {
    
    protected $model;
    protected $view;
    protected $config;
    protected $arguments=array();
    
  function __construct($arr) {
      //habilitat el Registry
      $this->config= Config::getInstance();
      $this->config->APP_W=  dirname(filter_input(INPUT_SERVER, 'SCRIPT_NAME',FILTER_SANITIZE_URL));
      $this->arguments=$arr;
           
    }
    /**
     * 
     * @param type $mod modul redirection
     * @param type $action modul/action redirection
     */
    function Redirect($mod,$action){
        if (isset($action)){
            header('Location:'.APP_W.'/'.$mod.'/'.$action);
        }
        else{
            header('Location:'.APP_W.'/'.$mod);
        }
        }
    
   /**
    * 
    * @param type $arr associative array
    * @return type json
    */ 
   function ajax($arr){
        $json=  json_encode($arr);
        return $json;
    }
}

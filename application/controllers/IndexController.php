<?php

/**
 * Description of IndexController
 * 
 * Aquesta classe és el controlador que contè els mètodes necessaris per a 
 * la identificació i creació d'usuaris.
 *
 * @author javier
 */

class IndexController extends ControllerBase{
    protected $model;
    protected $view;
    //private $conf;
    /**
     * rep con a paràmetre un array associatiu que 
     * permet passar els paràmetres de la URI
     * @param array $arr
     */
    public function __construct($arr) {
        parent::__construct($arr);
       //carregar la configuració
       $this->model= new IndexModel($arr);
       $this->view=new View();
       $this->view->setProp($this->model->getDataout());
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->config->APP_W));
        $this->view->addProp(array('THEME'=>$this->config->THEME));
        $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/index.html');
        $this->view->render();
        
       
    }
    public function index() {}
    
    /**
     * Aquesta funció capturà, mitjançant el mètode POST, l'email i contrasenya al fer un login.
     * Si es rep, almenys, l'email (POST -> "usuari"), es cridarà al mètode "login" de IndexModel.
     * Si aquest mètode retorna "true" significarà que l'usuari s'ha validat correctament i ens enviarà
     * a la pàgina de "perfil d'usuari".
     */
    function login(){
        if(isset($_POST['usuari'])){
            $email=filter_input(INPUT_POST, 'usuari', FILTER_SANITIZE_STRING);
            $password=md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            $user=$this->model->login($email,$password);
            if ($user== TRUE) {
                $this->Redirect('User');
            }
        }
    }
    
    /**
     * Aquesta función rep com a paràmetres el nom, cognoms, email y contrasenya d'un usuari nou.
     * Si s'han especificat tots els paràmetres necessaris, es cridarà al mètode "login" del model 
     * i si aquest retorna "true", automàticament es farà un login del nou usuari.
     */
    function register(){
        if(isset($_POST['usuari']) && isset($_POST['password']) && isset($_POST['nom']) && isset($_POST['cognoms'])){
            $nom=  utf8_decode(filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING));
            $cognoms=utf8_decode(filter_input(INPUT_POST, 'cognoms', FILTER_SANITIZE_STRING));
            $email=filter_input(INPUT_POST, 'usuari', FILTER_SANITIZE_STRING);
            $password=md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            $user=$this->model->register($email,$password, $nom, $cognoms);
            if ($user== TRUE){
                $this->model->login($email, $password);
                $this->Redirect('index');
            }
            else{
                $this->Redirect('index');
            }
        }
    }
    
    /**
     * Aquesta funció el que farà és cridar al mètode "logout" del model. Si retrona "true" ens 
     * redireccionarà a l'índex. 
     */
    function logout() {
        if($this->model->logout() == TRUE)
            $this->Redirect('index');
    }
} 
       



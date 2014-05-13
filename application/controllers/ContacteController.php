<?php

/**
 * Description of ContacteController
 * 
 * Aquesta classe és el controlador per a la secció "Contacte" i la seva funció només és visual,
 * és a dir, només carregarà el "template" -> "contacte.html".
 *
 * @author javier
 */

class ContacteController extends ControllerBase {
    protected $model;
    protected $view;
    
    public function __construct($arr) {
        parent::__construct($arr);
       //carregar la configuració
       $this->view=new View();
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->config->APP_W));
        $this->view->addProp(array('THEME'=>$this->config->THEME));
        $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/contacte.html');
        $this->view->render();
    }
    
    public function index(){}
    
    
} 
       



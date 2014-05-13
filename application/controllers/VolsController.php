<?php

/**
 * Description of VolsController
 * 
 * Aquesta classe és el controlador per a la secció "Vols" i la seva funció només és visual,
 * és a dir, només carregarà el "template" -> "vols.html".
 *
 * @author javier
 */

class VolsController extends ControllerBase{
    protected $model;
    protected $view;
    
    public function __construct($arr) {
        parent::__construct($arr);
       //carregar la configuració
       $this->view=new View();
        //afegir configuració per ruta publica, enllaços, css ,js...
        $this->view->addProp(array('APP_W'=>$this->config->APP_W));
        $this->view->addProp(array('THEME'=>$this->config->THEME));
        $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/vols.html');
        $this->view->render();
    }
    
    public function index(){}
    
    
} 
       



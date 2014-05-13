<?php

/**
 * Description of ErrorController
 * 
 * Aquesta classe és el controlador per als errors, la qual serà cridada quan s'intenti 
 * accedir a un controlador o acció que no existeixi. La seva funció només és visual,
 * és a dir, només carregarà el "template" -> "errors.html".
 *
 * @author javier
 */
    class ErrorController extends ControllerBase{
        protected $model;
        protected $view;
        private $conf;
        private $arg;
        
        public function __construct($arr) {
            parent::__construct($arr);
            //carregar la configuració
            $this->conf=$this->config;
            $this->arg=$arr;
            $this->view=new View();
            //afegir configuració per ruta publica, enllaços, css ,js...
            $this->view->addProp(array('APP_W'=>$this->config->APP_W));
            $this->view->addProp(array('THEME'=>$this->config->THEME));
            $this->view->setTemplate(APP.'/public/themes/'.$this->config->THEME.'/tpl/errors.html');
            $this->view->render();
        }
        
        function index() {}
    }
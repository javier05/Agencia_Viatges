<?php

/**
 * Description of AjaxController
 *
 * Aquesta clase serà el controlador per a totes les funcions que es cridin a travès
 * de una funció AJAX de JavaScript o jQuery.
 * 
 * @author javier
 */

class AjaxController extends ControllerBase{
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
       $this->model= new AjaxModel($arr);
    }
    
    public function index(){}
    
    /**
     * Aquesta acció el que farà és cridar al mètode "buscar" de AjaxModel si com a paràmetres hem indicat
     * que volem buscar un hotel o un vol.
     */
    function buscar() {
        if(isset($_POST['tipus'])) {
            
            $tipus = $_POST['tipus'];
            $poblacio = null;
            $categoria = null;
            $desti = null;
            $places = $_POST['places'];
            
            if($tipus == 'hotel') {
                $poblacio = $_POST['poblacio'];
                $categoria = $_POST['categoria'];
            } else if($tipus == 'vol') {
                $desti = $_POST['desti'];
            }
            
            if($this->model->buscar($tipus, $desti, $poblacio, $categoria, $places) != TRUE)
                echo '<h5>No hi ha cap resultat.</h5>';
        }
    }
    
    /**
     * Aquesta acció el que farà és cridar al mètode "reserva" de AjaxModel si com a paràmetres
     * indiquem el tipus de reserva, hotel o vol.
     */
    function reservar() {
        if(isset($_POST['tipus'])) {
            $tipus = $_POST['tipus'];
            $id = $_POST['id'];
            $places = $_POST['places'];
            $this->model->reservar($tipus, $id, $places);
        }
    }
    
    /**
     * Aquesta acció el que farà és cridar al mètode "canviarDades" de AjaxModel, haurem de pasar com a 
     * paràmetres el nom, cognoms i número de la tarjeta (VISA) per actualitzar les dades personals 
     * a la base de dades.
     */
    function canviarDades() {
        $nom = $_POST["nom"];
        $cognoms = $_POST["cognoms"];
        $visa = $_POST["visa"];
        $this->model->canviarDades($nom,$cognoms,$visa);
    }
    
    /**
     * Aquesta acció el que farà és cridar al mètode "canviarContrasenya" de AjaxModel, haurem de indicar
     * la nova contrasenya per la qual volem canviar l'actual del nostre compte a la base dades.
     */
    function canviarContrasenya() {
        $password=md5(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        $this->model->canviarContrasenya($password);
    }
    
    /**
     * Aquesta acció el que farà és cridar al mètode "reserves" de AjaxModel.
     */
    function reserves() {
        $this->model->reserves();
    }
    
    /**
     * Aquesta acció el que farà és cridar al mètode "pagarReserva" de AjaxModel, indicant com a paràmetres 
     * el ID de la reserva y el preu total.
     */
    function pagarReserva() {
        $reserva = $_POST["reserva"];
        $preu = $_POST["preu"];
        $this->model->pagarReserva($reserva,$preu);
    }
} 
       



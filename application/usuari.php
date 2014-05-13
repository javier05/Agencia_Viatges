<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    class usuari{
        public $nom;
        public $cognoms;
        public $email;
        public $passwd;
        public $idrol;
        
        function __construct($nom,$cognoms,$email,$rol) {
            $this->nom=$nom;
            $this->cognoms=$cognoms;
            $this->email=$email;
            $this->idrol=$rol;
            
        }
              
    }

<?php

/**
 * Description of View
 *
 * @author toni
 */
class View  {
    /**
     *
     * @template plantilla en html
     * @properties propietats substituïbles al template i
     *  els seus valors en array associatiu.
     */
    protected static $template;
    protected static $head;
    protected static $body;
    protected static $foot;
    protected $properties;
    protected $conf;
    protected static $config;
    
    public function __construct() {
        $this->properties=array();
        $this->conf= Config::getInstance();
        //definir capçalera i peus comuns en totes les plantilles
        $file_h=APP.'public/themes/'.$this->conf->THEME.'/tpl/head.html';
        self::$head=file_get_contents($file_h);
        $file_f=APP.'public/themes/'.$this->conf->THEME.'/tpl/foot.html';
        self::$foot=file_get_contents($file_f);
        
    }
   
    /**
     * Estableix la plantilla
     * @param file $file
     */
    public static function setTemplate($file){
        self::$config = Config::getInstance();
        $compare=APP.'/public/themes/'.self::$config->THEME.'/tpl/error.html';
        // la tpl error es defineix de forma diferent
        if ($file===$compare){
            self::$template=  file_get_contents($file);
        } else{
            self::$body=  file_get_contents($file);
            self::$template= self::$head.self::$body.self::$foot;
        }
        
    }
    /**
     * Remplaçar propietats
     * en layout
     */
    public function setProp($arr){
        $this->properties=$arr;
    }
    /**
     * per afegir array de propietats a substituir
     * @param array $arr
     */
    public function addProp($arr){
        if ($arr || $this->properties){
            if ($this->properties==null){
                $this->properties=$arr;
            }
            else{
            $this->properties= array_merge($this->properties,$arr);
            }
        }
    }
    public function getTemplate(){
        return $this->template;
    }
    /**
     * Substitueix en la plantilla els valors dinàmics passats
     * a través de l'array de propietats
     */
    private function transform(){
        $html=  self::$template;
        if ($this->properties){
        foreach ($this->properties as $clau => $valor) {
            $html=str_replace('{'.$clau.'}',$valor, $html);
        }
        self::$template=$html;
        }
    }
    
    public  function render(){
        //comencem sortida
        ob_start();
        $this->transform();
        // la funció eval permet utilitzar expressions
        // del tipus <?= $valor; dintre de la plantilla
        eval('?>'.self::$template.'<?');
        
        
    }
            
}

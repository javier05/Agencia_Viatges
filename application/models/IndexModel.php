<?php

/**
 * Description of IndexModel
 * 
 * Aquesta classe és el controlador que contè els mètodes necessaris per a 
 * la identificació i creació d'usuaris.
 *
 * @author javier
 */
class IndexModel extends Model{
    
    public function __construct($arr) {
        parent::__construct($arr);
//        //parametres de configuració
//        $this->datain=$this->config;
//        //afegir en DataOut els paràmetres URI
//        $this->addDataout($arr);
    }
    
    /**
     * Aquest mètode rep un email i una contrasenya (encriptada amb MD5); es fa una petició a 
     * la base de dades per comprovar que aquest usuari existeix i la contrasenya concorda. Si 
     * tot és correcte, es creara una sessió nova amb diferents claus per indicar que l'usuari 
     * ha iniciat sessió correctament.
     * 
     * @param string $email
     * @param string $password
     * @return boolean
     */
    function login($email,$password){
        try{
            $sql="SELECT * FROM usuaris WHERE email=? AND contrasenya=?";
            $query=$this->db->prepare($sql);
            $query->bindParam(1,$email);
            $query->bindParam(2,$password);
            $query->execute();
            $res=$query->fetch();
            if($query->rowCount()==1){
                Session::set('islogged',TRUE);
                Session::set('email',$email);
                Session::set('usuari',$res['nom']);
                $user=  serialize(new usuari($res['nom'],$res['cognoms'],$res['email'],$res['tipus']));
                Session::set($user);
                return TRUE;
            }
            else {
                Session::set('islogged',FALSE);
                return FALSE;}
        }catch(PDOException $e){
            echo "Error:".$e->getMessage();
        }
    }
    
    /**
     * Aquest mètode rep un email, contrasenya, nom i cognoms. Es comprova a la base de dades que 
     * no existeix cap usuari amb aquest email. Si no existeix, s'afegeix a la base de dades i retornem 
     * "TRUE" (com a que ha estat correcte); si ja existia, retornem un "FALSE" (com a que s'ha produït 
     * un error).
     * 
     * @param string $email
     * @param string $password
     * @param string $nom
     * @param string $cognoms
     * @return boolean
     */
    function register($email,$password,$nom,$cognoms){
        try{
            $sql="SELECT * FROM usuaris WHERE email=? AND contrasenya=?";
            $query=$this->db->prepare($sql);
            $query->bindParam(1,$email);
            $query->bindParam(2,$password);
            $query->execute();
            $res=$query->fetch();
            if($query->rowCount()<1){
                $sql="INSERT INTO usuaris (nom, cognoms, email, contrasenya, direccio, tipus) VALUES (?,?,?,?, 1, 1)";
                $query=$this->db->prepare($sql);
                $query->bindParam(1,$nom);
                $query->bindParam(2,$cognoms);
                $query->bindParam(3,$email);
                $query->bindParam(4,$password);
                $query->execute();
                return TRUE;
            } else {
                return FALSE;
            }
        }catch(PDOException $e){
            echo "Error:".$e->getMessage();
        }
    }
    
    /**
     * Aquest mètode únicament destrueix la sessió iniciada prèviament al haver fet un inici de sessió.
     * 
     * @return boolean
     */
    function logout() {
        Session::destroy();
        return TRUE;
    }
}

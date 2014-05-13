<?php

/**
 * Description of AjaxModel
 *
 * Aquesta clase serà el model per a les crides d'AJAX mitjançant JavaScript o jQuery.
 * 
 * @author javier
 */

class AjaxModel extends Model{
    
    public function __construct($arr) {
        parent::__construct($arr);
    }
    
    /**
     * Aquest mètode rep com a paràmetres les propietats d'un hotel o d'un vol. Segons si es un hotel 
     * o un vol, buscarà a la base de dades les corresponents dades i les retornarà cap a la funció d'AJAX
     * que l'ha cridat.
     * 
     * @param string $tipus
     * @param string $desti
     * @param string $poblacio
     * @param int $categoria
     * @param int $places
     * @return boolean
     */
    function buscar($tipus,$desti,$poblacio,$categoria,$places) {
        try{
            if($tipus == 'hotel') {
                $sql="SELECT * FROM hotels WHERE poblacio LIKE \"%$poblacio%\" AND categoria >= $categoria ORDER BY nom ASC;";
                $query=$this->db->prepare($sql);
                $query->execute();
                if($query->rowCount()>=1){
                    while($res = $query->fetch()) {
                        echo '<div class="well">
                            <input type="hidden" name="hotel_'.$res["id"].'" value="'.$res["coordenades"].','.$places.'" />
                    <div class="mini-info">
                        <div style="display: inline-block; vertical-align: top;">
                            <h3>'.utf8_encode($res["nom"]).' '; 
                            for($i = 0; $i < $res["categoria"]; $i++) {
                                echo '<span class="estrella"></span>';
                            }
                            echo '</h3><div class="localitat">'.utf8_encode($res["poblacio"]).', '.utf8_encode($res["provincia"]).', '.utf8_encode($res["pais"]).'</div>
                        </div>
                        <div style="display: inline-block; vertical-align: top; float: right;">
                            <button class="btn btn-primary reservar" style="padding: 10px 20px;" id="reservar_'.$res["id"].'">Reservar</button>
                        </div>
                    </div>
                    <div class="maxi">
                        <div class="hotel_image" style="background-image: url(\''.APP_W.'/application/public/themes/'.THEME.'/img/'.$res["imatge"].'\');"></div>
                        <div class="hotel_descr">
                            '.utf8_encode($res["descripcio"]).'
                        </div>
                        <div id="hotel_'.$res["id"].'" class="hotel_mapa"></div>
                    </div>
                    <div class="mostrar-ocultar">Más información</div>
                </div>';
                    }
                    return TRUE;
                }
                else
                    return FALSE;
            }
            elseif($tipus == 'vol') {
                $sql="SELECT * FROM vols WHERE desti LIKE \"%$desti%\" ORDER BY desti ASC;";
                $query=$this->db->prepare($sql);
                $query->execute();
                if($query->rowCount()>=1){
                    while($res = $query->fetch()) {
                        echo '<div class="well">
                            <input type="hidden" name="vol_'.$res["id"].'" value="'.$places.'" />
                    <div class="mini-info">
                        <div style="display: inline-block; vertical-align: top;">
                            <h3>'.utf8_encode($res["desti"]).' ';
                            echo '</h3><div class="localitat">'.utf8_encode($res["aeroport_origen"]).'&nbsp;&nbsp;&nbsp;<img src="'.APP_W.'/application/public/themes/'.THEME.'/img/avio.png" width="32" style="vertical-align: bottom;" />&nbsp;&nbsp;&nbsp;'.utf8_encode($res["aeroport_desti"]).'</div>
                        </div>
                        <div style="display: inline-block; vertical-align: top; float: right;">
                            <button class="btn btn-primary reservar" style="padding: 10px 20px;" id="reservar_'.$res["id"].'">Reservar</button>
                        </div>
                    </div>
                </div>';
                    }
                    return TRUE;
                }
                else
                    return FALSE;
            }
        }catch(PDOException $e){
            echo "Error:".$e->getMessage();
        }
    }
    
    /**
     * Aquest mètode rep com a paràmetres un tipus (hotel o vol), una ID del servei i les places a reservar.
     * Es cridarà a un procediment d'SQL, anomenat "contractar_servei" que durà a terme tot el procediment 
     * per a la reserva del servei.
     * 
     * @param string $tipus
     * @param int $id
     * @param int $places
     * @return boolean
     */
    function reservar($tipus,$id,$places) {
        try {
            $userMail = Session::get('email');
            $query=$this->db->prepare("SELECT id FROM usuaris WHERE email = ?");
            $query->bindParam(1,$userMail);
            $query->execute();
            $res=$query->fetch();
            if($query->rowCount()>=1)
                $id_usuari = $res[0];
            else {
                echo json_encode(array('error' => "Has d'iniciar sessió per realitzar una reserva"));
                return false;
            }
            
            if($tipus == 'hotel' || $tipus == 'vol') {
                $sql="CALL contractar_servei(?,?,NOW(),NULL,?)";
                $query=$this->db->prepare($sql);
                $query->bindParam(1,$id);
                $query->bindParam(2,$id_usuari);
                $query->bindParam(3,$places);
                $query->execute();
            }
            echo json_encode(array('error' => null));
            return true;
        } catch(PDOException $e){
            echo json_encode(array('error' => $e->getCode()));
            return false;
        }
    }
    
    /**
     * Aquest mètode actualitza les dades personals del compte d'un usuari a la base de dades.
     * Només es pot canviar el nom, els cognoms i el número de la targeta (VISA).
     * 
     * @param string $nom
     * @param string $cognoms
     * @param string $visa
     */
    function canviarDades($nom,$cognoms,$visa) {
        $userMail = Session::get('email');
        $sql = "UPDATE usuaris SET nom = '$nom', cognoms = '$cognoms', visa = ? WHERE email = ?";
        $query=$this->db->prepare($sql);
        $query->bindParam(1,$visa);
        $query->bindParam(2,$userMail);
        $query->execute();
        if($query->rowCount() > 0)
            echo "S'han actualitzat correctament les teves dades personals.";
    }
    
    /**
     * Aquest mètode canvia la contrasenya actual del compte d'un usuari per una de nova.
     * 
     * @param type $pass
     */
    function canviarContrasenya($pass) {
        $userMail = Session::get('email');
        $sql = "UPDATE usuaris SET contrasenya = ? WHERE email = ?";
        $query=$this->db->prepare($sql);
        $query->bindParam(1,$pass);
        $query->bindParam(2,$userMail);
        $query->execute();
        if($query->rowCount() > 0)
            echo "S'ha actualitzat correctament la contrasenya del teu comte.";
    }
    
    /**
     * Aquest mètode fa un llistat de les reserves d'un usuari, i un sub-llistat amb
     * els serveis corresponents de cada reserva.
     * 
     * @return boolean
     */
    function reserves() {
        $userMail = Session::get('email');
        $query=$this->db->prepare("SELECT id FROM usuaris WHERE email = ?");
        $query->bindParam(1,$userMail);
        $query->execute();
        $res=$query->fetch();
        $id_usuari = $res[0];

        $sql="SELECT reserves.id, reserves.data_reserva, codis_estat_reserva.descripcio, consultar_preu_reserva(reserves.id) AS preu_reserva FROM reserves
              INNER JOIN codis_estat_reserva ON reserves.codi_estat = codis_estat_reserva.codi
              WHERE reserves.client = $id_usuari";
        $query=$this->db->prepare($sql);
        $query->execute();
        if($query->rowCount()>=1){
            while($res = $query->fetch()) {
                $estatDesc = utf8_encode($res["descripcio"]);
                if($estatDesc == "Oberta")
                    $estat = '<span class="estats estat-oberta">'.$estatDesc.'</span><button id="pagar_'.$res["id"].'_'.number_format(floatval($res["preu_reserva"]), 2).'" style="margin-right: 10px;" class="btn btn-primary estats pagar">Pagar</button>';
                elseif($estatDesc == "Pagada" ||$estatDesc == "Parcialment pagada")
                    $estat = '<span class="estats estat-pagada">'.$estatDesc.'</span>';
                elseif($estatDesc == "Tancada")
                    $estat = '<span class="estats estat-tancada">'.$estatDesc.'</span>';
                elseif($estatDesc == "Anul·lada")
                    $estat = '<span class="estats estat-anulada">'.$estatDesc.'</span>';
                
                echo '<div class="well">'
                . '<h4 style="display: inline-block; margin-bottom: 20px;">Data: '.date('d/m/Y', strtotime($res["data_reserva"])).'&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Preu Total: '.number_format(floatval($res["preu_reserva"]), 2).' €</h4>'
                . $estat
                . '<div class="maxi">';
                
                $sql2="SELECT vols.desti, vols.aeroport_origen, vols.aeroport_desti, vols.data_vol, hotels.nom, hotels.categoria, hotels.poblacio, hotels.provincia, hotels.pais, serveis.descripcio, serveis.preu, serveis_reserves.data_inici, serveis_reserves.data_fi, serveis_reserves.places FROM serveis
                       INNER JOIN serveis_reserves ON serveis_reserves.servei = serveis.id
                       INNER JOIN reserves ON reserves.id = serveis_reserves.reserva
                       LEFT JOIN hotels ON hotels.id = serveis.id
                       LEFT JOIN vols ON vols.id = serveis.id
                       WHERE reserves.id = {$res['id']}";
                $query2=$this->db->prepare($sql2);
                $query2->execute();
                if($query2->rowCount()>=1){
                    while($ser = $query2->fetch()) {
                        $places = $ser["places"];
                        switch($ser["descripcio"]) {
                            case "Hotel":
                                echo '<div class="well">
                            <div class="mini-info">
                                <div style="display: inline-block; vertical-align: top;">
                                    <h3>'.$ser["nom"].' '; 
                                    for($i = 0; $i < $ser["categoria"]; $i++) {
                                        echo '<span class="estrella"></span>';
                                    }
                                    echo '</h3><div class="localitat">'.utf8_encode($ser["poblacio"]).', '.utf8_encode($ser["provincia"]).', '.utf8_encode($ser["pais"]).'</div>
                                </div>
                                <div style="display: inline-block; vertical-align: top; float: right;">
                                    <span class="estats estat-reservat">'.$places.' plaçes</button>
                                </div>
                            </div>
                        </div>';
                                break;
                                
                            case "Vol":
                                echo '<div class="well">
                            <div class="mini-info">
                                <div style="display: inline-block; vertical-align: top;">
                                    <h3>'.utf8_encode($ser["desti"]).' ';
                                    echo '</h3><div class="localitat">'.utf8_encode($ser["aeroport_origen"]).'&nbsp;&nbsp;&nbsp;<img src="'.APP_W.'/application/public/themes/'.THEME.'/img/avio.png" width="32" style="vertical-align: bottom;" />&nbsp;&nbsp;&nbsp;'.utf8_encode($ser["aeroport_desti"]).'</div>
                                </div>
                                <div style="display: inline-block; vertical-align: top; float: right;">
                                    <span class="estats estat-reservat">'.$places.' plaçes</button>
                                </div>
                            </div>
                        </div>';
                                break;
                        }
                    }
                }
                echo '</div>'
                . '<div class="mostrar-ocultar">Más información</div>'
                . '</div>';
            }
            return TRUE;
        }
    }
    
    /**
     * Aquest mètode rep com a paràmetres la ID de la reserva i el preu total. Es crida a un 
     * procediment de la base de dades que, automàticament, farà un pagament de la reserva i 
     * aquesta reserva canviarà el seu estat a "Pagada" o "Parcialment pagada" gràcies a un trigger.
     * 
     * @param int $reserva
     * @param float $preu
     */
    function pagarReserva($reserva,$preu) {
        $sql = "CALL pagar_reserva(?,?)";
        $query=$this->db->prepare($sql);
        $query->bindParam(1,$preu);
        $query->bindParam(2,$reserva);
        $query->execute();
        
        if($query->rowCount() > 0)
            echo "ok";
    }
}

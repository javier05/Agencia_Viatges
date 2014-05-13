<?php

class DbCrud{

  // Conexion db PDO ``
    
    protected function _getDbh() {
        return DbPdo::getInstance()->getConn();
    }
     
  public function save($table, $datas){
     /* $sql = "INSERT INTO tabla (campo1, campo2, campo3) 
      *         VALUES (?, ?, ?)"
      *  $stm->prepare($sql);
      *  $stm->bindValue(1, valor1);
      *  $stm->bindValue(2, valor2);
      *  $stm->bindValue(3, valor3);
      *  $stm->execute();
      */
    $result = null;
    $iterator = new ArrayIterator($datas);
      $sql = "INSERT INTO `$table` ("; 
        while ($iterator->valid()) {
          $sql .= "`" . $iterator->key() . "`, ";   
         $iterator->next();
        }
        $sql = substr($sql, 0, -2);
        $sql .= ") VALUES (";

        for($i=0; $i < $iterator->count(); $i++){
          $sql .= " ?,";
        }
        $sql  = substr($sql, 0, -1);
        $sql .= ")";
      $stm = $this->_getDbh()->prepare($sql);
      $i = 1; 
      foreach ($iterator as $param) {
        $stm->bindValue($i, $param);
        $i++;
      }
      $result = $stm->execute();    
      return $result;
  }
 

}
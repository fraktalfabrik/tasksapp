<?php
namespace Service;

if (!defined('SQLITE3_TEXT'))   {   define('SQLITE3_TEXT',      2); }
if (!defined('SQLITE3_INTEGER')){   define('SQLITE3_INTEGER',   1); }

if (!defined('SQLITE_PATH'))    {   define('SQLITE_PATH',       "db\sqlite\HealthMetrics.sqlite"); }
if(!is_file(SQLITE_PATH)){ die("There is no DB here: ".SQLITE_PATH);}

include_once ('ServiceInterface.php');


class Service
{
    public $_db;

    public function __construct(){
        $this->_db = new \PDO('sqlite:'.SQLITE_PATH);
    }


    public function fetchAll(){
        return $this->_db->query("SELECT * FROM $this->_table")->fetchAll();
    }

    public function updateBoolean($id, $field){
        if(is_numeric($id)) {
            $this->_db->query("UPDATE " . $this->_table . " SET $field = (($field | 1) - ($field & 1)) WHERE id = $id ");
            return $this->markAsUpdated($id);
        }
    }

    public function deleteOne($id){
        $stmt = $this->_db->prepare("DELETE FROM $this->_table WHERE id=:id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        return $stmt->execute();
    }

    public function createOne($payload){
        $vals = $keys = '';
        foreach($this->_fields_add_edit as $field => $dataType){
            $vals .= ' :'.$field;
            $keys .=  $field.',';
        }
        $keys = substr($keys,0 , -1);
        $sql = "INSERT INTO $this->_table ($keys) VALUES ($vals)";
        $stmt = $this->_db->prepare($sql);
        foreach($this->_fields_add_edit as $field => $dataType){
            if(isset($payload[$field])){
                 $value = $payload[$field];
                if($dataType ==SQLITE3_TEXT){
                    $value = htmlspecialchars($value);
                }
                 $stmt->bindParam(":$field", $value, $dataType);
             }
        }
        return $stmt->execute();
    }

    public function updateOne($id, $payload){
        if(is_numeric($id)) {
            $vals = $keys = '';
            foreach ($this->_fields_add_edit as $field => $dataType) {
                $vals .= ' :' . $field;
                $keys .= $field . ',';
            }
            $keys = substr($keys, 0, -1);
            $sql = "UPDATE $this->_table SET ($keys) = ($vals) WHERE ID=$id";
            $stmt = $this->_db->prepare($sql);
            foreach ($this->_fields_add_edit as $field => $dataType) {
                $value = $payload[$field];
                if($dataType == SQLITE3_TEXT){
                    $value = htmlspecialchars($value);
                }
                $stmt->bindParam(":$field", $value, $dataType);
            }
            if ($stmt->execute()){
                return $this->markAsUpdated($id);
            }
         }
    }


    private function markAsUpdated($id){
        $date = date('Y-m-d H:i:s');
        return  $this->_db->query("UPDATE $this->_table SET ('updated') = ('$date') WHERE ID=$id");
    }
}
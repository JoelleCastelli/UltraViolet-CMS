<?php
namespace App\Core;

use App\Models\User as UserModel;

class Database{

	private $pdo;
	private $table;

	public function __construct(){
		try{
			$this->pdo = new \PDO( DBDRIVER.":host=".DBHOST.";dbname=".DBNAME.";port=".DBPORT , DBUSER , DBPWD );
		}catch(Exception $e){
			die("Erreur SQL : ".$e->getMessage());
		}

	 	//  jclm_   App\Models\User -> jclm_User
	 	$classExploded = explode("\\", get_called_class());
		$this->table = strtolower(DBPREFIXE.end($classExploded)); //jclm_User
	}


	public function save(){

		//INSERT OU UPDATE

		$column = array_diff_key(get_object_vars($this), get_class_vars(get_class()));

		if (is_null($this->getId())) {
			//INSERT
			$query = $this->pdo->prepare("INSERT INTO ".$this->table." 
                (".implode(',', array_keys($column)).") 
                VALUES 
                (:".implode(',:', array_keys($column)).") "); //1
			
		} else {
			//UPDATE

            $str = "";
            foreach($column as $key => $value) // build string for update "propertie = :propertie"
            {
                $str .= $key . " = :" . $key . ", ";
            }
            $str = substr($str, 0, -2); // remove the last space and last comma

            $query = $this->pdo->prepare("UPDATE ".$this->table." SET " . $str . " WHERE id = " . $this->getId());
		}

		$query->execute($column);
	}

	public function findOneById($id)
    {
        $column = array_diff_key(get_object_vars($this), get_class_vars(get_class())); // get properties of the model

        $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE id= " . $id); // get one row
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this)); // return instance
        $object = $query->fetch();

        foreach($column as $key => $value)
        {
            $this->$key = $object->$key; // assign each value to the current object
        }
    }
}
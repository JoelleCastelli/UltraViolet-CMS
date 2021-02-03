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

            echo "<pre>";
            print_r($this);

            $object = $this->findOneById($this->getId());

            print_r($object);

            //change properties

            //final update


		}

		//$query->execute($column);
	}

	public function findOneById($id)
    {
        $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE id= " . $id); //1
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        $object = $query->fetch();

        return $object;
    }

}
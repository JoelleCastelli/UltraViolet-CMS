<?php
namespace App\Core;

class Database {

    protected $pdo;
    private $table;
    private $query;

    private $order = 0;

    public function __construct() {

	    if(ENV === "dev") {
            try {
                $this->pdo = new \PDO( DBDRIVER.":host=".DBHOST.";dbname=".DBNAME.";port=".DBPORT , DBUSER , DBPWD );
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e) {
                die("Erreur SQL : ".$e->getMessage());
            }
        } else if (ENV === "prod") {
            try {
                $this->pdo = new \PDO( DBDRIVER.":host=".DBHOST.";dbname=".DBNAME.";port=".DBPORT , DBUSER , DBPWD );
            } catch(Exception $e) {
                die("Erreur connexion bdd côté production");
            }
        }
        $classExploded = explode("\\", get_called_class());
        $this->table = strtolower(DBPREFIXE.end($classExploded));
        $this->query = "SELECT * FROM ". $this->table . " ";

    }

	public function save(){

        $column = array_diff_key(get_object_vars($this), get_class_vars(get_class()));

        // INSERT
		if (is_null($this->getId())) {
			$query = $this->pdo->prepare("INSERT INTO ".$this->table." 
                (".implode(',', array_keys($column)).") 
                VALUES 
                (:".implode(',:', array_keys($column)).") "); //1
		}
        //UPDATE
		else {
            $str = "";
            // build string for update -> "propertie = :propertie"
            foreach($column as $key => $value) {
                $str .= $key." = :".$key.", ";
            }
            // remove the last space and last comma
            $str = substr($str, 0, -2);

            $query = $this->pdo->prepare("UPDATE ".$this->table." SET " . $str . " WHERE id = " . $this->getId());
		}
		
        $query->execute($column);
    }

	public function findOneById($id){
        $column = array_diff_key(get_object_vars($this), get_class_vars(get_class())); // get properties of the model

        $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE id= " . $id); // get one row by the id
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this)); // return instance

        $object = $query->fetch();

        foreach($column as $key => $value) {
            $this->$key = $object->$key; // assign each value to the current object
        }
    }

    public function findAll(){
        $query = $this->pdo->query("SELECT * FROM " . $this->table);
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        return $query->fetchAll();
    }

    public function selectWhere($column, $value) {
        $query = $this->pdo->query("SELECT * FROM ".$this->table." WHERE ".$column." = '".$value."'");
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        return $query->fetchAll();
    }

    public function where($column, $value, $equal = "=" ) {
        $this->query .= "WHERE " . $column . " " . $equal . " '" . htmlspecialchars($value, ENT_QUOTES) . "' ";
    }

    public function andWhere($column, $value, $equal = "=") {
        echo "<br><br>" . $this->query . "<br><br>";
        $this->query .= " AND " . $column . " " . $equal . " '" . htmlspecialchars($value, ENT_QUOTES) . "' ";

    }

    public function order($column, $order = "ASC") {

        if($this->order == 0) {
            $this->query .= "ORDER BY " . $column . " " . $order . "' ";
            $this->order++;
        }
        else
            $this->query .= ", " . $column . " ". $order . "' ";
    }

    public function get() {
        echo "<br><br>" . $this->query . "<br><br>";
        $query = $this->pdo->query($this->query);
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        return $query->fetchAll();
    }



}
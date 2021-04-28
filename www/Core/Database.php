<?php
namespace App\Core;

class Database {

    protected $pdo;
    private $table;
    private $query;

    private $order = 0;
    private $like = 0;

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
    }

    /* GENERAL QUERY */
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

    /* BUILDING QUERY */


    // QUERY BEGINNING
    public function select() {
        $this->query = "SELECT * FROM " . $this->table . " ";
        return $this; // Activate chaining
    }

    public function delete() {
        $this->query = "DELETE FROM " . $this->table . " ";
        return $this;
    }

    public function count($column = "*")
    {
        $this->query = "SELECT COUNT(" . $column . ") ";
        return $this;
    }


    public function customQuery($string)
    {
        $this->query .= $string . " ";
        return $this;
    }

    // WHERE
    public function where($column, $value, $equal = "=" ) {
        $this->query .= "WHERE " . $column . " " . $equal . " '" . htmlspecialchars($value, ENT_QUOTES) . "' ";
        return $this;
    }

    public function andWhere($column, $value, $equal = "=") {
        $this->query .= "AND " . $column . " " . $equal . " '" . htmlspecialchars($value, ENT_QUOTES) . "' ";
        return $this;
    }

    public function orWhere($column, $value, $equal = "=") {
        $this->query .= "OR " . $column . " " . $equal . " '" . htmlspecialchars($value, ENT_QUOTES) . "' ";
        return $this;
    }

    public function whereIn($column, $value) {
        $this->query .= "WHERE " . $column . " IN " . $value . " ";
        return $this;
    }

    // OTHERS
    public function groupBy()
    {
        $this->query .= "GROUP BY " . $column . " ";
        return $this;
    }

    public function limit($limit = 10, $offset = 0)
    {
        // Use syntax the offset syntax because of the compatibility with others DBMS (database management system)
        $this->query .= "LIMIT " . $value . " OFFSET " . $offset . " ";
        return $this;
    }

    public function like($column, $value, $escape = "") {
        if($this->like == 0) { 
            $this->query .= "WHERE " . $column . " LIKE '" . $value . "' " . $escape . " ";
            $this->like++;

        } else{
            $this->query .= "AND " . $column . " LIKE '" . $value . "' " . $escape . " ";
        }
        return $this;
    }

    public function order($column, $order = "ASC") {

        if($this->order == 0) {
            $this->query .= "ORDER BY " . $column . " " . $order . "' ";
            $this->order++;
        }
        else
            $this->query .= ", " . $column . " ". $order . "' ";
        return $this;
    }

    public function get(){

        echo "<br><br>" . $this->query . "<br><br>";

        $query = $this->pdo->query($this->query);
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));


        return $query->fetchAll();
    }

    public function getQueryString()
    {
        return $this->query;
    }
}
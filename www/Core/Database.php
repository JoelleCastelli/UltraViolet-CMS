<?php

namespace App\Core;

use Exception;

class Database {

    protected ?\PDO $pdo = null;
    private string $table;
    private ?string $query = null;

    private int $order = 0;
    private int $like = 0;

    protected function __construct() {
        if ($this->pdo === null) {
            if (ENV === "dev") {
                try {
                    $this->pdo = new \PDO(DBDRIVER.":host=".DBHOST."; dbname=".DBNAME."; port=".DBPORT."; charset=UTF8", DBUSER, DBPWD);
                    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                } catch (Exception $e) {
                    die("Erreur SQL (dev mode) : " . $e->getMessage());
                }
            } else if (ENV === "prod") {
                try {
                    $this->pdo = new \PDO(DBDRIVER.":host=".DBHOST."; dbname=".DBNAME."; port=".DBPORT."; charset=UTF8", DBUSER, DBPWD);
                } catch (Exception $e) {
                    die("La connexion à la base de données n'a pas pu être effectuée");
                }
            }
        }
        $classExploded = explode("\\", get_called_class());
        $this->table = strtolower(Helpers::convertToSnakeCase(DBPREFIXE . end($classExploded)));
    }

    /* GENERAL QUERY */
	public function save() {
        $columns = array_diff_key(get_object_vars($this), get_class_vars(get_class()));

        // Unset $column if its an object
        foreach($columns as $key => $value) {
            if(is_object($value)) { unset($columns[$key]); }
        }

        if (is_null($this->getId())) {
            // INSERT
            $query = $this->pdo->prepare("INSERT INTO " . $this->table . " 
            (`" . implode('`, `', array_keys($columns)) . "`) 
            VALUES 
            (:" . implode(', :', array_keys($columns)) . ") ");
        } else {
            //UPDATE
            $str = "";
            // build string for update -> "propertie = :propertie"
            foreach ($columns as $key => $value) {
                $str .= $key . " = :" . $key . ", ";
            }
            // remove the last space and last comma
            $str = substr($str, 0, -2);
            $query = $this->pdo->prepare("UPDATE " . $this->table . " SET " . $str . " WHERE id = " . $this->getId());
        }

        try {
            // Workaround: prevent MySql from interpreting bool(false) as empty string
            foreach ($columns as $key => $value) {
                if(gettype($value) === "boolean" && $value === false) { $columns[$key] = 0; }
            }
            
            return $query->execute($columns);
        } catch (\Exception $e) {
            echo "EXCEPTION : Incorrect query<br>" . $e->getMessage();
            die();
        }
    }

    public function populate($id) {
        if(!empty($id) && is_numeric($id)) {
            // query
            $query = $this->pdo->query("SELECT * FROM " . $this->table . " WHERE id= " . $id);
            $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this)); // return instance
            $object = $query->fetch();

            // populate
            if (!empty($object)) {
                return $object;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function findOneBy($column, $value)  {
        $query = $this->pdo->query('SELECT * FROM '.$this->table.' WHERE `'.$column.'` = "'.htmlspecialchars($value).'"');
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        return $query->fetch();
    }

    public function findAll(): array
    {
        $query = $this->pdo->query("SELECT * FROM " . $this->table);
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        return $query->fetchAll();
    }

    public function selectWhere($column, $value): array
    {
        $query = $this->pdo->query('SELECT * FROM '.$this->table.' WHERE `'.$column.'` = "'. htmlspecialchars($value).'"');
        $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));
        return $query->fetchAll();
    }

    /* BUILDING QUERY */

    // QUERY BEGINNING
    public function select($values = '*'): Database
    {
        $this->query = "SELECT ".$values." FROM " . $this->table . " ";
        return $this; // Activate chaining
    }

    public function delete() {
        if ($this->getDeletedAt()) {
            // hard delete
            $query = $this->pdo->prepare("DELETE FROM " . $this->table . " WHERE `id`=" . $this->getId());
            try {
                return $query->execute();
            } catch (\Exception $e) {
                echo "EXCEPTION : Query not correct <br>" . $e->getMessage();
                die();
            }
        } else {
            // soft
            $this->setDeletedAt(Helpers::getCurrentTimestamp());
            return $this->save();
        }
    }

    public function hardDelete(): Database
    {
        $this->query = 'DELETE FROM ' . $this->table . ' ';
        return $this;
    }

    public function count($column = "*"): Database
    {
        $this->query = 'SELECT COUNT(`' . $column . '`) as total FROM ' . $this->table . ' ';
        return $this;
    }

    public function sum($column = "*"): Database
    {
        $this->query = 'SELECT SUM(`' . $column . '`) as total FROM ' . $this->table . ' ';
        return $this;
    }

    public function customQuery($string): Database
    {
        $this->query .= $string . ' ';
        return $this;
    }

    // WHERE
    public function where($column, $value, $equal = "=" ): Database
    {

        if($value === 'NOT NULL') {
            $this->query .= 'WHERE `' . $column . '` IS NOT NULL ';
        } else if ($value === 'NULL') {
            $this->query .= 'WHERE `' . $column . '` IS NULL ';
        } else {
            $this->query .= 'WHERE `' . $column . '` ' . $equal . ' "' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }
        return $this;
    }

    public function andWhere($column, $value, $equal = "="): Database
    {
        if($value == 'NOT NULL') {
            $this->query .= 'AND `' . $column . '` IS NOT NULL ';
        } else if ($value == "NULL") {
            $this->query .= 'AND `' . $column . '` IS NULL ';
        } else {
            $this->query .= 'AND `' . $column . '` ' . $equal . ' "' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }
        return $this;
    }

    public function orWhere($column, $value, $equal = "="): Database
    {
        $this->query .= 'OR `' . $column . '` ' . $equal . ' "' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        return $this;
    }

    public function whereIn($column, $value): Database
    {

        if (is_array($value)) {
            $result = "('" . implode("','", $value) . "')";
        } else {
            $result = "('" . $value . "')";
        }

        $this->query .= 'WHERE `' . $column . '` IN ' . $result . ' ';
        return $this;
    }

    //JOINS
    public function innerJoin($table, $statement): Database
    {
        $this->query .= 'INNER JOIN ' . $table . ' ON ' . htmlspecialchars($statement, ENT_QUOTES) . ' ';
        return $this;
    }

    public function leftJoin($table, $statement): Database
    {
        $this->query .= 'LEFT JOIN ' . $table . ' ON ' . htmlspecialchars($statement, ENT_QUOTES) . ' ';
        return $this;
    }

    public function rightJoin($table, $statement): Database
    {
        $this->query .= 'RIGHT JOIN ' . $table . ' ON ' . htmlspecialchars($statement, ENT_QUOTES) . ' ';
        return $this;
    }

    public function fullJoin($table, $statement): Database
    {
        $this->query .= 'FULL JOIN ' . $table . ' ON ' . htmlspecialchars($statement, ENT_QUOTES) . ' ';
        return $this;
    }

    // OTHERS
    public function groupBy($column): Database
    {
        $this->query .= 'GROUP BY `' . $column . '` ';
        return $this;
    }

    public function limit($limit = 10, $offset = 0): Database
    {
        // Use syntax the offset syntax because of the compatibility with others DBMS (database management system)
        $this->query .= 'LIMIT ' . $limit . ' OFFSET ' . $offset . ' ';
        return $this;
    }

    public function like($column, $value, $escape = ""): Database
    {
        if($this->like == 0) { 
            $this->query .= 'WHERE `' . $column . '` LIKE "' . $value . '" ' . $escape . ' ';
            $this->like++;
        } else{
            $this->query .= 'AND `' . $column . '` LIKE "' . $value . '" ' . $escape . ' ';
        }
        return $this;
    }

    public function orderBy($column, $order = "ASC"): Database
    {
        if($this->order == 0) {
            $this->query .= 'ORDER BY `' . $column . '` ' . $order . ' ';
            $this->order++;
        }
        else
            $this->query .= ', `' . $column . '` '. $order . ' ';
        return $this;
    }

    public function get($setFetchMode = true): array
    {
        $this->order = 0;
        $this->like = 0;
        $query = $this->pdo->query($this->query);

        if ($setFetchMode)
            $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));

        try {
            if ($setFetchMode)
                return $query->fetchAll();
            else
                return $query->fetchAll(\PDO::FETCH_COLUMN);

        } catch (\Exception $e) {
            echo "EXCEPTION : Query not correct <br>" . $e->getMessage();
            die();
        }
    }

    public function execute(): bool
    {
        $this->order = 0;
        $this->like = 0;
        $query = $this->pdo->prepare($this->query);
        try {
            return $query->execute();
        } catch (\Exception $e) {
            echo "EXCEPTION : Query not correct <br>" . $e->getMessage();
            die();
        }
    }

    public function first($setFetchMode = true)
    {
        $this->order = 0;
        $this->like = 0;
        $query = $this->pdo->query($this->query);

        if ($setFetchMode)
            $query->setFetchMode(\PDO::FETCH_CLASS, get_class($this));

        try {

            if ($setFetchMode)
                return $query->fetch();
            else
                return $query->fetch(\PDO::FETCH_COLUMN);

        } catch (\Exception $e) {
            echo "EXCEPTION : Query not correct <br>" . $e->getMessage();
            die();
        }
    }
  
    public function generateActionsMenu(): string {
        $classPath = explode('\\', get_class($this));
        $class = mb_strtolower(end($classPath));

        $actions = "<div class='actionsMenu'><div class='bubble-actions'></div><div class='actionsDropdown'>";
        foreach ($this->getActions() as $action) {
            if (!isset($action['role']) || (isset($action['role']) && Request::getUser()->checkRights(($action['role'])))) {
                $tag = in_array($action['action'], ['delete', 'update-state']) ? "span" : "a";
                $class = $action['class'] ?? $action['action'];
                if($tag === 'a')
                    $actions .= "<$tag id='".$class.'-'.$action['action'].'-'.$this->getId()."' class='".$class."' href='".$action['url']."'>".$action['name']."</$tag>";
                else
                    $actions .= "<$tag id='" . $class . '-' . $action['action'] . '-' . $this->getId() . "' class='" . $class . " clickable-tag' >" . $action['name'] . "</$tag>";
            }
        }
        $actions .= "</div></div>";
        return $actions;
    }

    public function getLastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

}
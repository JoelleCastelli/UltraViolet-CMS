<?php

namespace App\Core;

use Exception;

class DatabaseConnection {

    private ?\PDO $pdo = null;

    private static $instance;

    public function getConnection()
    {
        if ($this->pdo === null) {
            if (ENV === "dev") {
                try {
                    $this->pdo = new \PDO(DBDRIVER . ":host=" . DBHOST . "; dbname=" . DBNAME . "; port=" . DBPORT . "; charset=UTF8", DBUSER, DBPWD);
                    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                } catch (Exception $e) {
                    die("Erreur SQL (dev mode) : " . $e->getMessage());
                }
            } else if (ENV === "prod") {
                try {
                    $this->pdo = new \PDO(DBDRIVER . ":host=" . DBHOST . "; dbname=" . DBNAME . "; port=" . DBPORT . "; charset=UTF8", DBUSER, DBPWD);
                } catch (Exception $e) {
                    die("La connexion à la base de données n'a pas pu être effectuée");
                }
            }
        }
        return $this->pdo;
    }

    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new DatabaseConnection();
        }
        return static::$instance;
    }
}
<?php

namespace App\Core;

use Exception;
use DateTime;

class DatabaseConnection {

    private ?\PDO $pdo = null;

    private static $instance;

    private function getOffset($timezone) : string {
        
        // Set PHP Timezone
        date_default_timezone_set($timezone);

        // Obtain the Offset
        $time		= new DateTime();
        $minutes	= $time->getOffset() / 60;
        $sign		= ( $minutes < 0 ? -1 : 1 );
        $minutes	= abs( $minutes );
        $hours		= floor( $minutes / 60 );

        $minutes -= $hours * 60;

        $offset = sprintf( '%+d:%02d', $hours * $sign, $minutes );
        return $offset;
    }

    public function getConnection()
    {
        if ($this->pdo === null) {

            $offset = $this->getOffset('Europe/Paris');

            if (ENV === "dev") {
                try {
                    $this->pdo = new \PDO(DBDRIVER . ":host=" . DBHOST . "; dbname=" . DBNAME . "; port=" . DBPORT . "; charset=UTF8", DBUSER, DBPWD);
                    // Set DB Timezone using the Offset
	                $this->pdo->exec( "SET time_zone='$offset';" );
                    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                } catch (Exception $e) {
                    die("Erreur SQL (dev mode) : " . $e->getMessage());
                }
            } else if (ENV === "prod") {
                try {
                    $this->pdo = new \PDO(DBDRIVER . ":host=" . DBHOST . "; dbname=" . DBNAME . "; port=" . DBPORT . "; charset=UTF8", DBUSER, DBPWD);
                    // Set DB Timezone using the Offset
	                $this->pdo->exec( "SET time_zone='$offset';" );
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
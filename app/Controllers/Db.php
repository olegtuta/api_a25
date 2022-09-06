<?php

namespace App\Controllers;

class Db
{
    private $dbConnection;

    //Подключаемся к БД
    public function connect(): object
    {

        try {
            $db = new \PDO("sqlite:database.sqlite");
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $this->dbConnection = $db;
        return $db;

    }

    public function getData(): array
    {
        $query = $this->dbConnection->query("SELECT * FROM customers");
        return $query->fetchAll();
    }

}

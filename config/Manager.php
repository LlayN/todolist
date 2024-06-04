<?php

abstract class Manager
{
  protected function connectDatabase()
  {
    try {
      $pdo = new PDO("mysql:host=localhost;dbname=todolist", "root", "");
      return $pdo;
    } catch (PDOException $e) {
      echo "Connexion KO : " . $e->getMessage();
    };
  }
};

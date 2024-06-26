<?php

class Manager
{
  public function connectDatabase()
  {
    try {
      $pdo = new PDO("mysql:host=localhost;dbname=todolist", "root", "");

      return $pdo;
    } catch (PDOException $e) {
      throw $e;
    }
    ;
  }
}
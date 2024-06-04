<?php

class HomeManager extends Manager
{

  public function getAllTask()
  {
    $pdo = $this->connectDatabase();
    $stmt = $pdo->prepare("SELECT * FROM task");
    $stmt->execute();
    $allTask = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $allTask;
  }
}

<?php

if (isset($_POST['update'])) {

  $taskManager = new TaskManager(id: $_POST['id'], e: $_POST['state']);
  $taskManager->updateState();
}


class TaskManager extends Manager
{



  protected $titre;
  protected $date_tache;
  protected $heure_tache;
  protected $etat;
  public $id;

  public function __construct($t = "", $d_t = "", $h_t = "", $e = "", $id = "")
  {

    $this->titre = $t;
    $this->date_tache = $d_t;
    $this->heure_tache = $h_t;
    $this->etat = $e;
    $this->id = $id;
  }

  public function add()
  {
    $pdoReturn = $this->connectDatabase();
    $stmt = $pdoReturn->prepare("INSERT INTO task (titre, date_tache, heure_tache, etat) VALUES (:titre, :date_tache, :heure_tache, :etat)");
    $stmt->bindValue('titre', $this->titre);
    $stmt->bindValue('date_tache', $this->date_tache);
    $stmt->bindValue('heure_tache', $this->heure_tache);
    $stmt->bindValue('etat', $this->etat);
    $stmt->execute();
  }

  public function updateState()
  {
    $pdoReturn = $this->connectDatabase();
    $stmt = $pdoReturn->prepare("UPDATE task SET etat = :etat WHERE id = :id");
    $stmt->bindValue('etat', $this->etat);
    $stmt->bindValue('id', $this->id);
    $stmt->execute();
  }

  public function updateTask()
  {
    $pdoReturn = $this->connectDatabase();
    $stmt = $pdoReturn->prepare("UPDATE task SET titre = :titre, date_tache = :date_tache, heure_tache= :heure_tache WHERE id = :id");
    $stmt->bindValue('titre', $this->titre);
    $stmt->bindValue('date_tache', $this->date_tache);
    $stmt->bindValue('heure_tache', $this->heure_tache);
    $stmt->bindValue('id', $this->id);
    $stmt->execute();
  }

  public function remove($id)
  {
    $pdoReturn = $this->connectDatabase();
    $stmt = $pdoReturn->prepare("DELETE FROM task WHERE id = :id");
    $stmt->bindValue('id', $id);
    $stmt->execute();
  }
}
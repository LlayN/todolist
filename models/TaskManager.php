<?php



class TaskManager extends Manager
{



  protected $title;
  protected $date_tache;
  protected $heure_tache;
  protected $etat;

  public function __construct($t = "", $d_t = "", $h_t = "", $e = "")
  {

    $this->title = $t;
    $this->date_tache = $d_t;
    $this->heure_tache = $h_t;
    $this->etat = $e;
  }

  public function add()
  {
    $pdoReturn = $this->connectDatabase();
    $stmt = $pdoReturn->prepare("INSERT INTO task (titre, date_tache, heure_tache, etat) VALUES (:title, :date_tache, :heure_tache, :etat)");
    $stmt->bindValue('title', $this->title);
    $stmt->bindValue('date_tache', $this->date_tache);
    $stmt->bindValue('heure_tache', $this->heure_tache);
    $stmt->bindValue('etat', $this->etat);
    $stmt->execute();
  }

  public function modify()
  {
    $pdoReturn = $this->connectDatabase();
    $stmt = $pdoReturn->prepare("UPDATE task SET etat = :etat");
    $stmt->bindValue('etat', $this->etat);
    $stmt->execute();
  }

  public function remove()
  {
  }
}

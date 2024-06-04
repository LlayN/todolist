<?php


require("config/Manager.php");
require("models/TaskManager.php");
require("models/HomeManager.php");

class Controllers 
{

  private $timestamp;
  private $dateNow;
  private $nextDay;
  private $lastDay;

  public function __construct(){
    $this->timestamp = time();
    $this->dateNow = date('Y-m-d', $this->timestamp);
    $this->nextDay = date('Y-m-d', $this->timestamp + 86400);
    $this->lastDay = date('Y-m-d', $this->timestamp - 86400);
  }
  private function formateDateTime($d = null , $h = null, $formatIso = null){
    if(isset($d)){
      $newDate = date_create_from_format('Y-m-d', $d);
      $dFormat = date_format($newDate, 'd/m/Y');
      return $dFormat; 
    }
    else if(isset($h)){
      $newHeure = date_create_from_format('H:i:s' ,$h);
      $hFormat = date_format($newHeure, 'H\hi');
      return $hFormat;
    }
  }

  private function initializedState($p, $modify = null){
    if($this->dateNow == $p['date_tache']){
      $p['etat'] = 'aujourd\'hui';
    }
    else if(strtotime($this->dateNow) > strtotime($p['date_tache'])){
      $p['etat'] = 'en retard';
    }
    else if(strtotime($this->dateNow) < strtotime($p['date_tache'])){
      $p['etat'] = 'à venir';
    }
    if(isset($modify)){
      $taskManager = new TaskManager(e: $p['etat']);
      $taskManager->modify();
      return;
    }
    return $p;
  }
  public function home()
  {
    $homeManager = new HomeManager();
    $allTask = $homeManager->getAllTask();
    if (count($allTask) == 0) {
      $message = "Vous êtes libre aujourd'hui !";
      $contentNoTask = <<<CONT
      <br>
      <p>--------------------------------</p>
      <p>Vous n'avez pas encore de tâche !</p>
      CONT;
    } else {
      $message = " Tu as du travail aujourd'hui !";
      $contentNoTask = "";
      for ($i = 0; $i < count($allTask); $i++) {
        $allTask[$i]['heure_tache'] = $this->formateDateTime(h: $allTask[$i]['heure_tache']);
        $this->initializedState($allTask[$i], true);        
        switch ($allTask[$i]['date_tache']){
          case $this->dateNow:
            $allTask[$i]['date_tache'] = "Aujourd'hui";
            break;
          case $this->nextDay:
            $allTask[$i]['date_tache'] = "Demain";
            break;
          case $this->lastDay:
            $allTask[$i]['date_tache'] = "Hier";
            break;
          default :
          $allTask[$i]['date_tache'] = $this->formateDateTime(d: $allTask[$i]['date_tache']);
        }
      }
    }
    require("views/home.php");
  }
  public function addTask()
  {
    require("views/addtaskView.php");
    if(!empty($_POST)){
      $newPost = $this->initializedState($_POST);
      $taskManager = new TaskManager($newPost['titre'], $newPost['date_tache'], $newPost['heure_tache'], $newPost['etat']);
      $taskManager->add();
    }
  }
}









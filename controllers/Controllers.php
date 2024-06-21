<?php

session_start();

require __DIR__ . "../../config/Manager.php";
require __DIR__ . "../../models/TaskManager.php";
require __DIR__ . "../../models/HomeManager.php";


class Controllers 
{


  public static $nbToday = [];
  public static $nbComing = [];
  public static $nbDelayed = [];

  
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

  public function home()
{
  ?>
<script type="module">
import * as app from "../public/js/app.js";
fetch("controllers/Controllers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=descendant",
  })
  .then((response) => {
    return response.json();
  })
  .then((data) => {
    app.dataView(data);
  })
  .catch((error) => {
    console.log(error);
  });
</script>
<?php
  

  $homeManager = new HomeManager();

  $taskToday = count($homeManager->taskToday());
  $taskComing = count($homeManager->taskComing());
  $taskDelayed = count($homeManager->taskDelayed());
  $fullTask = $taskToday + $taskComing + $taskDelayed;

  $message = $fullTask > 0 ? "Tu as du travail aujourd'hui !" : "Vous êtes libre aujourd'hui !";
  $contentNoTask = $fullTask == 0 ? "Vous n'avez pas encore de tâche !" : ""; 
  
  require __DIR__ . '../../views/homeView.php';

}

public function addTask()
{   
    require("views/addtaskView.php");  
    if (!empty($_POST)) {
        $newPost = $this->initializedState($_POST);       
        $taskManager = new TaskManager($newPost['titre'], $newPost['date_tache'], $newPost['heure_tache'], $newPost['etat']);      
        $taskManager->add();              
        header('Location: /');
    }
}
private function initializedState($p, $modify = null){
  
  if($this->dateNow == $p['date_tache']){
      $p['etat'] = 'aujourd\'hui';
      Controllers::$nbToday[] = $p['etat'];  
  }
  else if(strtotime($this->dateNow) > strtotime($p['date_tache'])){
      $p['etat'] = 'en retard';
      Controllers::$nbDelayed[] = $p['etat'];  
  }
  else if(strtotime($this->dateNow) < strtotime($p['date_tache'])){
      $p['etat'] = 'à venir';
      Controllers::$nbComing[] = $p['etat'];  
  } 
  if(isset($modify)){
      $taskManager = new TaskManager(e: $p['etat'], id: $p['id']);
      $taskManager->updateState();
      return;
  }
  return $p;  
}



public function modifyTask(){
  require('views/modifytaskView.php');
  if(!empty($_POST)){
    $exist = false;
    foreach($_SESSION['id'] as $id){
      if($id == $_GET['id']){
        $exist = true;
      }};
      if($exist){
        $taskManager = new TaskManager($_POST['titre'], $_POST['date_tache'], $_POST['heure_tache'], id: $_GET['id']);
        $taskManager->updateTask();
        header('Location: /');
      }else{
        echo "Cette tâche n'existe pas";
      }
  }
}
}
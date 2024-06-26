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

  public function __construct()
  {
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
    app.dataView(data, false);
    app.countTask(dataView);
  })
  .catch((error) => {
    console.log(error);
  });
</script>
<?php
require __DIR__ . '../../views/homeView.php';


  }

  public function addTask()
  {
    require ("views/addtaskView.php");
    if (!empty($_POST)) {
      $newPost = $this->initializedState($_POST);
      $newPost['titre'] = htmlspecialchars($newPost['titre']);
      $taskManager = new TaskManager($newPost['titre'], $newPost['date_tache'], $newPost['heure_tache'], $newPost['etat']);
      $taskManager->add();
      header('Location: /');
    }
  }
  private function initializedState($p, $modify = null)
  {

    if ($this->dateNow == $p['date_tache']) {
      $p['etat'] = 'aujourd\'hui';
      Controllers::$nbToday[] = $p['etat'];
    } else if (strtotime($this->dateNow) > strtotime($p['date_tache'])) {
      $p['etat'] = 'en retard';
      Controllers::$nbDelayed[] = $p['etat'];
    } else if (strtotime($this->dateNow) < strtotime($p['date_tache'])) {
      $p['etat'] = 'à venir';
      Controllers::$nbComing[] = $p['etat'];
    }
    if (isset($modify)) {
      $taskManager = new TaskManager(e: $p['etat'], id: $p['id']);
      $taskManager->updateState();
      return;
    }
    return $p;
  }



  public function modifyTask()
  {
    require ('views/modifytaskView.php');

    if (isset($_SESSION[('id')])) {
      if (!empty($_POST)) {
        $_POST['titre'] = htmlspecialchars($_POST['titre']);
        $taskManager = new TaskManager(t: $_POST['titre'], d_t: $_POST['date_tache'], h_t: $_POST['heure_tache'], id: $_SESSION['id']);
        $taskManager->updateTask();
        header('Location: /');
      }
    }
  }
  public function error($th)
  {
    require ('views/errorserverView.php');
  }
}
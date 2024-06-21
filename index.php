<?php

require('./controllers/Controllers.php');


$instControllers = new Controllers();


  if (isset($_GET['page'])) {
    if ($_GET['page'] == "accueil") {
      $instControllers->home();
    } else if ($_GET['page'] == "ajouter_tache") {
      $instControllers->addTask();
    }
    else if ($_GET['page'] == "modifier_tache") {
      $instControllers->modifyTask();
    }

  } else {
    $instControllers->home();
  }


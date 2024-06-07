<?php

/** 
 *@author Hugo Yengo <h.yengo@outlook.fr> 
 */

 /**
 * Inclut les fichiers nécessaires pour le fonctionnement de l'objet Controllers.
 * Ceci inclut les fichiers pour le gestionnaire principal de configuration et les modèles spécifiques
 * pour la gestion des tâches et la page d'accueil.
 */

require __DIR__ . "../../config/Manager.php";
require __DIR__ . "../../models/TaskManager.php";
require __DIR__ . "../../models/HomeManager.php";




/**
 ** Vérifie si un identifiant de tâche est passé via POST et déclenche sa suppression.
 ** Si un identifiant de tâche ('id') est présent dans les données POST, la méthode statique removeTask
 ** de la classe Controllers est appelée pour supprimer la tâche correspondante de la base de données ou du système de gestion.
 ** Cela permet de gérer les actions de suppression des tâches de manière centralisée et sécurisée.
 */
if (isset($_POST['id'])) {
  Controllers::removeTask($_POST['id']); // Appelle la fonction de suppression de tâche
}


class Controllers 
{

  /**
 ** Constructeur pour initialiser les propriétés de la classe avec les dates actuelle, précédente et suivante.
 ** Les propriétés $dateNow, $nextDay, et $lastDay sont définies en fonction du timestamp actuel.
 ** Les tableaux statiques $nbToday, $nbComing, et $nbDelayed sont utilisés pour suivre respectivement le nombre de tâches pour aujourd'hui, les tâches à venir, et les tâches en retard.
 *
 *
 * @property array $nbToday Liste statique des tâches d'aujourd'hui.
 * @property array $nbComing Liste statique des tâches à venir.
 * @property array $nbDelayed Liste statique des tâches en retard.
 * @property int $timestamp Timestamp actuel au moment de la création de l'instance.
 * @property string $dateNow Date actuelle formatée en 'Y-m-d'.
 * @property string $nextDay Date du jour suivant formatée en 'Y-m-d'.
 * @property string $lastDay Date du jour précédent formatée en 'Y-m-d'.
 */

  public static $nbToday = [];
  public static $nbComing = [];
  public static $nbDelayed = [];

  private $timestamp;
  private $dateNow;
  private $nextDay;
  private $lastDay;

  public function __construct(){
    $this->timestamp = time(); // Stocke le timestamp actuel
    $this->dateNow = date('Y-m-d', $this->timestamp); // Configure la date actuelle
    $this->nextDay = date('Y-m-d', $this->timestamp + 86400); // Configure la date du jour suivant
    $this->lastDay = date('Y-m-d', $this->timestamp - 86400); // Configure la date du jour précédent
  }

  /**
   ** Récupére le format de la date Ou de l'heure US pour le modifier en format EU.
   * 
   * @param string|null $d Date au format 'Y-m-d' à convertir.
   * @param string|null $h Heure au format 'H:i:s' à convertir.
   * @return string|null Renvoie la date ou l'heure formatée, ou null si aucun paramètre valide n'est fourni.
   */
  private function formateDateTime($d = null , $h = null){
    if(isset($d)){
      $newDate = date_create_from_format('Y-m-d', $d); //Initialise une nouvelle date et retourne un objet DateTime
      $dFormat = date_format($newDate, 'd/m/Y'); // Remplace le format de la date en EU
      return $dFormat; 
    }
    else if(isset($h)){
      $newHeure = date_create_from_format('H:i:s' ,$h); //Initialise une nouvelle heure et retourne un objet DateTime 
      $hFormat = date_format($newHeure, 'H\hi'); // Remplace le format de l'heure en EU
      return $hFormat;
    }
  }

/**
 ** Initialise l'état d'une tâche basé sur la comparaison entre sa date et la date actuelle.
 ** Met à jour les compteurs statiques pour les tâches classifiées comme 'aujourd'hui', 'en retard', et 'à venir'.
 ** Cette fonction ajuste également l'état de la tâche dans la base de données si le paramètre $modify est fourni et non-null.
 *
 * @param array $p Tableau associatif de la tâche qui doit contenir au moins 'date_tache', et peut modifié 'etat'.
 * @param mixed $modify Si non-null, modifie l'état de la tâche en base de données via l'objet TaskManager.
 * @return array|null Retourne le tableau de la tâche modifié si $modify est null, sinon ne retourne rien.
 */
private function initializedState($p, $modify = null){
  // Comparaison de la date de la tâche avec la date actuelle pour définir son état
  if($this->dateNow == $p['date_tache']){
      $p['etat'] = 'aujourd\'hui';
      Controllers::$nbToday[] = $p['etat'];  // Mise à jour du compteur de tâches d'aujourd'hui
  }
  else if(strtotime($this->dateNow) > strtotime($p['date_tache'])){
      $p['etat'] = 'en retard';
      Controllers::$nbDelayed[] = $p['etat'];  // Mise à jour du compteur de tâches en retard
  }
  else if(strtotime($this->dateNow) < strtotime($p['date_tache'])){
      $p['etat'] = 'à venir';
      Controllers::$nbComing[] = $p['etat'];  // Mise à jour du compteur de tâches à venir
  }

  // Modification de l'état de la tâche en base de données si $modify est défini
  if(isset($modify)){
      $taskManager = new TaskManager(e: $p['etat']);
      $taskManager->modify();
      return;
  }
  return $p;  // Retourne le tableau modifié si aucune modification de la base de données n'est effectuée
}

/**
 ** Gère l'affichage de la page d'accueil, en récupérant et en formatant toutes les tâches stockées.
 ** Cette méthode utilise HomeManager pour récupérer toutes les tâches, puis modifie leur format de date et d'heureet ajuste leur état. 
 ** Elle affiche des messages différents suivant si l'utilisateur a des tâches ou non.
 ** La méthode calcule aussi les totaux de tâches pour aujourd'hui, les tâches à venir et les tâches en retard.
 ** Enfin, elle charge la vue home.php pour afficher les résultats.
 */
public function home()
{
    // Crée une instance de HomeManager pour gérer les tâches
    $homeManager = new HomeManager();
    
    // Récupère toutes les tâches disponibles
    $allTask = $homeManager->getAllTask();
    
    // Prépare les messages pour l'utilisateur basés sur le nombre de tâches
    $message = count($allTask) > 0 ? "Tu as du travail aujourd'hui !" : "Vous êtes libre aujourd'hui !";
    $contentNoTask = count($allTask) == 0 ? "Vous n'avez pas encore de tâche !" : "";
    
    // Formate les tâches récupérées pour l'affichage
    if (count($allTask) > 0) {
        for ($i = 0; $i < count($allTask); $i++) {
            // Formatage de l'heure de chaque tâche
            $allTask[$i]['heure_tache'] = $this->formateDateTime(h: $allTask[$i]['heure_tache']);
            
            // Initialisation de l'état de chaque tâche
            $this->initializedState($allTask[$i], true);
            
            // Ajustement de l'affichage de la date selon qu'il s'agisse d'aujourd'hui, demain ou hier
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
                default:
                    $allTask[$i]['date_tache'] = $this->formateDateTime(d: $allTask[$i]['date_tache']);
            }
        }
    }

    // Compte le nombre de tâches pour aujourd'hui, à venir et en retard
    $taskToday = count(Controllers::$nbToday);  
    $taskComing = count(Controllers::$nbComing);
    $taskDelayed = count(Controllers::$nbDelayed);   
    $fullTask = $taskToday + $taskComing + $taskDelayed;
    
    // Charge la vue correspondante pour afficher toutes ces informations
    require("views/home.php");
}



/**
 ** Ajoute une nouvelle tâche à partir des données soumises via un formulaire POST.
 ** Cette méthode charge d'abord la vue du formulaire d'ajout de tâche. Si des données POST sont détectées,
 ** elle les traite pour initialiser et formater l'état de la tâche, puis crée une nouvelle instance de TaskManager
 ** avec ces données pour ajouter la tâche à la base de données. Après l'ajout, redirige l'utilisateur vers la page d'accueil.
 */
public function addTask()
{
    // Charge la vue qui contient le formulaire d'ajout de tâche
    require("views/addtaskView.php");
    
    // Vérifie si des données POST sont présentes
    if (!empty($_POST)) {
        // Initialise l'état de la tâche et formate les données
        $newPost = $this->initializedState($_POST);
        
        // Crée un gestionnaire de tâches avec les données formatées
        $taskManager = new TaskManager($newPost['titre'], $newPost['date_tache'], $newPost['heure_tache'], $newPost['etat']);
        
        // Ajoute la nouvelle tâche à la base de données
        $taskManager->add();
        
        // Redirige l'utilisateur vers la page d'accueil après l'ajout de la tâche
        header('Location: /');
    }
}


/**
 * Permet de supprimer une tâche de la base de données.
 * Cette méthode crée une instance de TaskManager et utilise sa méthode remove() pour supprimer une tâche spécifiée par son ID.
 *
 * @param string $id L'ID de la tâche à supprimer.
 */
public static function removeTask($id){
  // Crée une nouvelle instance de TaskManager
  $taskManager = new TaskManager();

  // Tente de supprimer la tâche et capture le résultat
  $success = $taskManager->remove($id);

  // Vérifie si la suppression a réussi et agit en conséquence
  if ($success) {
      // La tâche a été supprimée avec succès
      echo "La tâche a été supprimée.";
  } else {
      // La suppression a échoué, gérer l'erreur ici
      echo "Erreur lors de la suppression de la tâche.";
  }
}
  
}









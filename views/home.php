<?php
$user = 'Hugo';
$title = "Accueil";
ob_start();
?>

<h2>Bonjour <?= $user ?>,</h2>
<p><?= $message ?></p>
<h2>Vos tâches</h2>
<h3>Aujourd'hui <?= $taskToday?></h3>
<h3>A venir <?= $taskComing ?></h3>
<h3>En retard <?= $taskDelayed?></h3>
<h3>Voir tout <?= $fullTask ?></h3>


<button>Trier</button>
<button>Filtrer</button>

<?php


echo $contentNoTask;
foreach ($allTask as $task) {
  
  echo "<br>";
  echo "--------------------------------";
  $id = $task['id'];
  echo "<br>";
  echo $task['date_tache'] . ", à " . $task['heure_tache'];
  echo "<br>";
  echo $task['titre']; 
  echo "<br>";
  echo "<button class =\"removeButton\" id = $id >x</button>";
  echo "<br>";
  echo "--------------------------------";
  echo "<br>";
};

?>
<button>
  <a href="?page=ajouter_tache">Ajouter une tâche</a>
</button>

<?php
$content = ob_get_clean();
require "template/template.php";

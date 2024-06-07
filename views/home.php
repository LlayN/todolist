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
<br>
<hr>
<?= $contentNoTask?>
<?php
foreach ($allTask as $task) {
?>
  <div>
    <?php $id = $task['id'];?>
    <h4><?=$task['date_tache']?> , à <?=$task['heure_tache']?></h4>
    <hr>
    <h3><?=$task['titre']?></h3>
    <button class="removeButton" id="<?=$id?>">Supprimer</button>
    <hr>
  </div>
<?php
};
?>




<br>
<div>
<button>
  <a href="?page=ajouter_tache">Ajouter une tâche</a>
</button>

</div>

<?php
$content = ob_get_clean();
require "template/template.php";

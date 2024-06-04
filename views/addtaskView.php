<?php
$title = "Accueil - Ajouter une tâche";
ob_start();
?>

<h2>Ajouter une tâche :</h2>
<form action="" method="post">
  <div>
    <label for="titre">Titre : </label>
    <input type="text" name="titre" id="titre">
  </div>
  <div>
    <label for="date_tache">Date : </label>
    <input type="date" name="date_tache" id="date_tache">
  </div>
  <div>
    <label for="heure_tache">Heure : </label>
    <input type="time" name="heure_tache" id="heure_tache">
  </div>

  <button type="submit">Ajouter la tâche</button>
</form>


<?php
$content = ob_get_clean();
require "template/template.php";

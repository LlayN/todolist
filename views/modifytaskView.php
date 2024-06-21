<?php
$title = "Accueil - Modifier une tâche";
ob_start();

?>

<h2>Modifer ma tâche :</h2>
<form action="" method="post">
  <div>
    <label for="titre">Titre : </label>
    <input type="text" name="titre" id="titre" required>
  </div>
  <br>
  <div>
    <label for="date_tache">Date : </label>
    <input type="date" name="date_tache" id="date_tache" required>
  </div>
  <br>
  <div>
    <label for="heure_tache">Heure : </label>
    <input type="time" name="heure_tache" id="heure_tache" required>
  </div>
  <br>
  <button type="submit">Modifier la tâche</button>
</form>


<?php
$content = ob_get_clean();
require "template/template.php";
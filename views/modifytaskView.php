<?php
$title = " - Modifier une tâche";
ob_start();

?>

<div class="container my-4">
  <h3>Modifier la tâche :</h3>
</div>

<div class="container my-4">
  <form action="" method="post">
    <div class="my-2">
      <label for="titre" class="form-label">Titre : </label>
      <input type="text" name="titre" id="titre" class="form-control" required>
    </div>
    <div class="my-2">
      <label for="date_tache" class="form-label">Date : </label>
      <input type="date" name="date_tache" id="date_tache" class="form-control" required>
    </div>
    <div class="my-2">
      <label for="heure_tache" class="form-label">Heure : </label>
      <input type="time" name="heure_tache" id="heure_tache" class="form-control"
        required>
    </div>
    <div class="d-flex gap-2 my-4">
      <a href="/"><button type="button" class="btn btn-primary ">Retour à
          l'accueil</button></a>
      <button type="submit" class="btn btn-primary">Modifier la tâche</button>
    </div>
  </form>
</div>


<?php
$content = ob_get_clean();
require "template/template.php";
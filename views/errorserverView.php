<?php
$title = " - Erreur " . $th->getCode();
ob_start();
?>

<div class="container my-4 welcome">
  <h3>Une erreur est survenue ...</h3>
  <h5>Code erreur <?= $th->getCode() ?></h5>
</div>
<div
  class="container d-flex justify-content-center align-items-center flex-column div-error">
  <h1>Erreur du système !</h1>
  <img src="../public/assets/svg/giphy.gif" alt="">
  <a href="/"> <button class="btn btn-error" type="button">Retourner à
      l'accueil</button></a>
</div>


<script src="../../public/js/app.js" type="module"></script>

<?php
$content = ob_get_clean();
require "template/template.php";
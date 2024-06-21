<?php
$user = 'Hugo';
$title = "Accueil";
ob_start();
?>

<div class="container my-4 welcome">
  <h2 class="mb-0">Bonjour <?= $user ?>,</h2>
  <p><?= $message ?></p>
</div>

<div class="container my-4">
  <div class="row dashboard">
    <div class="col-6">
      <div class="background bg-today">
        <div class="div-logo-task">
          <div class="icon">
            <img src="../public/assets/svg/time.svg" alt="">
          </div>
        </div>
        <div class="div-nb-task">
          <h5>Aujourd'hui</h5>
          <h4><?= $taskToday ?></h4>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="background bg-coming">
        <div class="div-logo-task">
          <div class="icon">
            <img src="../public/assets/svg/timer-pause-rounded.svg" alt="">
          </div>
        </div>
        <div class="div-nb-task">
          <h5>À venir</h5>
          <h4><?= $taskComing ?></h4>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="background bg-delayed">
        <div class="div-logo-task">
          <div class="icon">
            <img src="../public/assets/svg/timer-fill.svg" alt="">
          </div>
        </div>
        <div class="div-nb-task">
          <h5>En retard</h5>
          <h4><?= $taskDelayed ?></h4>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="background bg-all">
        <div class="div-logo-task">
          <div class="icon">
            <img src="../public/assets/svg/nest-secure-alarm-sharp.svg" alt="">
          </div>
        </div>
        <div class="div-nb-task">
          <h5>Total</h5>
          <h4><?= $fullTask ?></h4>
        </div>
      </div>
    </div>
  </div>
</div>





<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center">
    <h4>Vos tâches</h4>
    <div class="d-flex gap-2">
      <div class="dropdown">
        <button class="btn data-manip d-flex align-items-center gap-1" type="button"
          data-bs-toggle="dropdown" aria-expanded="false" id="sort">
          Trier
        </button>
        <ul class="dropdown-menu p-3 sort">
          <div class="form-check">
            <input class="form-check-input" value="ascendant" type="radio" name="sort"
              id="ascendant">
            <label class="form-check-label" for="ascendant">
              Par état croissant
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" value="descendant" type="radio" name="sort"
              id="descendant" checked>
            <label class="form-check-label" for="descendant">
              Par état décroissant
            </label>
          </div>
        </ul>
      </div>
      <div class="dropdown ">
        <button class="btn data-manip d-flex align-items-center gap-1" type="button"
          data-bs-toggle="dropdown" aria-expanded="false" id="filter">
          Filtrer
        </button>
        <ul class="dropdown-menu p-3 filter">
          <div class="form-check mb-2">
            <input class="form-check-input" value="descendant" type="radio" name="filter"
              id="default" checked>
            <label class="form-check-label" for="default">
              Par défaut
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" value="today" type="radio" name="filter"
              id="today">
            <label class="form-check-label" for="today">
              Aujourd'hui
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" value="coming" type="radio" name="filter"
              id="coming">
            <label class="form-check-label" for="coming">
              À venir
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" value="delayed" type="radio" name="filter"
              id="delayed">
            <label class="form-check-label" for="delayed">
              En retard
            </label>
          </div>
        </ul>
      </div>
    </div>
  </div>
</div>


<main class="container my-4" id="dataView">
  <?= $contentNoTask ?>
</main>

<div class="container my-4">
  <button class="btn" id="addTask">
    <a href="?page=ajouter_tache" class="text-white"><img
        src="../public/assets/svg/plus-svgrepo-com.svg" alt=""></a>
  </button>
</div>

<script src="../../public/js/app.js" type="module"></script>

<?php
$content = ob_get_clean();
require "template/template.php";
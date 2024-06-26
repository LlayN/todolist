<?php



if (isset($_POST['action'])) {
  $homeManager = new HomeManager();
  if ($_POST['action'] == 'getAll') {
    $allTask = $homeManager->getAllTask();
    echo json_encode($allTask);
  } else if ($_POST['action'] == "ascendant") {
    $allTask = $homeManager->sortAsc();
    echo json_encode($allTask);
  } else if ($_POST['action'] == "descendant") {
    $allTask = $homeManager->sortDesc();
    echo json_encode($allTask);
  }
}


class HomeManager extends Manager
{

  public $value;
  public function __construct($v = null)
  {
    $this->value = $v;
  }

  public function getAllTask()
  {
    echo $this->value;
    $pdo = $this->connectDatabase();
    $stmt = $pdo->prepare("SELECT * FROM task ");
    $stmt->execute();
    $allTask = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $allTask;
  }

  public function sortAsc()
  {
    $pdo = $this->connectDatabase();
    $stmt = $pdo->prepare("SELECT * FROM task ORDER BY date_tache ASC");
    $stmt->execute();
    $allTask = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $allTask;
  }

  public function sortDesc()
  {
    $pdo = $this->connectDatabase();
    $stmt = $pdo->prepare("SELECT * FROM task ORDER BY CASE WHEN etat = 'aujourd\'hui' THEN 1 ELSE 0 END DESC, date_tache DESC");
    $stmt->execute();
    $allTask = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $allTask;
  }


}
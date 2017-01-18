<?php 

  // Page entièrement en PHP et qui sera accédée par les requêtes ajax uniquement

  
  // On se connecte à la base de données
  $mysqli = new mysqli('localhost', 'root', 'root', 'task');
  $mysqli->set_charset("utf8");
 
  // On vérifie qu'Ajax nous a envoyé au minimum Action et Méthod, sinon on quitte
  if(!isset($_POST['action']) or !isset($_POST['method']))  {
    die('Pas bons les paramètres');
  }

  // On récupère les données
  $method = $_POST['method'];
  $action = $_POST['action'];
  $data = $_POST['form'];

  // On lance la fonction qui corredpond à la méthode
  // "tasks" + () : tasks();
  $method($action, $data); 


  // Gestion des tâches
  function tasks($action, $data) {
    global $mysqli;

    if ($action == 'add') {
      $name = $data['name'];
      $list = $data['list'];

      // on lance la requête SQL
      // Nota : Lors d'une création le status de la tâche est forcément "non coché"
      $mysqli->query("INSERT INTO tasks (name, status, list) VALUES ('$name', 0, $list)");

      echo mysqli_insert_id($mysqli);
    }

    else if ($action == 'update') {
      $id = $data['id'];
      $name = $data['name'];
      $status = $data['status'];

      $mysqli->query("UPDATE tasks SET name = '$name', status = '$status' WHERE id = $id ");

      echo "tâche mise à jour";
    }

    else if ($action == 'delete') {

      $id = $data['id'];

      $mysqli->query("DElETE FROM tasks WHERE id = $id");

      echo "Tâche supprimée";
    }

    
    else {
      echo "Action non reconnue";
    }
  }

  // Gestion des listes
  function lists($action, $data) {
    global $mysqli;

    if ($action == "add") {
      
      $name = $data['name'];

      $mysqli->query("INSERT INTO lists (name) VALUES ('$name')");

      echo mysqli_insert_id($mysqli);
    }

    // Mise à jour
    else if ($action == 'update') {
      
      $id = $data['id'];
      $name = $data['name'];

      $mysqli->query("UPDATE lists SET name = '$name' WHERE id = $id");

      echo "Titre mis à jour";
    } 

    // Effacer la liste
    else if ($action == 'delete') {
      $id = $data['id'];

      // On supprime la liste
      $mysqli->query("DElETE FROM lists WHERE id = $id");

      // EH ! Une minute !!!!
      // Cette liste a peut-être des tâches qui étaient rattachées.
      // Les pauvres sont désormais orphelines (par ex liées à une liste avec id 3)
      // On ne les verra donc plus sur l'app mais elles seront toujours dans la base
      // On va donc également les supprimer

      $mysqli->query("DElETE FROM tasks WHERE list = $id");

      echo "Liste et tâches associées supprimées";
    }

    else {
      echo "Action non reconnue";
    }

  }

  // On ferme la connexion à la base de données
  $mysqli->close();


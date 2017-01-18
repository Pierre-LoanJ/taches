<?php

// Page d'accueil de l'application

  // ETAPE 1 : PHP
  // On va récupérer les infos de la base SQL (listes, tâches) et générer la page


  // va accueillir listes et tâches
  $datas = array();

  // On se connecte à la base de données 
  $mysqli = new mysqli('localhost', 'root', 'root', 'task');
  $mysqli->set_charset("utf8");

  // On récupére les listes
  $lists = $mysqli->query("SELECT * FROM lists ORDER BY id");

  // On ajoute les 2 listes au tableau $datas
  if($lists){
    while ($row = $lists->fetch_array(MYSQLI_ASSOC)){
      $key = $row['id'];
      $value = $row['name'];

      $datas[$key] = array('name' => $value, 'tasks' => array());
    }
  }

  // On récupère les tâches
  $tasks = $mysqli->query("SELECT * FROM tasks ORDER BY list, id");
  
  // On les range dans la bonne liste dans $datas
  if($tasks){
    while ($row = $tasks->fetch_array(MYSQLI_ASSOC)){
      
      $list = $row['list'];
      $datas[$list]['tasks'][] = $row;
    }
  }

  // on a un tableau bien rangé avec les listes et les tâches
  //var_dump($datas);

  // On a plus besoin de faire de requete SQL, on ferme
  $mysqli->close();

  //var_dump($datas); die();
  // ETAPE 2 : place au HTML !
  // Il est temps de rendre la page

?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<meta charset="utf-8">
	<!--meta http-equip="x-ua-compatible" content="ie-edge"-->
	<title></title>
	<meta name="descritpion" content="">
	<!--meta name="viewport" content="width=device-width,initial-scale=1"-->

	<!--link rel="apple-touch-icon" href="apple-touch-icon.png"-->
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,700" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	
</head>
<body>
	<header class="header">
		<div class="atry">
			<i class="fa fa-space-shuttle"></i>Mes tâches
		</div>
		<div class="header__tasks">
			<i class="fa fa-check-square-o"></i><span class="js-completed-tasks">0</span> / <span class="js-total-tasks">0</span> Tâches accomplies
		</div>
	</header>

	<form action="" class="app-container">
	<?php foreach($datas as $id_list => $list_data): ?>

	<div class="list" data-list-id="<?php echo $id_list; ?>">
		<i class="fa fa-clock-o"></i>
		<input type="text" name="title" placeholder="" value="<?php echo $list_data['name']; ?>">

		<ul class="list__tasks">
		<?php foreach($list_data['tasks'] as $task_data): ?>
			<li class="list__task" data-task-id="<?php echo $task_data['id']; ?>">
			<!--li class="list__task"-->
				<div class="list__task__checkbox">
				<?php
					$class = ($task_data['status'] == 1) ? "is-active": "";
					$status = ($task_data['status' ==1]) ? "true" : "false";
				?>
					<a href="#" class="checkbox js-checkbox <?php  echo $class; ?> ">
						<input type="hidden" name="status" value="<php echo $status; ?>">
					</a>
				</div>
				<div class="list__task__name">
					<input type="text" name="name" placeholder="Votre tâche" value="<?php echo $task_data['name']; ?>">
				</div>
				<div class="list__task__delete">
					<a href="" class="delete--task js-delete-task">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<a href="#" class="list__button js-add-task"><i class="fa fa-plus-square"></i> Ajouter une tâche</a> 

		<a href="#" class="delete--list js-delete-list"><i class="fa fa-times"></i></a> 
		


	</div>
		<?php endforeach; ?>


	<a href="#" class="list__add js-add-list"><span>+</span></a>

	</form>

	<div class="template">
		<div class="list">
			<i class="fa fa-clock-o"></i>
			<input type="text" name="title" placeholder="Titre de la liste">
	
			<ul class="list__tasks">
				<li class="list__task">
					<div class="list__task__checkbox">
						<a href="#" class="checkbox js-checkbox">
							<input type="hidden" name="status" value="false">
						</a>
					</div>
					<div class="list__task__name">
						<input type="text" name="name" placeholder="Votre tâche">
					</div>
					<div class="list__task__delete">
						<a href="" class="delete--task js-delete-task">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</li>
			</ul>
			<a href="#" class="list__button js-add-task"><i class="fa fa-plus-square"></i> Ajouter une tâche</a> 

		<!-- X de la liste -->
		<a href="#" class="delete--list js-delete-list"><i class="fa fa-times"></i></a> 

		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!--script src="https://code.jquery.com/jquery-1.12.0.min.js"></script-->

	<script type="text/javascript"	src="js/script.js"></script>
</body>
</html>
var count = function() {
	//cible
	var allTasks = $('.app-container .checkbox').length;
	var completedTasks = $('.app-container .checkbox.is-active').length;
	//marque

	$(".js-completed-tasks").html(completedTasks);
	$(".js-total-tasks").html(allTasks);
};

$(document).ready(function() {
//toujours commencer par document ready pour atteindre le chargement de la page
	count();
	// debut dev Loan
	$('.atry').click(function(e){
	console.log("hello");
	$(this).toggle();
	});
	/*$('body').on('click', '.header', function(e){
		console.log("hello2");
	});*/


	//fin dev Loan
	$('.app-container').on('click', '.js-checkbox', function(e){
		e.preventDefault(); //évite qd on utilise un href='#' qui ramène toujours en haut de la page
		
		//on est sur la checkbox
		$(this).toggleClass('is-active'); //enleve classe si presente et inversement

		var status = $(this).hasClass('is-active');
		$('input', this).val(status);
		count();
		});

	//gestion du bouton ajouter tâche
	$('.app-container').on('click', '.js-add-task', function(e) {
		e.preventDefault();

		//je cherche le parent (= la liste) pour pas confondre les listes
		var $parent = $(this).parents('.list');
		var html = $('.template .list__tasks').clone().html();
		$('.list__tasks', $parent).append(html).hide().fadeIn(300);

		count();
	});

	//suppression d'une tâche
	$('.app-container').on('click', '.js-delete-task', function(e) {
		e.preventDefault();
		//choper la tâche correspondante
		var $task = $(this).parents('.list__task');
		var taskId = $task.attr('data-task-id');
		//on la retire de la liste après confirmation
		if (window.confirm("Etes-vous sûr de vouloir supprimer ?")) {
			//effet de disparition avec effet volet
			


			$.ajax({
			type: "POST",
			url: "ajax/controller.php",
			data: {
				method: 'tasks',
				action: "delete",
				form: {id: taskId}
			}

			}).done(function(response) {
				//pour mettre à jour le li, on ne peut plus faire $(this) car $ fait référence à ajax ici
				if(newEntry) {
					$task.attr('data-task-id', response);
				}
			});
			$task.fadeOut(300, function() {
				$task.remove();
				count();
			});
		}


	});

	//Enregistrement d'une tâche quand on sort du champ input
	$('.app-container').on('change', '.list__task input[name=name]', addOrUpdate);
	$('.app-container').on('click', '.js-checkbox', addOrUpdate);



		function addOrUpdate() {
		//en creation d'une nouvelle tâche
		//on a besoin de l'état de la tâche et son label (=name)
		var $task = $(this).parents('.list__task');
		var name = $task.find('input[name=name]').val();
		var action = "";
		var form = {};
		
		var taskId = $task.attr('data-task-id');
		var newEntry = (typeof(taskId) === 'undefined');

		if(newEntry) {
			action = "add";
			var list = $(this).parents('.list').attr('data-list-id');
			var $task = $(this).parents('.list__task');
			form = {
					name: name,
					list: list
				};
		}
		else {
			var status = $task.find('input[name=status]').val(); // on cherche à partir du parent
			status = status == "true" ? 1: 0;
			action = "update";
			form = {
					name: name,
					status: status,
					id: taskId
				};
		}
		
		$.ajax({
			type: "POST",
			url: "ajax/controller.php",
			data: {
				method: 'tasks',
				action: action,
				form: form
			}

		}).done(function(response) {
				//pour mettre à jour le li, on ne peut plus faire $(this) car $ fait référence à ajax ici
				if(newEntry) {
					$task.attr('data-task-id', response);
				}
			});
	}


	//LIST

	//ajout d'une liste
	$('.app-container').on('click', '.js-add-list', function(e) {
		e.preventDefault();
		
		var html = $('.template').clone().html();
		$(this).before(html);

		count();

		$.ajax({
			type: "POST",
			url: "ajax/controller.php",
			data: {
				method: 'lists',
				action: "add",
				form: {}
			}
		}).done(function(response) {
			//pour mettre à jour le li, on ne peut plus faire $(this) car $ fait référence à ajax ici
				$('.app-container .list:last-of-type').attr('data-list-id', response);
		});
	});

	//update de liste
	$('.app-container').on('change', '.list input[name=title]', function(e) {
		e.preventDefault();
		
		var $list = $(this).parents('.list');
		var listId = $list.attr('data-list-id');
		var name = $(this).val();
		count();

		$.ajax({
			type: "POST",
			url: "ajax/controller.php",
			data: {
				method: 'lists',
				action: "update",
				form: {
					id: listId,
					name: name
				}
			}
		});
	});


	//suppression d'une liste avec confirmation
	$('.app-container').on('click', '.js-delete-list', function(e) {
		e.preventDefault();
		//choper la tâche correspondante
		//var $parent = $(this).parents('.list');
		//on la retire de la liste après confirmation
		if (window.confirm("Etes-vous sûr de vouloir supprimer la liste?")) {
			//effet de disparition avec effet volet
			var $list = $(this).parents('.list');
			var listId = $list.attr('data-list-id');
			
			$.ajax({
			type: "POST",
			url: "ajax/controller.php",
			data: {
				method: 'lists',
				action: "delete",
				form: {
					id: listId,
				}
			}
		});
			$list.fadeOut(300, function() {
				$list.remove();
				count();
			});
		}
	});

});
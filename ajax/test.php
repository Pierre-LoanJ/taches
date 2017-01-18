<?php

	//var_dump($_POST['formData']); // pratique pour débugguer le retour du php
	$num1 = ($_POST['formData'][0]['value']);
	$num2 = ($_POST['formData'][1]['value']);

	$operation = $_POST['operation'];

	if($operation == "addition") {
		echo $num1 + $num2;
	}
	if($operation == "soustraction") {
		echo $num1 - $num2;	
	}
	//die();

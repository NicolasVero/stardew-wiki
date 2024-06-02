<?php

	header('Content-Type: application/json');
	require 'functions/utility_functions.php';

	ob_start();
	extract($_POST);

	$response = [
		"success" => false,
		"message" => "Error while sending the email. Please try again"
	];
	
	if($_SERVER['REQUEST_METHOD'] != "POST") {
		echo json_encode($response);
		exit;
	}

	$captcha_json = sanitize_json_with_version("captcha_questions");
	$captcha_answer = $captcha_json[$_POST["captcha_id"]]["answer"];

	if(($captcha_answer == $_POST["captcha_answer"]) == false) {
		$response["message"] = "Incorrect captcha answer. Please try again";
		echo json_encode($response);
		exit;
	}
	
	$to         = "jsp quelle adresse";
	$headers    = "De: $mail";
	$coords     = "$username : $mail";
	$date 	    = date("d/m/Y");
	$heure 	    = date("H:i");
	$email_body = "Message de $coords \nLe : $date à $heure \n $message";

	// if (mail($to, $topic, $email_body, $headers)) {
	if(true) {
		$response["success"] = true;
		$response["message"] = "Your mail has been delivered successfully";
	}
	
	exit;
	echo json_encode($response);
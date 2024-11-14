<?php

header("Content-Type: application/json");
require "functions/utility_functions.php";

ob_start();
extract($_POST);
$response = [
	"success" => false,
	"message" => "Error while sending the email. Please try again"
];

if($_SERVER["REQUEST_METHOD"] != "POST") {
	echo json_encode($response);
	exit;
}

$user_details = array(
	"feedback_type" => $topic,
	"email_adress" => $mail,
	"username" => $username,
	"message" => $message,
);

if(send_feedback_mail($user_details)) {
	$response["success"] = true;
	$response["message"] = "Your mail has been delivered successfully";
}

echo json_encode($response);
exit;
<?php

require "functions/data_functions.php";
require "functions/display_functions.php";
require "functions/utility_functions.php";
require "security_check.php";

$response = array();
$response["file_content"] = $_FILES;

try {
	$name_check = explode("_", $_FILES["save-upload"]["name"]);	
    $external_error = ($name_check[0] == "Error") ? explode(".", ($name_check[1]))[0] : null;

    if(is_file_secure($_FILES["save-upload"], $external_error)) {
        $responses = load_save($_FILES["save-upload"]["tmp_name"]);

        $response["players"] = $responses["players"];
        $response["html"] = $responses["html"];
        $response["code"] = $responses["code"];
    }
} catch (Exception $exception) {
	$page["sur_header"] = display_sur_header(false, true);
	$page["error_message"] = display_error_page($exception);
    $response["html"]  = $page;
    $response["code"]  = "failed";
    $response["error"] = $exception->getMessage();
}

echo json_encode($response);
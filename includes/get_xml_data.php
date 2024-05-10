<?php

require 'functions/data_functions.php';
require 'functions/display_functions.php';
require 'functions/utility_functions.php';
require 'security_check.php';


$response = array();

if($_FILES['save-upload']['error'] === UPLOAD_ERR_OK && is_file_secure($_FILES['save-upload'])) {


    $uploadedFile = $_FILES['save-upload']['tmp_name'];
    $data = simplexml_load_file($uploadedFile);

    $players_data = get_all_players_datas($data);
    $players = get_all_players($data);
   
    $response['html'] = display_page($players_data[0], $players);
    $response['code'] = "success";
} else {
    $response['html'] = "Something went wrong";
    $response['code'] = "failed";
}

echo json_encode($response);
<?php

require 'functions/data_functions.php';
require 'functions/display_functions.php';
require 'functions/utility_functions.php';
require 'security_check.php';

//&TEMP
// $data = simplexml_load_file('./data/romain-1.5.6');

// // $general_data = get_general_datas($data);
// $players_data = get_all_players_datas($data);
// $players = get_all_players($data);
//&TEMP


$response = array();
$response['file_content'] = $_FILES;

try {
    if(is_file_secure($_FILES['save-upload'])) {
        $uploadedFile = $_FILES['save-upload']['tmp_name'];
        $data = simplexml_load_file($uploadedFile);

        $players_data = get_all_players_datas($data);
        $players = get_all_players($data);

        for($player_count = 0; $player_count < count($players); $player_count++) {
			$GLOBALS['player_id'] = $player_count;
            $pages['player_' . $player_count] = "
                <div class='player_container player_{$player_count}_container'>" . 
                    display_page($players_data[$player_count], $players, $player_count) . "
                </div>
            ";
        }

        $response['global_variables'] = $GLOBALS;
        $response['html'] = $pages;
        $response['code'] = "success";
    }
} catch (Exception $exception) {
    $response['html']  = display_error_page($exception);
    $response['code']  = "failed";
    $response['error'] = $exception->getMessage();
}

echo json_encode($response);
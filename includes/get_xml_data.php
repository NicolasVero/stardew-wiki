<?php

require 'functions/data_functions.php';
require 'functions/display_functions.php';
require 'functions/utility_functions.php';
require 'security_check.php';


$response = array();
$response['file_content'] = $_FILES;

try {

	$name_check = explode("_", $_FILES['save-upload']['name']);
	if($name_check[0] == "Error") {
		$manual_error = true;
		$manual_error_type = explode(".", ($name_check[1]))[0];
	}
	else {
		$manual_error = false;
		$manual_error_type = '';
	}

    if(is_file_secure($_FILES['save-upload'], $manual_error, $manual_error_type)) {
        $uploadedFile = $_FILES['save-upload']['tmp_name'];
        $data = simplexml_load_file($uploadedFile);

        load_all_items();
        load_wiki_links();
        $GLOBALS['untreated_all_players_data'] = $data;

        $players_data = get_all_players_datas();
        $players = get_all_players();

        for($player_count = 0; $player_count < count($players); $player_count++) {
			$GLOBALS['player_id'] = $player_count;
            $pages['player_' . $player_count] = "
                <div class='player_container player_{$player_count}_container'>" . 
                    display_page() . "
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
<?php

require 'functions/data_functions.php';
require 'functions/display_functions.php';
require 'functions/utility_functions.php';
require 'security_check.php';


if($_FILES['save-upload']['error'] === UPLOAD_ERR_OK && is_file_secure($_FILES['save-upload'])) {

    $uploadedFile = $_FILES['save-upload']['tmp_name'];
    $data = simplexml_load_file($uploadedFile);
    
    // $data = simplexml_load_file('./data/saves/gameInfos_better');
    // $data = simplexml_load_file('./data/saves/nico');
    // $data = simplexml_load_file('./data/saves/romain-1.5.6');
    
    // $general_data = get_general_datas($data);
    $players_data = get_all_players_datas($data);
    $players = get_all_players($data);
    
    echo display_page($players_data[0], $players);
} 
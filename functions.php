<?php 

function log($element) {
    echo "<pre>" . print_r($element, true) . "</pre>";
} 



function get_all_players($data): array {
    $players = array();

    array_push($players, get_aggregated_data($data->player));

    foreach($data->farmhands as $side_player) {
        array_push($players, get_aggregated_data($side_player->Farmer));     
    }

    return $players;
}


function get_aggregated_data($data) {
    return $data->name;
}

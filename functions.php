<?php 

function log_($element) {
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


function get_aggregated_data($data):array {
    
    // log_($data);

    return array(
        'favorite_thing' => $data->favoriteThing,
        'farming_level' => $data->farmingLevel
    );

    // favoriteThing
    // farmingLevel	:	1
    // miningLevel	:	3
    // combatLevel	:	2
    // foragingLevel	:	2
    // fishingLevel	:	4
    // luckLevel	:	0
    // maxStamina	:	270
    // maxItems	:	24
    // maxHealth	:	110
    // gender	:	Male
    // friendshipData
}

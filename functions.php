<?php 

function log_($element) {
    echo "<pre>" . print_r($element, true) . "</pre>";
} 



function get_all_players(object $data): array {
    $players = array();

    array_push($players, get_aggregated_data($data->player));

    foreach($data->farmhands as $side_player) {
        array_push($players, get_aggregated_data($side_player->Farmer));     
    }

    return $players;
}


function get_aggregated_data(object $data):array {
    
    // log_($data);

    return array(
        'favorite_thing' => (string) $data->favoriteThing,
        'farming_level'  => (int) $data->farmingLevel,
        'mining_level'   => (int) $data->miningLevel,
        'combat_level'   => (int) $data->combatLevel,
        'foraging_level' => (int) $data->foragingLevel,
        'fishing_level'  => (int) $data->fishingLevel,
        'luck_level'     => (int) $data->luckLevel,
        'max_items'      => (int) $data->maxItems,
        'max_health'     => (int) $data->maxHealth,
        'max_stamina'    => (int) $data->maxStamina,
        'gender'         => (string) $data->gender,
        'friendship'     => get_friendship_data($data->friendshipData),
        'monsters_kill'  => get_monsters_kill_data($data->stats)
    );
}


function get_monsters_kill_data(object $data): array { 
    $monsters = [];
    
    foreach ($data->specificMonstersKilled->item as $item) {
        $monsters[(string) $item->key->string] = (int) $item->value->int;
    }
    
    return $monsters;
}


function get_friendship_data(object $data):array { 
    
    $friends = array();

    foreach($data->item as $item) {
        $friends[(string) $item->key->string] = array(

            'points'     => (int) $item->value->Friendship->Points,
            'status'     => (int) $item->value->Friendship->Status,
            'week_gifts' => (int) $item->value->Friendship->GiftsThisWeek
        );
    }

    return $friends; 
}
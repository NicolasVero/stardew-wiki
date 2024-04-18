<?php


function get_all_players_datas(object $data): array {
    $players = array();

    array_push($players, get_aggregated_data($data->player));

    foreach($data->farmhands as $side_player) {
        array_push($players, get_aggregated_data($side_player->Farmer));     
    }

    return $players;
}


function get_aggregated_data(object $data):array {
    
    return array(
        'name'           => (string) $data->name,
        'gender'         => (string) $data->gender,
        'favorite_thing' => (string) $data->favoriteThing,
        'levels'         => array(
            'farming_level'  => (int) $data->farmingLevel,
            'mining_level'   => (int) $data->miningLevel,
            'combat_level'   => (int) $data->combatLevel,
            'foraging_level' => (int) $data->foragingLevel,
            'fishing_level'  => (int) $data->fishingLevel,
        ),
        'luck_level'     => (int) $data->luckLevel,
        'max_items'      => (int) $data->maxItems,
        'max_health'     => (int) $data->maxHealth,
        'max_stamina'    => (int) $data->maxStamina,
        'money'          => (int) $data->money,
        'total_money'    => (int) $data->totalMoneyEarned,
        'game_duration'  => get_game_duration((int) $data->millisecondsPlayed),
        'friendship'     => get_friendship_data($data->friendshipData),
        'monsters_kill'  => get_monsters_kill_data($data->stats),
        'quest_log'      => get_quest_log($data->questLog)
    );
}


function get_game_duration(int $duration):string {

    $totalSeconds = intdiv($duration, 1000);
    $seconds      = $totalSeconds % 60;
    $totalMinutes = intdiv($totalSeconds, 60);
    $minutes      = $totalMinutes % 60;
    $hours        = intdiv($totalMinutes, 60);

    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
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
            'status'     => (string) $item->value->Friendship->Status,
            'week_gifts' => (int) $item->value->Friendship->GiftsThisWeek
        );
    }

    return $friends; 
}


function get_quest_log(object $data):array {
    $quests = array();

    foreach($data->Quest as $item) {
        $quests[] = array(

            'objective'   => (string) $item->_currentObjective,
            'description' => (string) $item->_questDescription,
            'title'       => (string) $item->_questTitle,
            'money'       => (int) $item->moneyReward
        );
    }

    return $quests;
}


function get_general_datas(object $data):array {
    
    return array(
        'farm_name' => (string) $data->player->farmName,   
        'day'       => (int) $data->dayOfMonth,
        'season'    => (string) $data->currentSeason,
        'year'      => (int) $data->year
    );
}
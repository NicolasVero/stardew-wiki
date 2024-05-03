<?php

function get_all_players_datas(object $data):array {
    $players = array();

    array_push($players, get_aggregated_data($data->player));

    foreach($data->farmhands as $side_player) {
        // array_push($players, get_aggregated_data($side_player->Farmer));     
    }

    complete_general_data($players, $data);

    return $players;
}


function get_all_players(object $data):array {
    $players_names = array();

    array_push($players_names, (string) $data->player->name);

    foreach($data->farmhands as $side_player) {
        array_push($players_names, (string) $side_player->Farmer->name);     
    }

    return $players_names;
}


function get_aggregated_data(object $data):array {
    
    return array(
        'general' => array(
            'name'           => (string) $data->name,
            'gender'         => (string) $data->gender,
            'farm_name'      => (string) $data->farmName,
            'favorite_thing' => (string) $data->favoriteThing,
            'animal_type'    => (string) $data->whichPetType,
            'date'           => get_formatted_date($data),
            'game_duration'  => get_game_duration((int) $data->millisecondsPlayed),
            'mine_level'     => (int) $data->deepestMineLevel,
            'max_items'      => (int) $data->maxItems,
            'max_health'     => (int) $data->maxHealth,
            'max_stamina'    => (int) $data->maxStamina,
            'golds'          => (int) $data->money,
            'total_golds'    => (int) $data->totalMoneyEarned,
            'qi_gems'        => (int) $data->qiGems,
            'casino_coins'   => (int) $data->clubCoins
        ),
        'levels' => array(
            'farming_level'  => (int) $data->farmingLevel,
            'mining_level'   => (int) $data->miningLevel,
            'combat_level'   => (int) $data->combatLevel,
            'foraging_level' => (int) $data->foragingLevel,
            'fishing_level'  => (int) $data->fishingLevel,
        ),
        'has_element' => array(
            'understand_dwarf'     => has_element($data->canUnderstandDwarves),
            'has_rusty_key'        => has_element($data->hasRustyKey),
            'has_club_card'        => has_element($data->hasClubCard),
            'has_special_charm'    => has_element($data->hasSpecialCharm),
            'has_skull_key'        => has_element($data->hasSkullKey),
            'has_magnifying_glass' => has_element($data->hasMagnifyingGlass),
            'has_dark_talisman'    => has_element($data->hasDarkTalisman),
            'has_magic_ink'        => has_element($data->hasMagicInk),
            'has_bear_knowledge'   => (int) in_array(2120303, (array) $data->eventsSeen->int),
            'has_onion_mastery'    => (int) in_array(3910979, (array) $data->eventsSeen->int),
            'has_town_key'         => has_element($data->HasTownKey),
        ),
        'fish_caught'     => get_item_list($data->fishCaught, 'fish'),
        'artifacts_found' => get_item_list($data->archaeologyFound, 'artifacts'),
        'minerals_found'  => get_item_list($data->mineralsFound, 'minerals'),
		//! Display les cookingRecipes que tu possèdes, pas que tu as déjà cuisiné, peut-être le changer?
        'cooking_recipe'  => get_item_list($data->cookingRecipes, 'recipes'),
        'shipped_items'   => get_item_list($data->basicShipped, 'shipped_items'),
        'achievements'    => get_achievement($data->achievements),
        'skills'          => get_skills_data((array) $data->professions->int),
        'friendship'      => get_friendship_data($data->friendshipData),
        'enemies_killed'   => get_enemies_killed_data($data->stats),
        'quest_log'       => get_quest_log($data->questLog)
    );
}

function has_element(object $element):int {
    return !empty((array) $element);
}

function get_achievement(object $achievements):array {

    $datas = array();
    
    foreach($achievements->int as $achievement) 
        $datas[] = find_reference_in_json((string)$achievement, 'achievements');
    
    return $datas;
}


function get_item_list(object $items, string $filename):array {

    $datas = array();

    foreach($items->item as $item) {
		//! Anciennes versions ($item->key->int) sauf cookingRecipes ($item->key-string)
        $item_id = str_replace('(O)', '', (string) $item->key->string);

        if(ctype_digit($item_id)) {
            $reference = find_reference_in_json($item_id, $filename);
            
            if(!empty($reference))
                $datas[] = $reference;
        }

        if($filename == 'recipes')
            $datas[] = $item_id;
    }
    
    return $datas;
}


function find_reference_in_json(int $id, string $file) {
    //! Changer file_get_contents en curl -> problème de pare-feu en hébergé
    $json_file = json_decode(file_get_contents(get_json_folder() . $file . '.json'), true);

    return isset($json_file[$id]) ? $json_file[$id] : null;
}


function get_skills_data(array $skills):array {
    $json_skills = json_decode(file_get_contents(get_json_folder() . 'skills.json'), true);
    $skills_datas = array();

    foreach($json_skills as $key => $skill) {
        if(in_array($key, $skills))
            $skills_datas[] = $json_skills[$key];
    }

    return $skills_datas;
}

function get_game_duration(int $duration):string {

    $totalSeconds = intdiv($duration, 1000);
    $seconds      = $totalSeconds % 60;
    $totalMinutes = intdiv($totalSeconds, 60);
    $minutes      = $totalMinutes % 60;
    $hours        = intdiv($totalMinutes, 60);

    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

function get_enemies_killed_data(object $data):array { 
    $enemies = [];
    
    foreach ($data->specificMonstersKilled->item as $item) {
        $enemies[(string) $item->key->string] = (int) $item->value->int;
    }
    
    return $enemies;
}


function get_friendship_data(object $data):array { 
    //! Faire json de tous les villageois car parties moddées en rajoute des inconnus
    $friends = array();

    foreach($data->item as $item) {
        $friends[(string) $item->key->string] = array(

            'points'       => (int) $item->value->Friendship->Points,
            'friend_level' => (int) floor(($item->value->Friendship->Points) / 250),
            'status'       => (string) $item->value->Friendship->Status,
            'week_gifts'   => (int) $item->value->Friendship->GiftsThisWeek
        );
    }

    uasort($friends, function ($a, $b) {
        return $b['points'] - $a['points'];
    });

    return $friends; 
}


function get_quest_log(object $data):array {
    $quests = array();

    foreach($data->Quest as $item) {
        $quests[] = array(

            'objective'   => (string) $item->_currentObjective,
            'description' => (string) $item->_questDescription,
            'title'       => (string) $item->_questTitle,
            'gold'       => (int) $item->moneyReward
        );
    }

    return $quests;
}


function get_formatted_date(object $data):string {

    $day    = $data->dayOfMonthForSaveGame;
    $season = array('spring', 'summer', 'fall', 'winter')[$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    return "Day $day of $season, Year $year";
}



function complete_general_data(array &$players, object $data) {
    foreach($players as &$player) {
        $player['general']['golden_walnuts'] = (int) $data->goldenWalnuts;
    }
}
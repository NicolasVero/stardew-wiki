<?php

require 'search_data_functions.php';


function get_all_players_datas(object $data):array {
    $players = array();

    array_push($players, get_aggregated_data($data->player, $data));

    foreach($data->farmhands as $side_player) {
        // array_push($players, get_aggregated_data($side_player->Farmer, $data));
    }

    return $players;
}


function get_all_players(object $data):array {
    $players_names = array();

    array_push($players_names, (string) $data->player->name);

    foreach($data->farmhands as $side_player) {
        if(!empty($side_player->Farmer->name))
            array_push($players_names, (string) $side_player->Farmer->name);     
    }

    return $players_names;
}


function get_aggregated_data(object $data, object $general_data):array {    

    $game_version_score = (int) get_game_version_score((string) $general_data->gameVersion);
    
    return array(
        'general' => array(
            'game_version'       => (string) $general_data->gameVersion,
            'game_version_score' => $game_version_score,
            'name'               => (string) $data->name,
            'gender'             => get_gender(array($data->gender, $data->isMale)),
            'farm_name'          => (string) $data->farmName,
            'farmer_level'       => get_farmer_level($data),
            'favorite_thing'     => (string) $data->favoriteThing,
            'animal_type'        => (string) $data->whichPetType,
            'date'               => get_formatted_date($data),
            'game_duration'      => get_game_duration((int) $data->millisecondsPlayed),
            'mine_level'         => (int) $data->deepestMineLevel,
            'max_items'          => (int) $data->maxItems,
            'max_health'         => (int) $data->maxHealth,
            'max_stamina'        => (int) $data->maxStamina,
            'golds'              => (int) $data->money,
            'total_golds'        => (int) $data->totalMoneyEarned,
            'golden_walnuts'     => (int) $general_data->goldenWalnuts,
            'qi_gems'            => (int) $data->qiGems,
            'casino_coins'       => (int) $data->clubCoins
        ),
        'levels' => array(
            'farming_level'      => (int) $data->farmingLevel,
            'mining_level'       => (int) $data->miningLevel,
            'combat_level'       => (int) $data->combatLevel,
            'foraging_level'     => (int) $data->foragingLevel,
            'fishing_level'      => (int) $data->fishingLevel,
        ),
        'has_element' => array(
            'forest_magic'                  => get_unlockables("forest_magic", $data, $game_version_score),
            'dwarvish_translation_guide'    => get_unlockables("dwarvish_translation_guide", $data, $game_version_score),
            'rusty_key'        				=> get_unlockables("rusty_key", $data, $game_version_score),
            'club_card'        				=> get_unlockables("club_card", $data, $game_version_score),
            'special_charm'    				=> get_unlockables("special_charm", $data, $game_version_score),
            'skull_key'        				=> get_unlockables("skull_key", $data, $game_version_score),
            'magnifying_glass' 				=> get_unlockables("magnifying_glass", $data, $game_version_score),
            'dark_talisman'    				=> get_unlockables("dark_talisman", $data, $game_version_score),
            'magic_ink'        				=> get_unlockables("magic_ink", $data, $game_version_score),
            'bears_knowledge'   			=> (int) in_array(2120303, (array) $data->eventsSeen->int),
            'spring_onion_mastery'  		=> (int) in_array(3910979, (array) $data->eventsSeen->int),
            'town_key'         				=> get_unlockables("town_key", $data, $game_version_score),
        ),
        'books'           => get_item_list_string($data->stats->Values, "books", $game_version_score),
        'masteries'       => get_item_list_string($data->stats->Values, "masteries", $game_version_score),
        'fish_caught'     => get_fish_caught_data($data->fishCaught, $game_version_score),
        'artifacts_found' => get_artifacts($data->archaeologyFound, $general_data, $game_version_score),
        'minerals_found'  => get_minerals($data->mineralsFound, $general_data, $game_version_score),
        'cooking_recipes'  => get_cooking_recipes($data->cookingRecipes, $data->recipesCooked, $game_version_score),
        'shipped_items'   => get_item_list($data->basicShipped, 'shipped_items', $game_version_score),
        'achievements'    => get_achievement($data->achievements),
        'skills'          => get_skills_data((array) $data->professions->int),
        'friendship'      => get_friendship_data($data->friendshipData),
        'enemies_killed'  => get_enemies_killed_data($data->stats),
        'quest_log'       => get_quest_log($data->questLog)
    );
}

/*
	### Get unlockables < 1.6 ###
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
*/
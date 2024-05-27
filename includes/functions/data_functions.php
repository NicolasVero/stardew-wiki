<?php

require 'search_data_functions.php';


function get_all_players_datas():array {
    $players_data = array();
    $data = $GLOBALS['untreated_all_players_data'];

    array_push($players_data, get_aggregated_data($data->player));
	$GLOBALS['host_data'] = $players_data[0];

	if(empty($data->farmhands)) {
        $GLOBALS['all_players_data'] = $players_data;
    	return $players_data;
    }

    foreach($data->farmhands as $side_player) {
        array_push($players_data, get_aggregated_data($side_player->Farmer));
    }

	$GLOBALS['all_players_data'] = $players_data;

    return $players_data;
}


function get_all_players():array {
    $players_names = array();
    $data = $GLOBALS['untreated_all_players_data'];

    array_push($players_names, (string) $data->player->name);

    foreach($data->farmhands as $side_player) {
        if(!empty($side_player->Farmer->name))
            array_push($players_names, (string) $side_player->Farmer->name);
    }
	$GLOBALS['players_names'] = $players_names;

    return $players_names;
}


function get_aggregated_data(object $data):array {

    $general_data = $GLOBALS['untreated_all_players_data'];
    $game_version_score = (int) get_game_version_score((string) $general_data->gameVersion);

	$GLOBALS['untreated_player_data'] = $data;
	$GLOBALS['game_version'] = $general_data->gameVersion;
	$GLOBALS['game_version_score'] = $game_version_score;
    
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
        'has_element'     => get_unlockables_list($data),
        'crafting_recipes'          => get_crafting_recipes($data->craftingRecipes),
        'books'           => get_item_list_string($data->stats->Values, "books"),
        'masteries'       => get_item_list_string($data->stats->Values, "masteries"),
        'fish_caught'     => get_fish_caught($data->fishCaught),
        'artifacts_found' => get_artifacts($data->archaeologyFound, $general_data),
        'minerals_found'  => get_minerals($data->mineralsFound, $general_data),
        'cooking_recipes' => get_cooking_recipes($data->cookingRecipes, $data->recipesCooked),
        'shipped_items'   => get_shipped_items($data->basicShipped, 'shipped_items'),
        'achievements'    => get_achievement($data->achievements),
        'skills'          => get_skills_data((array) $data->professions->int),
        'friendship'      => get_friendship_data($data->friendshipData),
        'enemies_killed'  => get_enemies_killed_data($data->stats),
        'quest_log'       => get_quest_log($data->questLog)
    );
}
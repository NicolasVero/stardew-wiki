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
        array_push($players_names, (string) $side_player->Farmer->name);     
    }

    return $players_names;
}


function get_aggregated_data(object $data, object $general_data):array {     
    return array(
        'general' => array(
            'game_version'   => (string) $general_data->gameVersion,
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
            'golden_walnuts' => (int) $general_data->goldenWalnuts,
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
            'forest_magic'                  => has_element("canReadJunimoText", $data),
            'dwarvish_translation_guide'    => has_element("HasDwarvishTranslationGuide", $data),
            'rusty_key'        				=> has_element("HasRustyKey", $data),
            'club_card'        				=> has_element("HasClubCard", $data),
            'special_charm'    				=> has_element("HasSpecialCharm", $data),
            'skull_key'        				=> has_element("HasSkullKey", $data),
            'magnifying_glass' 				=> has_element("HasMagnifyingGlass", $data),
            'dark_talisman'    				=> has_element("HasDarkTalisman", $data),
            'magic_ink'        				=> has_element("HasPickedUpMagicInk", $data),
            'bears_knowledge'   			=> (int) in_array(2120303, (array) $data->eventsSeen->int),
            'spring_onion_mastery'  		=> (int) in_array(3910979, (array) $data->eventsSeen->int),
            'town_key'         				=> has_element("HasTownKey", $data),
        ),
        // 'books'           => get_books(),
        'fish_caught'     => get_fish_caught_data($data->fishCaught),
        'artifacts_found' => get_artifacts($data->archaeologyFound, $general_data),
        'minerals_found'  => get_minerals($data->mineralsFound, $general_data),
        'cooking_recipe'  => get_cooking_recipes($data->cookingRecipes, $data->recipesCooked),
        'shipped_items'   => get_item_list($data->basicShipped, 'shipped_items'),
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
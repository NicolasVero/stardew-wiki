<?php

require 'search_data_functions.php';


function load_save($save_file, $use_ajax = true):mixed {
    $uploadedFile = $save_file;
    $data = simplexml_load_file($uploadedFile);

    load_all_json();
    $GLOBALS['untreated_all_players_data'] = $data;

    $players_data = get_all_players_datas();
    $players = get_all_players();

    $pages['sur_header'] = display_sur_header(false, false);
    for($player_count = 0; $player_count < count($players); $player_count++) {
        $GLOBALS['player_id'] = $player_count;
        $pages['player_' . $player_count] = "
            <div class='player_container player_{$player_count}_container'>" . 
                display_page() . "
            </div>
        ";
    }

    if($use_ajax) {
        return array(
            "players" => $GLOBALS['players_names'],
            "html" => $pages,
            "code" => "success"
        );
    } else {   
        foreach($pages as $page)
            echo $page;
    }
}

function get_all_players_datas():array {
    $players_data = array();
    $data = $GLOBALS['untreated_all_players_data'];

    array_push($players_data, get_aggregated_data($data->player));
	$GLOBALS['host_player_data'] = $players_data[0];

	if($GLOBALS['game_version_score'] < get_game_version_score("1.6.0"))  {
		foreach($data->locations->GameLocation as $game_location) {
			if(isset($game_location->buildings)) {
				foreach($game_location->buildings->Building as $building) {
					if(isset($building->indoors->farmhand)) {
						$farmhand_info = $building->indoors->farmhand;
						array_push($players_data, get_aggregated_data($farmhand_info));
					}
				}
				break;
			}
		}
	} else {
		if(empty($data->farmhands)) {
			$GLOBALS['all_players_data'] = $players_data;
			return $players_data;
		}
	
		foreach($data->farmhands->Farmer as $side_player)
			array_push($players_data, get_aggregated_data($side_player));
	
	}

	$GLOBALS['all_players_data'] = $players_data;

    return $players_data;
}


function get_all_players():array {
    $players_names = array();
    $data = $GLOBALS['untreated_all_players_data'];

    array_push($players_names, (string) $data->player->name);
	
	if($GLOBALS['game_version_score'] < get_game_version_score("1.6.0"))  {
		foreach($data->locations->GameLocation[0]->buildings->Building as $building) {
			if(isset($building->indoors->farmhand)) {
				$farmhand_name = (string) $building->indoors->farmhand->name;
				array_push($players_names, $farmhand_name);
			}
		}
	} else {
		foreach($data->farmhands->Farmer as $side_player) {
			if(!empty($side_player->name))
				array_push($players_names, (string) $side_player->name);
		}
	}

	$GLOBALS['players_names'] = $players_names;

    return $players_names;
}


function get_aggregated_data(object $data):array {

    $general_data = $GLOBALS['untreated_all_players_data'];
    $game_version_score = (int) get_game_version_score((string) $general_data->gameVersion);
    $should_spawn_monsters = $general_data->shouldSpawnMonsters;

	$GLOBALS['untreated_player_data'] = $data;
	$GLOBALS['game_version'] = $general_data->gameVersion;
	$GLOBALS['game_version_score'] = $game_version_score;
	$GLOBALS['should_spawn_monsters'] = $should_spawn_monsters;
    
    return array(
        'general' => array(
            'id'                    => (int) $data->UniqueMultiplayerID,
            'game_version'          => (string) $general_data->gameVersion,
            'game_version_score'    => $game_version_score,
            'should_spawn_monsters' => $should_spawn_monsters,
            'name'                  => (string) $data->name,
            'gender'                => get_gender(array($data->gender, $data->isMale)),
            'farm_name'             => (string) $data->farmName,
            'farmer_level'          => get_farmer_level(),
            'favorite_thing'        => (string) $data->favoriteThing,
            'pet'                   => get_pet(),
            'spouse'                => get_spouse(),
            'children'              => get_children_amount((int) $data->UniqueMultiplayerID),
            'house_level'           => get_house_upgrade_level(),
            'date'                  => get_formatted_date(),
            'game_duration'         => get_game_duration((int) $data->millisecondsPlayed),
            'mine_level'            => (int) $data->deepestMineLevel,
            'max_items'             => (int) $data->maxItems,
            'max_health'            => (int) $data->maxHealth,
            'max_stamina'           => (int) $data->maxStamina,
            'grandpa_score'         => get_grandpa_score(),
            'golds'                 => (int) $data->money,
            'total_golds'           => (int) $data->totalMoneyEarned,
            'golden_walnuts'        => (int) $general_data->goldenWalnutsFound,
            'qi_gems'               => (int) $data->qiGems,
            'casino_coins'          => (int) $data->clubCoins
        ),
        'levels' => array(
            'farming_level'  => (int) $data->farmingLevel,
            'mining_level'   => (int) $data->miningLevel,
            'combat_level'   => (int) $data->combatLevel,
            'foraging_level' => (int) $data->foragingLevel,
            'fishing_level'  => (int) $data->fishingLevel,
        ),
        'has_element'     	=> get_unlockables_list(),
        'crafting_recipes'	=> get_crafting_recipes($data->craftingRecipes),
        'books'           	=> get_books($data->stats->Values),
        'masteries'       	=> get_masteries($data->stats->Values),
        'fish_caught'     	=> get_fish_caught($data->fishCaught),
        'artifacts_found' 	=> get_artifacts($data->archaeologyFound, $general_data),
        'minerals_found'  	=> get_minerals($data->mineralsFound, $general_data),
        'cooking_recipes' 	=> get_cooking_recipes($data->cookingRecipes, $data->recipesCooked),
        'shipped_items'   	=> get_shipped_items($data->basicShipped),
        'achievements'    	=> get_achievement($data->achievements),
        'skills'          	=> get_skills_data((array) $data->professions->int),
        'friendship'      	=> get_friendship_data($data->friendshipData),
        'enemies_killed'  	=> get_enemies_killed_data($data->stats),
        'quest_log'       	=> get_quest_log($data->questLog)
    );
}
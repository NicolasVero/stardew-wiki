<?php 

function get_gender(array $genders):string {

	foreach($genders as $gender) {
		if(!empty($gender)) {
			if(is_numeric($gender[0]))
				return ($gender[0] == 0) ? "Male" : "Female";
			else 
				return ($gender[0]) ? "Male" : "Female";
		}
	}

	return "Neutral";
}

function get_achievement(object $achievements):array {
   
	$achievements_data = array();
	
	foreach($achievements->int as $achievement) {
		$achievement_reference = find_reference_in_json((int) $achievement, 'achievements_details');

		$achievement_title = explode('µ', $achievement_reference)[0]; 
		$achievement_description = explode('µ', $achievement_reference)[1]; 

		$achievements_data[$achievement_title] = array(
			'description' => $achievement_description
		);
	}
	
	return $achievements_data;
}

function get_unlockables_list(object $data):array {
	return array(
		'forest_magic' => array(
			'id'       => 107004,
			'is_found' => get_unlockables("forest_magic")
		),
		'dwarvish_translation_guide' => array(
			'id'       => 107000,
			'is_found' => get_unlockables("dwarvish_translation_guide")
		),
		'rusty_key' => array(
			'id'       => 107001,
			'is_found' => get_unlockables("rusty_key")
		),
		'club_card' => array(
			'id'       => 107002,
			'is_found' => get_unlockables("club_card")
		),
		'special_charm' => array(
			'id'       => 107007,
			'is_found' => get_unlockables("special_charm")
		),
		'skull_key' => array(
			'id'       => 107003,
			'is_found' => get_unlockables("skull_key"),
		),
		'magnifying_glass' => array(
			'id'       => 107008,
			'is_found' => get_unlockables("magnifying_glass")
		),            
		'dark_talisman' => array(
			'id'       => 107005,
			'is_found' => get_unlockables("dark_talisman")
		),
		'magic_ink' => array(
			'id'       => 107006,
			'is_found' => get_unlockables("magic_ink")
		),
		'bears_knowledge' => array(
			'id'       => 107009,
			'is_found' => (int) in_array(2120303, (array) $data->eventsSeen->int)
		),
		'spring_onion_mastery' => array(
			'id'       => 107010,
			'is_found' => (int) in_array(3910979, (array) $data->eventsSeen->int)
		),
		'town_key' => array(
			'id'       => 107011,
			'is_found' => get_unlockables("town_key")
		)
	);
}

function get_unlockables(string $unlockable_name):int {

	$player_data = $GLOBALS['untreated_player_data'];
	$version_score = $GLOBALS['game_version_score'];

	$is_older_version = ($version_score < get_game_version_score("1.6.0"));


	switch($unlockable_name) {
		case "forest_magic":
			return has_element("canReadJunimoText", $player_data);
			break;

		case "dwarvish_translation_guide":
			return ($is_older_version)
				? has_element_ov($player_data->canUnderstandDwarves) : 
					((isset($GLOBALS['host_player_data']))
						? does_host_has_element("dwarvish_translation_guide") : has_element("HasDwarvishTranslationGuide", $player_data));
			break;

		case "rusty_key":
			return ($is_older_version)
				? has_element_ov($player_data->hasRustyKey) :
					((isset($GLOBALS['host_player_data']))
						? does_host_has_element("rusty_key") : has_element("HasRustyKey", $player_data));
			break;
		case "club_card":
			return ($is_older_version) ? has_element_ov($player_data->hasClubCard) : has_element("HasClubCard", $player_data);
			break;

		case "special_charm":
			return ($is_older_version) ? has_element_ov($player_data->hasSpecialCharm) : has_element("HasSpecialCharm", $player_data);
			break;

		case "skull_key":
			return ($is_older_version)
				? has_element_ov($player_data->hasSkullKey) :
					((isset($GLOBALS['host_player_data']))
						? does_host_has_element("skull_key") : has_element("HasSkullKey", $player_data));
			break;

		case "magnifying_glass":
			return ($is_older_version) ? has_element_ov($player_data->hasMagnifyingGlass) : has_element("HasMagnifyingGlass", $player_data);
			break;

		case "dark_talisman":
			return ($is_older_version) ? has_element_ov($player_data->hasDarkTalisman) : has_element("HasDarkTalisman", $player_data);
			break;

		case "magic_ink":
			return ($is_older_version) ? has_element_ov($player_data->hasMagicInk) : has_element("hasPickedUpMagicInk", $player_data);
			break;

		case "town_key":
			return ($is_older_version) ? has_element_ov($player_data->HasTownKey) : has_element("HasTownKey", $player_data);
			break;
	}
}

function get_shipped_items(object $items):array {

	$version_score = $GLOBALS['game_version_score'];
	$shipped_items_data = array();

	foreach($items->item as $item) {

		if($version_score < get_game_version_score("1.6.0")) 
			$item_id = formate_original_data_string($item->key->int);
		else 
			$item_id = formate_original_data_string($item->key->string);
		

		if(!ctype_digit($item_id))
			$item_id = get_custom_id($item_id);

		$shipped_items_reference = find_reference_in_json($item_id, 'shipped_items');
		
		if(!empty($shipped_items_reference)) {

			$shipped_items_data[$shipped_items_reference] = array(
				'id' => $item_id
			);
		}
	}
	
	return $shipped_items_data;
}

function get_skills_data(array $skills):array {
	$json_skills = sanitize_json_with_version('skills');
	$skills_datas = array();

	foreach($json_skills as $key => $skill) {
		if(in_array($key, $skills))
			$skills_datas[] = $json_skills[$key];
	}

	return $skills_datas;
}

function get_enemies_killed_data(object $data):array { 
	$enemies_data = array();
	
	foreach($data->specificMonstersKilled->item as $enemy) {
		$enemies_data[(string) $enemy->key->string] = array(
			'id'             => get_custom_id((string) $enemy->key->string),
			'killed_counter' => (int) $enemy->value->int
		);
	}
	
	return $enemies_data;
}

function get_books(object $data):array {
	return get_item_list($data, 'books');
}

function get_masteries(object $data):array {
	return get_item_list($data, 'masteries');
}

function get_item_list(object $data, string $filename):array {
	
	$version_score = $GLOBALS['game_version_score'];

	if($version_score < get_game_version_score("1.6.0")) 
		return array();
	
	$items_data = array();

	foreach($data->item as $item) {

		$item_id = formate_original_data_string($item->key->string);

		if(!ctype_digit($item_id)) 
			$item_id = get_custom_id($item_id);

		$item_reference = find_reference_in_json($item_id, $filename);

		if(empty($item_reference)) 
			continue;

		$items_data[$item_reference] = array(
			'id' => $item_id
		);
	}
	
	return $items_data;
}

function get_fish_caught(object $data):array {
	
	$version_score = $GLOBALS['game_version_score'];
	$fishes_data = array();

	foreach($data->item as $item) {

		if($version_score < get_game_version_score("1.6.0")) 
			$item_id = formate_original_data_string($item->key->int);
		else 
			$item_id = formate_original_data_string($item->key->string);


		if(!ctype_digit($item_id)) 
			$item_id = get_custom_id($item_id);

		$values_array = (array) $item->value->ArrayOfInt->int;
		$fish_reference = find_reference_in_json(
			$item_id,
			'fish'
		);

		if(empty($fish_reference) || $fish_reference == "") 
			continue;
		
		$fishes_data[$fish_reference] = array(
			'id'             => (int) $item_id,
			'caught_counter' => (int) $values_array[0],
			'max_length'     => (int) $values_array[1]
		);
	}

	return $fishes_data;
}

function get_friendship_data(object $data):array {
	$friends_data = array();
	$villagers_json = sanitize_json_with_version('villagers');
	$birthday_json = sanitize_json_with_version('villagers_birthday');

	foreach($data->item as $item) {
		
		$friend_name = (string) $item->key->string;

		if(!in_array($friend_name, $villagers_json)) continue;

		$friends_data[$friend_name] = array(
			'id'              => get_custom_id($friend_name),
			'points'          => (int) $item->value->Friendship->Points,
			'friend_level'    => (int) floor(($item->value->Friendship->Points) / 250),
			'birthday'        => $birthday_json[get_custom_id($friend_name)],
			'status'          => (string) $item->value->Friendship->Status,
			'week_gifts'      => (int) $item->value->Friendship->GiftsThisWeek,
			'talked_to_today' => (int) $item->value->Friendship->TalkedToToday
		);
	}

	uasort($friends_data, function ($a, $b) {
		return $b['points'] - $a['points'];
	});

	return $friends_data; 
}

function get_quest_log(object $data):array {
	$quests_data = array();

	foreach($data->Quest as $item) {
		$quest_id = (int) $item->id;
		$quest_reference = find_reference_in_json(
			$quest_id,
			'quests'
		);

		// Quêtes histoire
		if (!empty($quest_reference)){
			$quests_data[] = array(
				'time_limited'	=> false,
				'objective'   	=> $quest_reference['objective'],
				'description' 	=> $quest_reference['description'],
				'title'       	=> $quest_reference['title'],
				'rewards'     	=> $quest_reference['reward']
			);
		}

		// Quêtes daily
		else {
			$quest_type = (int) $item->questType;

			$days_left = (int) $item->daysLeft;
			$rewards = [(int) $item->reward];
			$target = $item->target;
			
			switch($quest_type) {

				case 3 :
					$goal_name = find_reference_in_json(formate_original_data_string($item->item), 'shipped_items');

					$keyword = "Deliver";
					$keyword_ing = "Delivering";

					$number_to_get = $item->number;
					$number_obtained = 0;
					break;

				case 4 :
					$goal_name = $item->monsterName;

					$keyword = "Kill";
					$keyword_ing = "Killing";

					$number_to_get = $item->numberToKill;
					$number_obtained = $item->numberKilled;
					break;

				case 5 :
					$goal_name = "people";

					$keyword = "Talk to";
					$keyword_ing = "Socializing";

					$number_to_get = $item->total;
					$number_obtained = $item->whoToGreet;
					break;

				case 7 :
					$goal_name = find_reference_in_json(formate_original_data_string($item->whichFish), 'fish');

					$keyword = "Fish";
					$keyword_ing = "Fishing";

					$number_to_get = $item->numberToFish;
					$number_obtained = $item->numberFished;
					break;

				case 10 :
					$goal_name = find_reference_in_json(formate_original_data_string($item->resource), 'shipped_items');

					$keyword = "Fish";
					$keyword_ing = "Fishing";

					$number_to_get = $item->number;
					$number_obtained = $item->numberCollected;
					break;
			}
			
			$title = "$keyword_ing Quest";
			$description = "Help $target with his $keyword_ing request.";
			$objective = "$keyword $number_to_get $goal_name for $target: $number_obtained/$number_to_get";
			$quests_data[] = array(
				'time_limited'	=> true,
				'objective'   	=> $objective,
				'description' 	=> $description,
				'title'       	=> $title,
				'daysLeft'    	=> $days_left,
				'rewards'     	=> $rewards
			);
		}
	}

	// Special Orders (Weekly)
	$entire_data = $GLOBALS['untreated_all_players_data'];
	$special_orders_json = sanitize_json_with_version('special_orders', true);

	foreach($entire_data->specialOrders->SpecialOrder as $special_order) {
		if(((string) $special_order->questState) != 'InProgress')
			continue;

		$target = (string) $special_order->requester;
		$is_qi_order = ((string) $special_order->orderType == "Qi");

		$number_to_get = (int) $special_order->objectives->maxCount;
		$number_obtained = (int) $special_order->objectives->currentCount;

		$title = ($is_qi_order) ? "QI's Special Order" : "Weekly Special Order";
		$description = $special_orders_json[(string) $special_order->questKey];

		$objective = "$target, $description: $number_obtained/$number_to_get";

		$days_left = (int) $special_order->dueDate - get_number_of_days_ingame();

		$rewards = array();

		foreach($special_order->rewards as $reward) {
			if($reward->amount) {
				$rewards[] = ($is_qi_order) ? (int) $reward->amount->int . "_q" : ((int) $reward->amount->int) * ((int) $reward->multiplier->float);
			}
		}
			
		$quests_data[] = array(
			'time_limited'	=> true,
			'objective'   	=> $objective,
			'description' 	=> $description,
			'title'       	=> $title,
			'daysLeft'    	=> $days_left,
			'rewards'     	=> $rewards
		);
	}

	return $quests_data;
}

function get_crafting_recipes(object $recipes):array {

	$crafting_recipes_data = array();
	$crafting_recipes_json = sanitize_json_with_version('crafting_recipes');

	foreach($recipes->item as $recipe) {
		
		$item_name = formate_original_data_string($recipe->key->string);
		$index = array_search($item_name, $crafting_recipes_json);

		$crafting_recipes_data[$item_name] = array(
			'id' => $index,
			'counter' => (int) $recipe->value->int
		);
	}
	
	return $crafting_recipes_data;
}

function get_cooking_recipes(object $recipes, object $recipes_cooked):array {

	$version_score = $GLOBALS['game_version_score'];
	$cooking_recipes_data = array();
	$cooking_recipes_json = sanitize_json_with_version('cooking_recipes');

	$has_ever_cooked = (empty((array) $recipes_cooked)) ? false : true;

	foreach($recipes->item as $recipe) {

		$item_name = formate_original_data_string($recipe->key->string);
		$index = array_search($item_name, $cooking_recipes_json);      

		if($has_ever_cooked) {
			foreach($recipes_cooked->item as $recipe_cooked) {

				if($version_score < get_game_version_score("1.6.0"))
					$recipe_id = (int) $recipe_cooked->key->int;
				else
					$recipe_id = (int) $recipe_cooked->key->string;

				if($recipe_id == $index) {
					$cooking_recipes_data[$item_name] = array(
						'id'      => $recipe_id,
						'counter' => (int) $recipe_cooked->value->int
					);
					break;
				}
				
			}

			if(isset($cooking_recipes_data[$item_name]))
				continue;
		}
		
		$cooking_recipes_data[$item_name] = array(
			'id'      => $index,
			'counter' => 0
		);

	}
	
	return $cooking_recipes_data;
}

function get_artifacts(object $artifacts, object $general_data):array {

	$version_score = $GLOBALS['game_version_score'];
	$artifacts_data = array();

	foreach($artifacts->item as $artifact) {

		if($version_score < get_game_version_score("1.6.0")) 
			$artifact_id = formate_original_data_string($artifact->key->int);
		else 
			$artifact_id = formate_original_data_string($artifact->key->string);

		if(!ctype_digit($artifact_id)) 
			$artifact_id = get_custom_id($artifact_id);

		$artifacts_reference = find_reference_in_json($artifact_id, 'artifacts');
		
		$museum_index = get_museum_index($general_data);

		if(!empty($artifacts_reference)) {
			$artifacts_data[$artifacts_reference] = array(
				'id'      => $artifact_id,
				'counter' => is_given_to_museum($artifact_id, $general_data, $museum_index, $version_score)
			);
		}
	}
	
	return $artifacts_data;
}

function get_minerals(object $minerals, object $general_data):array {
	
	$version_score = $GLOBALS['game_version_score'];
	$minerals_data = array();

	foreach($minerals->item as $mineral) {

		if($version_score < get_game_version_score("1.6.0")) 
			$mineral_id = formate_original_data_string($mineral->key->int);
		else 
			$mineral_id = formate_original_data_string($mineral->key->string);


		if(!ctype_digit($mineral_id)) 
			$mineral_id = get_custom_id($mineral_id);

		$minerals_reference = find_reference_in_json($mineral_id, 'minerals');
		
		$museum_index = get_museum_index($general_data);

		if(!empty($minerals_reference)) {
			$minerals_data[$minerals_reference] = array(
				'id'      => $mineral_id,
				'counter' => is_given_to_museum($mineral_id, $general_data, $museum_index, $version_score)
			);
		}
	}
	
	return $minerals_data;
}

function is_given_to_museum(int $item_id, object $general_data, int $museum_index, int $version_score):int { 

	// $location_index = ($version_score < get_game_version_score("1.6.0")) ? 31 : 32;
	$museum_items = $general_data->locations->GameLocation[$museum_index]->museumPieces;

	foreach($museum_items->item as $museum_item) {
		if($version_score < get_game_version_score("1.6.0")) {
			if($item_id == (int) $museum_item->value->int) return 1;
		} else {
			if($item_id == (int) $museum_item->value->string) return 1;
		}
	}

	return 0;
}

function get_museum_index(object $locations):int {
	$index_museum = 0;

	$locations = $locations->locations->GameLocation;

	foreach($locations as $location) {
		if(isset($location->museumPieces))
			break;
		$index_museum++;
	}

	return $index_museum;
}


function get_adventurers_guild_data():array {
	$categories = get_all_adventurers_guild_categories();
	$player_id = $GLOBALS['player_id'];
	$enemies_killed = $GLOBALS['all_players_data'][$player_id]['enemies_killed'];
	$adventurers_guild_data = array();
	
	foreach($categories as $monsters_name => $monters_data) {

		$counter = 0;
		extract($monters_data);

		foreach($enemies_killed as $enemy_killed) {
			if(in_array($enemy_killed['id'], $ids))
				$counter += $enemy_killed['killed_counter'];
		}

		$adventurers_guild_data[$monsters_name] = array(
			"counter"		=> $counter,
			"limit"			=> $limit,
			"reward"		=> $reward,
			"is_completed"	=> is_objective_completed($counter, $limit)
		);
	}

    $adventurers_guild_data["is_all_completed"] = is_all_the_adventurers_guild_categories_completed($adventurers_guild_data);

	return $adventurers_guild_data;
}

function is_all_the_adventurers_guild_categories_completed(array $adventurers_guild_data):bool {
    $counter = 0;
    foreach($adventurers_guild_data as $data) {
        if($data["is_completed"])
            $counter++;
    }

    return $counter == count($adventurers_guild_data);
}

function get_all_adventurers_guild_categories():array {
	return array(
		"slimes" => array(
			"ids" => array(105007, 105009, 105015, 105042),
			"limit" => 1000,
			"reward" => array(
				"alt" => "Slime Charmer Ring",
				"src" => "slime_charmer_ring"
			)
		),
		"void_spirits" => array(
			"ids" => array(105018, 105019, 105038),
			"limit" => 150,
			"reward" => array(
				"alt" => "Savage Ring",
				"src" => "savage_ring"
			)
		),
		"bats" => array(
			"ids" => array(105000, 105006, 105011, 105024),
			"limit" => 200,
			"reward" => array(
				"alt" => "Vampire Ring",
				"src" => "vampire_ring"
			)
		),
		"skeletons" => array(
			"ids" => array(105020),
			"limit" => 50,
			"reward" => array(
				"alt" => "Skeleton Mask",
				"src" => "skeleton_mask"
			)
		),
		"cave_insects" => array(
			"ids" => array(105002, 105003, 105010),
			"limit" => 80,
			"reward" => array(
				"alt" => "Insect Head",
				"src" => "insect_head"
			)
		),
		"duggies" => array(
			"ids" => array(105004, 105034),
			"limit" => 30,
			"reward" => array(
				"alt" => "Hard Hat",
				"src" => "hard_hat"
			)
		),
		"dust_sprites" => array(
			"ids" => array(105005),
			"limit" => 500,
			"reward" => array(
				"alt" => "Burglar's Ring",
				"src" => "burglars_ring"
			)
		),
		"rocks_crabs" => array(
			"ids" => array(105012, 105016, 105024),
			"limit" => 60,
			"reward" => array(
				"alt" => "Crabshell Ring",
				"src" => "crabshell_ring"
			)
		),
		"mummies" => array(
			"ids" => array(105014),
			"limit" => 100,
			"reward" => array(
				"alt" => "Arcane Hat",
				"src" => "arcane_hat"
			)
		),
		"pepper_rex" => array(
			"ids" => array(105028),
			"limit" => 50,
			"reward" => array(
				"alt" => "Knight's helmet",
				"src" => "knights_helmet"
			)
		),
		"serpents" => array(
			"ids" => array(105017),
			"limit" => 250,
			"reward" => array(
				"alt" => "Napalm Ring",
				"src" => "napalm_ring"
			)
		),
		"magma_sprites" => array(
			"ids" => array(105035, 105036),
			"limit" => 150,
			"reward" => array(
				"alt" => "Marlon's Phone Number",
				"src" => "phone_number"
			)
		)
	);
}
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

function does_player_have_achievement(object $achievements, int $achievement_id):bool {
	foreach($achievements->int as $achievement) {
		if($achievement_id == $achievement)
			return true;
	}
	return false;
}

function get_unlockables_list():array {

	$data = $GLOBALS['untreated_player_data'];
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
			'is_found' => get_unlockables("skull_key")
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
					$recipe_id = $recipe_cooked->key->string;

				$recipe_id = (filter_var((int) $recipe_id, FILTER_VALIDATE_INT)) ? (int) $recipe_id : get_custom_id((string) $recipe_id);

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

function get_adventurers_guild_data(int $player_id):array {
	$categories = get_all_adventurers_guild_categories();
	$enemies_killed = $GLOBALS['all_players_data'][$player_id]['enemies_killed'];
	$adventurers_guild_data = array();
	
	foreach($categories as $monsters_name => $monster_data) {

		$counter = 0;
		extract($monster_data);

		foreach($enemies_killed as $enemy_killed) {
			if(in_array($enemy_killed['id'], $ids)) 
				$counter += $enemy_killed['killed_counter'];
		}

		$adventurers_guild_data[$monsters_name] = array(
			"target"		=> $target_name,
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
			"target_name" => "Slimes",
			"ids" => array(105007, 105009, 105015, 105042),
			"limit" => 1000,
			"reward" => array(
				"alt" => "Slime Charmer Ring",
				"src" => "slime_charmer_ring"
			)
		),
		"void_spirits" => array(
			"target_name" => "Void Spirits",
			"ids" => array(105018, 105019, 105038),
			"limit" => 150,
			"reward" => array(
				"alt" => "Savage Ring",
				"src" => "savage_ring"
			)
		),
		"bats" => array(
			"target_name" => "Bats",
			"ids" => array(105000, 105006, 105011, 105024),
			"limit" => 200,
			"reward" => array(
				"alt" => "Vampire Ring",
				"src" => "vampire_ring"
			)
		),
		"skeletons" => array(
			"target_name" => "Skeletons",
			"ids" => array(105020),
			"limit" => 50,
			"reward" => array(
				"alt" => "Skeleton Mask",
				"src" => "skeleton_mask"
			)
		),
		"cave_insects" => array(
			"target_name" => "Cave Insects",
			"ids" => array(105002, 105003, 105010),
			"limit" => 80,
			"reward" => array(
				"alt" => "Insect Head",
				"src" => "insect_head"
			)
		),
		"duggies" => array(
			"target_name" => "Duggies",
			"ids" => array(105004, 105034),
			"limit" => 30,
			"reward" => array(
				"alt" => "Hard Hat",
				"src" => "hard_hat"
			)
		),
		"dust_sprites" => array(
			"target_name" => "Dust Sprites",
			"ids" => array(105005),
			"limit" => 500,
			"reward" => array(
				"alt" => "Burglar's Ring",
				"src" => "burglars_ring"
			)
		),
		"rocks_crabs" => array(
			"target_name" => "Rocks Crabs",
			"ids" => array(105012, 105016, 105026),
			"limit" => 60,
			"reward" => array(
				"alt" => "Crabshell Ring",
				"src" => "crabshell_ring"
			)
		),
		"mummies" => array(
			"target_name" => "Mummies",
			"ids" => array(105014),
			"limit" => 100,
			"reward" => array(
				"alt" => "Arcane Hat",
				"src" => "arcane_hat"
			)
		),
		"pepper_rex" => array(
			"target_name" => "Pepper Rex",
			"ids" => array(105028),
			"limit" => 50,
			"reward" => array(
				"alt" => "Knight's helmet",
				"src" => "knights_helmet"
			)
		),
		"serpents" => array(
			"target_name" => "Serpents",
			"ids" => array(105017, 105043),
			"limit" => 250,
			"reward" => array(
				"alt" => "Napalm Ring",
				"src" => "napalm_ring"
			)
		),
		"magma_sprites" => array(
			"target_name" => "Magma Sprites",
			"ids" => array(105035, 105036),
			"limit" => 150,
			"reward" => array(
				"alt" => "Marlon's Phone Number",
				"src" => "phone_number"
			)
		)
	);
}

function get_grandpa_score():int {

	$data = $GLOBALS['untreated_player_data'];
	$grandpa_points = 0;
	$money_earned_goals = array(
		array(
			"goal" => 50000, 
			"points" => 1
		),
		array(
			"goal" => 100000, 
			"points" => 1
		),
		array(
			"goal" => 200000, 
			"points" => 1
		),
		array(
			"goal" => 300000, 
			"points" => 1
		),
		array(
			"goal" => 500000, 
			"points" => 1
		),
		array(
			"goal" => 1000000, 
			"points" => 2
		)
	);

	$skill_goals = array(
		array(
			"goal" => 30, 
			"points" => 1
		),
		array(
			"goal" => 50, 
			"points" => 1
		)
	);

	$achievement_ids = array(
		5,
		26,
		34
	);

	$friendship_goals = array(
		5,
		10
	);

	$cc_rooms = array(
		"ccBoilerRoom",
		"ccCraftsRoom",
		"ccPantry",
		"ccFishTank",
		"ccVault",
		"ccBulletin"
	);

	// Gains totaux
	$total_money_earned = $data->totalMoneyEarned;
	foreach($money_earned_goals as $money_earned_goal) {
		extract($money_earned_goal);
		if($total_money_earned > $goal)
			$grandpa_points+=$points;
	}

	// Skill level
	$total_skills_level = get_total_skills_level($data);
	foreach($skill_goals as $skill_goal) {
		extract($skill_goal);
		if($total_skills_level > $goal)
			$grandpa_points+=$points;
	}

	// Achievements
	foreach($achievement_ids as $achievement_id)
		if(does_player_have_achievement($data->achievements, $achievement_id))
			$grandpa_points++;

	// Married + 2 house upgrades
	$house_level = get_house_upgrade_level();
	$is_married = get_is_married();
	if($house_level >= 2 && $is_married)
		$grandpa_points++;

	// Friendship
	$friendships = get_friendship_data($data->friendshipData);
	$friendship_count = 0;
	foreach($friendships as $friendship) {
		extract($friendship);
		if($friend_level >= 10)
			$friendship_count++;
	}

	foreach($friendship_goals as $friendship_goal) {
		if($friendship_count >= $friendship_goal)
			$grandpa_points++;
	}

	// Pet Friendship
	if(get_pet_frienship_points() >= 999)
		$grandpa_points++;

	// Community Center completed
	$cc_completed = true;
	foreach($cc_rooms as $cc_room) {
		if(!has_element($cc_room, $data))
			$cc_completed = false;
	}
	if($cc_completed)
		$grandpa_points++;

	// Community Center restored
	if(in_array(191393 , (array) $data->eventsSeen->int))
		$grandpa_points+=2;

	// Skull Key
	if(get_unlockables("skull_key"))
		$grandpa_points++;
	
	// Rusty Key
	if(get_unlockables("rusty_key"))
		$grandpa_points++;

	return $grandpa_points;
}

function get_highest_count_for_category(string $category):array {
	$game_version = substr($GLOBALS['game_version'], 0, 3);
	$total_players = get_number_of_player();
	$all_datas = $GLOBALS['all_players_data'];
	$highest_player = 0;
	$max_elements = 0;

	$exceptions_recipes = array(
		'cooking_recipes',
		'crafting_recipes'
	);

	$exceptions_level = array(
		'farmer_level'
	);

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(in_array($category, $exceptions_recipes)) {
			$filtered_elements = array_filter($all_datas[$current_player][$category], function($item) {
				return $item['counter'] > 0;
			});
			$amount_elements = count($filtered_elements);
		}
		else if(in_array($category, $exceptions_level)) {
			$level_category = $all_datas[$current_player]['levels'];
			$amount_elements = 0;
			
			foreach($level_category as $level) {
				$amount_elements += $level;
			}
		}
		else {
			$amount_elements = count($all_datas[$current_player][$category]);	
		}

		if($max_elements < $amount_elements) {
			$max_elements = $amount_elements;
			$highest_player = $current_player;
		}
	}

	$perfection_max = get_perfection_max_elements()[$game_version][$category];
	$max_elements = min($max_elements, $perfection_max);

	return array(
		'player_id' => $highest_player,
		'highest_count' => $max_elements
	);
}

function has_players_done_monster_slayer_hero():bool {
	$total_players = get_number_of_player();
	
	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(get_adventurers_guild_data($current_player)['is_all_completed'])
			return true;
	}

	return false;
}

function has_any_player_gotten_all_stardrops():bool {
	$total_players = get_number_of_player();
	$all_datas = $GLOBALS['all_players_data'];

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$amount_elements = $all_datas[$current_player]['general']['max_stamina'];
		if($amount_elements == 508)
			return true;
	}

	return false;
}

function get_player_with_highest_friendships():array {
	$game_version = substr($GLOBALS['game_version'], 0, 3);
	$total_players = get_number_of_player();
	$all_datas = $GLOBALS['all_players_data'];
	$highest_player = 0;
	$max_elements = 0;
	
    $marriables_npc = sanitize_json_with_version('marriables');

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$friendships = $all_datas[$current_player]['friendship'];
		$friend_counter = 0;

		foreach($friendships as $friendship_name => $friendship) {
			extract($friendship);
			$can_be_married = in_array($friendship_name, $marriables_npc) && $status == "Friendly";

			if(($can_be_married && $friend_level >= 8) || (!$can_be_married && $friend_level >= 10))
				$friend_counter++;
		}

		if($friend_counter > $max_elements) $max_elements = $friend_counter;
	}

	$perfection_max = get_perfection_max_elements()[$game_version]['friendship'];
	$max_elements = min($max_elements, $perfection_max);

	return array(
		'player_id' => $highest_player,
		'highest_count' => $max_elements
	);
}

function get_perfection_max_elements():array {
	return array(
		'1.5' => array(
			'farmer_level' => 25,
			'golden_walnuts' => 130,
			'obelisks' => 4,
			'crafting_recipes' => 129,
			'cooking_recipes' => 80,
			'shipped_items' => 145,
			'friendship' => 33,
			'fish_caught' => 67
		),
		'1.6' => array(
			'farmer_level' => 25,
			'golden_walnuts' => 130,
			'obelisks' => 4,
			'crafting_recipes' => 149,
			'cooking_recipes' => 81,
			'shipped_items' => 155,
			'friendship' => 34,
			'fish_caught' => 72
		)
	);
}

function get_perfection_elements():array {
	$general_data = $GLOBALS['host_player_data']['general'];
	$game_version = substr($GLOBALS['game_version'], 0, 3);

	$highest_items_shipped 		= get_highest_count_for_category('shipped_items')['highest_count'];
	$highest_farmer_level 		= get_highest_count_for_category('farmer_level')['highest_count'];
	$highest_fish_caught 		= get_highest_count_for_category('fish_caught')['highest_count'];
	$highest_cooking_recipes 	= get_highest_count_for_category('cooking_recipes')['highest_count'];
	$highest_crafting_recipes 	= get_highest_count_for_category('crafting_recipes')['highest_count'];
	$highest_friendship 		= get_player_with_highest_friendships()['highest_count'];

	return array(
		"Golden Walnuts found"		=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['golden_walnuts'], (int) $general_data['golden_walnuts']) * 5,
		"Crafting Recipes Made"		=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['crafting_recipes'], $highest_crafting_recipes) * 10,
		"Cooking Recipes Made"		=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['cooking_recipes'], $highest_cooking_recipes) * 10,
		"Produce & Forage Shipped"	=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['shipped_items'], $highest_items_shipped) * 15,
		"Obelisks on Farm"			=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['obelisks'], get_amount_obelisk_on_map()) * 4 ,
		"Farmer Level"				=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['farmer_level'], $highest_farmer_level) * 5 ,
		"Fish Caught"				=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['fish_caught'], $highest_fish_caught) * 10,
		"Great Friends"				=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]['friendship'], $highest_friendship) * 11,
		"Monster Slayer Hero"		=> get_element_completion_percentage(1, (int) has_players_done_monster_slayer_hero()) * 10,
		"Found All Stardrops"		=> get_element_completion_percentage(1, (int) has_any_player_gotten_all_stardrops()) * 10,
		"Golden Clock on Farm"		=> get_element_completion_percentage(1, (int) is_golden_clock_on_farm()) * 10
	);
}

function get_perfection_percentage():int {
	$untreated_data = $GLOBALS['untreated_all_players_data'];
	if((string) $untreated_data->farmPerfect == 'true')
		return 100;

	$perfection_elements = get_perfection_elements();
	$percentage = 0;
	foreach($perfection_elements as $element_percent)
		$percentage += $element_percent;

	return round($percentage);
}

function get_pet():array {
	$data = $GLOBALS['untreated_player_data'];
	$type = ($GLOBALS['game_version_score'] < get_game_version_score("1.6.0")) ?
		(((string) $data->catPerson == 'true') ? 'cat' : 'dog')
		:
		lcfirst((string) $data->whichPetType);
	$breed = (int) $data->whichPetBreed;

	return array(
		'type'  => $type,
		'breed' => $breed
	);
}
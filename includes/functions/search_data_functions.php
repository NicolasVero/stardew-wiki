<?php 

function get_gender(array $genders):string {

	foreach($genders as $gender) {
		if(!empty($gender)) {
			if(is_numeric($gender[0])) {
				return ($gender[0] == 0) ? "Male" : "Female";
			} else {
				return ($gender[0]) ? "Male" : "Female";
			} 
		}
	}

	return "Neutral";
}

function get_achievement(object $achievements):array {
   
	$achievements_data = [];
	
	foreach($achievements->int as $achievement) {
		$achievement_reference = find_reference_in_json((int) $achievement, "achievements_details");

		$achievement_title = explode("µ", $achievement_reference)[0]; 
		$achievement_description = explode("µ", $achievement_reference)[1]; 

		$achievements_data[$achievement_title] = [ "description" => $achievement_description ];
	}
	
	return $achievements_data;
}

function does_player_have_achievement(object $achievements, int $achievement_id):bool {
	foreach($achievements->int as $achievement) {
		if($achievement_id == $achievement) {
			return true;
		}
	}

	return false;
}

function get_unlockables_list():array {

	$data = $GLOBALS["untreated_player_data"];
	return [
		"forest_magic" => [
			"id"       => 107004,
			"is_found" => get_unlockables("forest_magic")
		],
		"dwarvish_translation_guide" => [
			"id"       => 107000,
			"is_found" => get_unlockables("dwarvish_translation_guide")
		],
		"rusty_key" => [
			"id"       => 107001,
			"is_found" => get_unlockables("rusty_key")
		],
		"club_card" => [
			"id"       => 107002,
			"is_found" => get_unlockables("club_card")
		],
		"special_charm" => [
			"id"       => 107007,
			"is_found" => get_unlockables("special_charm")
		],
		"skull_key" => [
			"id"       => 107003,
			"is_found" => get_unlockables("skull_key")
		],
		"magnifying_glass" => [
			"id"       => 107008,
			"is_found" => get_unlockables("magnifying_glass")
		],            
		"dark_talisman" => [
			"id"       => 107005,
			"is_found" => get_unlockables("dark_talisman")
		],
		"magic_ink" => [
			"id"       => 107006,
			"is_found" => get_unlockables("magic_ink")
		],
		"bears_knowledge" => [
			"id"       => 107009,
			"is_found" => (int) in_array(2120303, (array) $data->eventsSeen->int)
		],
		"spring_onion_mastery" => [
			"id"       => 107010,
			"is_found" => (int) in_array(3910979, (array) $data->eventsSeen->int)
		],
		"town_key" => [
			"id"       => 107011,
			"is_found" => get_unlockables("town_key")
		]
	];
}

function get_unlockables(string $unlockable_name):int {

	$player_data = $GLOBALS["untreated_player_data"];
	$version_score = $GLOBALS["game_version_score"];

	$is_older_version = ($version_score < get_game_version_score("1.6.0"));

	switch($unlockable_name) {
		case "forest_magic":
			return has_element("canReadJunimoText", $player_data);

		case "dwarvish_translation_guide":
			return ($is_older_version)
				? has_element_ov($player_data->canUnderstandDwarves) : 
					((isset($GLOBALS["host_player_data"]))
						? does_host_has_element("dwarvish_translation_guide") : has_element("HasDwarvishTranslationGuide", $player_data));

		case "rusty_key":
			return ($is_older_version)
				? has_element_ov($player_data->hasRustyKey) :
					((isset($GLOBALS["host_player_data"]))
						? does_host_has_element("rusty_key") : has_element("HasRustyKey", $player_data));
		
		case "club_card":
			return ($is_older_version) ? has_element_ov($player_data->hasClubCard) : has_element("HasClubCard", $player_data);

		case "special_charm":
			return ($is_older_version) ? has_element_ov($player_data->hasSpecialCharm) : has_element("HasSpecialCharm", $player_data);

		case "skull_key":
			return ($is_older_version)
				? has_element_ov($player_data->hasSkullKey) :
					((isset($GLOBALS["host_player_data"]))
						? does_host_has_element("skull_key") : has_element("HasSkullKey", $player_data));

		case "magnifying_glass":
			return ($is_older_version) ? has_element_ov($player_data->hasMagnifyingGlass) : has_element("HasMagnifyingGlass", $player_data);

		case "dark_talisman":
			return ($is_older_version) ? has_element_ov($player_data->hasDarkTalisman) : has_element("HasDarkTalisman", $player_data);

		case "magic_ink":
			return ($is_older_version) ? has_element_ov($player_data->hasMagicInk) : has_element("hasPickedUpMagicInk", $player_data);

		case "town_key":
			return ($is_older_version) ? has_element_ov($player_data->HasTownKey) : has_element("HasTownKey", $player_data);
	}
}

function get_shipped_items(object $items):array {

	$version_score = $GLOBALS["game_version_score"];
	$shipped_items_data = [];

	foreach($items->item as $item) {

		if($version_score < get_game_version_score("1.6.0")) {
			$item_id = formate_original_data_string($item->key->int);
		} else {
			$item_id = formate_original_data_string($item->key->string);
		} 
		
		get_correct_id($item_id);
		$shipped_items_reference = find_reference_in_json($item_id, "shipped_items");
		
		if(!empty($shipped_items_reference)) {
			$shipped_items_data[$shipped_items_reference] = [ "id" => $item_id ];
		}
	}
	
	return $shipped_items_data;
}

function get_skills_data(array $skills):array {
	$json_skills = sanitize_json_with_version("skills");
	$skills_datas = [];

	foreach($json_skills as $key => $skill) {
		if(in_array($key, $skills)) {
			$skills_datas[] = $json_skills[$key];
		}
	}

	return $skills_datas;
}

function get_enemies_killed_data(object $data):array { 
	$enemies_data = [];
	
	foreach($data->specificMonstersKilled->item as $enemy) {
		$enemies_data[(string) $enemy->key->string] = [
			"id"             => get_custom_id((string) $enemy->key->string),
			"killed_counter" => (int) $enemy->value->int
		];
	}

	return $enemies_data;
}

function get_books(object $data):array {
	return get_item_list($data, "books");
}

function get_masteries(object $data):array {
	return get_item_list($data, "masteries");
}

function get_item_list(object $data, string $filename):array {
	
	$version_score = $GLOBALS["game_version_score"];

	if($version_score < get_game_version_score("1.6.0")) {
		return [];
	}
	
	$items_data = [];

	foreach($data->item as $item) {

		$item_id = formate_original_data_string($item->key->string);
		get_correct_id($item_id);

		$item_reference = find_reference_in_json($item_id, $filename);

		if(empty($item_reference)) {
			continue;
		}

		$items_data[$item_reference] = [ "id" => $item_id ];
	}
	
	return $items_data;
}

function get_fish_caught(object $data):array {
	
	$version_score = $GLOBALS["game_version_score"];
	$fishes_data = [];

	foreach($data->item as $item) {

		if($version_score < get_game_version_score("1.6.0")) {
			$item_id = formate_original_data_string($item->key->int);
		} else {
			$item_id = formate_original_data_string($item->key->string);
		} 

		get_correct_id($item_id);

		$values_array = (array) $item->value->ArrayOfInt->int;
		$fish_reference = find_reference_in_json(
			$item_id,
			"fish"
		);

		if(empty($fish_reference) || $fish_reference == "") {
			continue;
		}
		
		$fishes_data[$fish_reference] = [
			"id"             => (int) $item_id,
			"caught_counter" => (int) $values_array[0],
			"max_length"     => (int) $values_array[1]
		];
	}

	return $fishes_data;
}

function get_friendship_data(object $data):array {
	$friends_data = [];
	$villagers_json = sanitize_json_with_version("villagers");
	$birthday_json = sanitize_json_with_version("villagers_birthday");

	foreach($data->item as $item) {
		
		$friend_name = (string) $item->key->string;

		if(!in_array($friend_name, $villagers_json)) {
			continue;
		}

		$friends_data[$friend_name] = [
			"id"              => get_custom_id($friend_name),
			"points"          => (int) $item->value->Friendship->Points,
			"friend_level"    => (int) floor(($item->value->Friendship->Points) / 250),
			"birthday"        => $birthday_json[get_custom_id($friend_name)],
			"status"          => (string) $item->value->Friendship->Status,
			"week_gifts"      => (int) $item->value->Friendship->GiftsThisWeek,
			"talked_to_today" => (int) $item->value->Friendship->TalkedToToday
		];
	}

	uasort($friends_data, function ($a, $b) {
		return $b["points"] - $a["points"];
	});

	return $friends_data; 
}

function get_quest_log(object $data):array {
	$quests_data = [];

	foreach($data->Quest as $item) {
		$quest_id = (int) $item->id;
		$quest_reference = find_reference_in_json(
			$quest_id,
			"quests"
		);

		// Quêtes histoire
		if(!empty($quest_reference)){
			$quests_data[] = [
				"time_limited"	=> false,
				"objective"   	=> $quest_reference["objective"],
				"description" 	=> $quest_reference["description"],
				"title"       	=> $quest_reference["title"],
				"rewards"     	=> $quest_reference["reward"]
			];
		} else {
			// Quêtes daily
			$quest_type = (int) $item->questType;

			$days_left = (int) $item->daysLeft;
			$rewards = [(int) $item->reward];
			$target = $item->target;
			
			switch($quest_type) {

				case 3 :
					$goal_name = find_reference_in_json(formate_original_data_string($item->item), "shipped_items");

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
					$goal_name = find_reference_in_json(formate_original_data_string($item->whichFish), "fish");

					$keyword = "Fish";
					$keyword_ing = "Fishing";

					$number_to_get = $item->numberToFish;
					$number_obtained = $item->numberFished;
					break;

				case 10 :
					$goal_name = find_reference_in_json(formate_original_data_string($item->resource), "shipped_items");

					$keyword = "Fish";
					$keyword_ing = "Fishing";

					$number_to_get = $item->number;
					$number_obtained = $item->numberCollected;
					break;
			}
			
			$title = "$keyword_ing Quest";
			$description = "Help $target with his $keyword_ing request.";
			$objective = "$keyword $number_to_get $goal_name for $target: $number_obtained/$number_to_get";
			$quests_data[] = [
				"time_limited"	=> true,
				"objective"   	=> $objective,
				"description" 	=> $description,
				"title"       	=> $title,
				"daysLeft"    	=> $days_left,
				"rewards"     	=> $rewards
			];
		}
	}

	// Special Orders (Weekly)
	$entire_data = $GLOBALS["untreated_all_players_data"];
	$special_orders_json = sanitize_json_with_version("special_orders", true);

	foreach($entire_data->specialOrders->SpecialOrder as $special_order) {
		if(((string) $special_order->questState) != "InProgress") {
			continue;
		}

		$target = (string) $special_order->requester;
		$is_qi_order = ((string) $special_order->orderType == "Qi");

		$number_to_get = (int) $special_order->objectives->maxCount;
		$number_obtained = (int) $special_order->objectives->currentCount;

		$title = ($is_qi_order) ? "QI's Special Order" : "Weekly Special Order";
		$description = $special_orders_json[(string) $special_order->questKey];

		$objective = "$target, $description: $number_obtained/$number_to_get";

		$days_left = (int) $special_order->dueDate - get_number_of_days_ingame();

		$rewards = [];

		foreach($special_order->rewards as $reward) {
			if($reward->amount) {
				$rewards[] = ($is_qi_order) ? (int) $reward->amount->int . "_q" : ((int) $reward->amount->int) * ((int) $reward->multiplier->float);
			}
		}
			
		$quests_data[] = [
			"time_limited"	=> true,
			"objective"   	=> $objective,
			"description" 	=> $description,
			"title"       	=> $title,
			"daysLeft"    	=> $days_left,
			"rewards"     	=> $rewards
		];
	}

	return $quests_data;
}

function get_crafting_recipes(object $recipes):array {

	$crafting_recipes_data = [];
	$crafting_recipes_json = sanitize_json_with_version("crafting_recipes");

	foreach($recipes->item as $recipe) {
		
		$item_name = formate_original_data_string($recipe->key->string);
		$index = array_search($item_name, $crafting_recipes_json);

		$crafting_recipes_data[$item_name] = [
			"id" => $index,
			"counter" => (int) $recipe->value->int
		];
	}
	
	return $crafting_recipes_data;
}

function get_cooking_recipes(object $recipes, object $recipes_cooked):array {

	$version_score = $GLOBALS["game_version_score"];
	$cooking_recipes_data = [];
	$cooking_recipes_json = sanitize_json_with_version("cooking_recipes");

	$has_ever_cooked = (empty((array) $recipes_cooked)) ? false : true;

	foreach($recipes->item as $recipe) {

		$item_name = formate_original_data_string($recipe->key->string);
		$index = array_search($item_name, $cooking_recipes_json);      

		if($has_ever_cooked) {
			foreach($recipes_cooked->item as $recipe_cooked) {

				if($version_score < get_game_version_score("1.6.0")) {
					$recipe_id = (int) $recipe_cooked->key->int;
				} else {
					$recipe_id = $recipe_cooked->key->string;
				}

				get_correct_id($recipe_id);

				if($recipe_id == $index) {
					$cooking_recipes_data[$item_name] = [
						"id"      => $recipe_id,
						"counter" => (int) $recipe_cooked->value->int
					];
					break;
				}
				
			}

			if(isset($cooking_recipes_data[$item_name])) {
				continue;
			}
		}
		
		$cooking_recipes_data[$item_name] = [
			"id"      => $index,
			"counter" => 0
		];

	}
	
	return $cooking_recipes_data;
}

function get_artifacts(object $artifacts, object $general_data):array {

	$version_score = $GLOBALS["game_version_score"];
	$artifacts_data = [];

	foreach($artifacts->item as $artifact) {

		if($version_score < get_game_version_score("1.6.0")) {
			$artifact_id = formate_original_data_string($artifact->key->int);
		} else {
			$artifact_id = formate_original_data_string($artifact->key->string);
		} 

		get_correct_id($artifact_id);

		$artifacts_reference = find_reference_in_json($artifact_id, "artifacts");
		$museum_index = get_museum_index($general_data);

		if(!empty($artifacts_reference)) {
			$artifacts_data[$artifacts_reference] = [
				"id"      => $artifact_id,
				"counter" => is_given_to_museum($artifact_id, $general_data, $museum_index, $version_score)
			];
		}
	}
	
	return $artifacts_data;
}

function get_minerals(object $minerals, object $general_data):array {
	$version_score = $GLOBALS["game_version_score"];
	$minerals_data = [];

	foreach($minerals->item as $mineral) {

		if($version_score < get_game_version_score("1.6.0")) {
			$mineral_id = formate_original_data_string($mineral->key->int);
		} else {
			$mineral_id = formate_original_data_string($mineral->key->string);
		} 


		get_correct_id($mineral_id);
		
		$minerals_reference = find_reference_in_json($mineral_id, "minerals");
		
		$museum_index = get_museum_index($general_data);

		if(!empty($minerals_reference)) {
			$minerals_data[$minerals_reference] = [
				"id"      => $mineral_id,
				"counter" => is_given_to_museum($mineral_id, $general_data, $museum_index, $version_score)
			];
		}
	}
	
	return $minerals_data;
}

function is_given_to_museum(int $item_id, object $general_data, int $museum_index, int $version_score):int { 

	$museum_items = $general_data->locations->GameLocation[$museum_index]->museumPieces;

	foreach($museum_items->item as $museum_item) {
		if($version_score < get_game_version_score("1.6.0")) {
			if($item_id == (int) $museum_item->value->int) {
				return 1;
			}
		} else {
			if($item_id == (int) $museum_item->value->string) {
				return 1;
			}
		}
	}

	return 0;
}

function get_museum_index(object $locations):int {
	$index_museum = 0;

	$locations = $locations->locations->GameLocation;

	foreach($locations as $location) {
		if(isset($location->museumPieces)) {
			break;
		}
		$index_museum++;
	}

	return $index_museum;
}

function get_adventurers_guild_data(int $player_id):array {
	$categories = get_all_adventurers_guild_categories();
	$enemies_killed = $GLOBALS["all_players_data"][$player_id]["enemies_killed"];
	$adventurers_guild_data = [];
	
	foreach($categories as $monsters_name => $monster_data) {

		$counter = 0;
		extract($monster_data);

		foreach($enemies_killed as $enemy_killed) {
			if(in_array($enemy_killed["id"], $ids)) {
				$counter += $enemy_killed["killed_counter"];
			}
		}

		$adventurers_guild_data[$monsters_name] = [
			"target"		=> $target_name,
			"counter"		=> $counter,
			"limit"			=> $limit,
			"reward"		=> $reward,
			"is_completed"	=> is_objective_completed($counter, $limit)
		];
	}

    $adventurers_guild_data["is_all_completed"] = is_all_the_adventurers_guild_categories_completed($adventurers_guild_data);

	return $adventurers_guild_data;
}

function is_all_the_adventurers_guild_categories_completed(array $adventurers_guild_data):bool {
    $counter = 0;
    foreach($adventurers_guild_data as $data) {
        if($data["is_completed"]) {	
			$counter++;
		}
    }

    return $counter == count($adventurers_guild_data);
}

function get_grandpa_score(): int {
    $data = $GLOBALS["untreated_player_data"];
    $grandpa_points = 0;

    $money_earned_goals = [
        ["goal" => 50000, "points" => 1],
        ["goal" => 100000, "points" => 1],
        ["goal" => 200000, "points" => 1],
        ["goal" => 300000, "points" => 1],
        ["goal" => 500000, "points" => 1],
        ["goal" => 1000000, "points" => 2]
    ];

    $skill_goals = [
        ["goal" => 30, "points" => 1],
        ["goal" => 50, "points" => 1]
    ];

    $achievement_ids = [5, 26, 34];
    $friendship_goals = [5, 10];
    $cc_rooms = [
        "ccBoilerRoom", "ccCraftsRoom", "ccPantry", 
        "ccFishTank", "ccVault", "ccBulletin"
    ];

    $total_money_earned = $data->totalMoneyEarned;
    foreach($money_earned_goals as $goal_data) {
        if($total_money_earned > $goal_data["goal"]) {
            $grandpa_points += $goal_data["points"];
        }
    }

    $total_skills_level = get_total_skills_level($data);
    foreach($skill_goals as $goal_data) {
        if($total_skills_level > $goal_data["goal"]) {
            $grandpa_points += $goal_data["points"];
        }
    }

    foreach($achievement_ids as $achievement_id) {
        if(does_player_have_achievement($data->achievements, $achievement_id)) {
            $grandpa_points++;
        }
    }

    $house_level = get_house_upgrade_level();
    $is_married = get_is_married();
    if($house_level >= 2 && $is_married) {
        $grandpa_points++;
    }

    $friendships = get_friendship_data($data->friendshipData);
    $friendship_count = 0;
    foreach($friendships as $friendship) {
        if($friendship["friend_level"] >= 8) {
            $friendship_count++;
        }
    }

    foreach($friendship_goals as $goal) {
        if($friendship_count >= $goal) {
            $grandpa_points++;
        }
    }

    if(get_pet_frienship_points() >= 999) {
        $grandpa_points++;
    }

    $cc_completed = array_reduce($cc_rooms, function($completed, $room) use ($data) {
        return $completed && has_element($room, $data);
    }, true);

    if($cc_completed) {
        $grandpa_points++;
    }

    if(in_array(191393, (array)$data->eventsSeen->int)) {
        $grandpa_points += 2;
    }

    if(get_unlockables("skull_key")) {
        $grandpa_points++;
    }

    if(get_unlockables("rusty_key")) {
        $grandpa_points++;
    }

    return $grandpa_points;
}


function get_highest_count_for_category(string $category):array {
	$game_version = substr($GLOBALS["game_version"], 0, 3);
	$total_players = get_number_of_player();
	$all_datas = $GLOBALS["all_players_data"];
	$highest_player = 0;
	$max_elements = 0;

	$exceptions_recipes = [
		"cooking_recipes",
		"crafting_recipes"
	];

	$exceptions_level = [
		"farmer_level"
	];

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(in_array($category, $exceptions_recipes)) {
			$filtered_elements = array_filter($all_datas[$current_player][$category], function($item) {
				return $item["counter"] > 0;
			});
			$amount_elements = count($filtered_elements);
		}
		else if(in_array($category, $exceptions_level)) {
			$level_category = $all_datas[$current_player]["levels"];
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

	return [
		"player_id" => $highest_player,
		"highest_count" => $max_elements
	];
}

function has_players_done_monster_slayer_hero():bool {
	$total_players = get_number_of_player();
	
	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(get_adventurers_guild_data($current_player)["is_all_completed"])
			return true;
	}

	return false;
}

function has_any_player_gotten_all_stardrops():bool {
	$total_players = get_number_of_player();
	$all_datas = $GLOBALS["all_players_data"];

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$amount_elements = $all_datas[$current_player]["general"]["max_stamina"];
		if($amount_elements == 508) {
			return true;
		}
	}

	return false;
}

function get_player_with_highest_friendships():array {
	$game_version = substr($GLOBALS["game_version"], 0, 3);
	$total_players = get_number_of_player();
	$all_datas = $GLOBALS["all_players_data"];
	$highest_player = 0;
	$max_elements = 0;
	
    $marriables_npc = sanitize_json_with_version("marriables");

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$friendships = $all_datas[$current_player]["friendship"];
		$friend_counter = 0;

		foreach($friendships as $friendship_name => $friendship) {
			extract($friendship);
			$can_be_married = in_array($friendship_name, $marriables_npc) && $status == "Friendly";

			if(($can_be_married && $friend_level >= 8) || (!$can_be_married && $friend_level >= 10)) {
				$friend_counter++;
			}
		}

		if($friend_counter > $max_elements) $max_elements = $friend_counter;
	}

	$perfection_max = get_perfection_max_elements()[$game_version]["friendship"];
	$max_elements = min($max_elements, $perfection_max);

	return [
		"player_id" => $highest_player,
		"highest_count" => $max_elements
	];
}

function get_perfection_elements():array {
	$general_data = $GLOBALS["host_player_data"]["general"];
	$game_version = substr($GLOBALS["game_version"], 0, 3);

	$highest_items_shipped 		= get_highest_count_for_category("shipped_items")["highest_count"];
	$highest_farmer_level 		= get_highest_count_for_category("farmer_level")["highest_count"];
	$highest_fish_caught 		= get_highest_count_for_category("fish_caught")["highest_count"];
	$highest_cooking_recipes 	= get_highest_count_for_category("cooking_recipes")["highest_count"];
	$highest_crafting_recipes 	= get_highest_count_for_category("crafting_recipes")["highest_count"];
	$highest_friendship 		= get_player_with_highest_friendships()["highest_count"];

	return [
		"Golden Walnuts found"		=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["golden_walnuts"], (int) $general_data["golden_walnuts"]) * 5,
		"Crafting Recipes Made"		=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["crafting_recipes"], $highest_crafting_recipes) * 10,
		"Cooking Recipes Made"		=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["cooking_recipes"], $highest_cooking_recipes) * 10,
		"Produce & Forage Shipped"	=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["shipped_items"], $highest_items_shipped) * 15,
		"Obelisks on Farm"			=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["obelisks"], get_amount_obelisk_on_map()) * 4 ,
		"Farmer Level"				=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["farmer_level"], $highest_farmer_level) * 5 ,
		"Fish Caught"				=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["fish_caught"], $highest_fish_caught) * 10,
		"Great Friends"				=> get_element_completion_percentage(get_perfection_max_elements()[$game_version]["friendship"], $highest_friendship) * 11,
		"Monster Slayer Hero"		=> get_element_completion_percentage(1, (int) has_players_done_monster_slayer_hero()) * 10,
		"Found All Stardrops"		=> get_element_completion_percentage(1, (int) has_any_player_gotten_all_stardrops()) * 10,
		"Golden Clock on Farm"		=> get_element_completion_percentage(1, (int) is_golden_clock_on_farm()) * 10
	];
}

function get_perfection_percentage():string {
	$untreated_data = $GLOBALS["untreated_all_players_data"];
	if((string) $untreated_data->farmPerfect == "true")
		return 100;

	$perfection_elements = get_perfection_elements();
	$percentage = 0;
	foreach($perfection_elements as $element_percent)
		$percentage += $element_percent;

	return round($percentage);
}

function get_pet():array {
	$data = $GLOBALS["untreated_player_data"];
	$type = ($GLOBALS["game_version_score"] < get_game_version_score("1.6.0")) ?
		(((string) $data->catPerson == "true") ? "cat" : "dog")
		:
		lcfirst((string) $data->whichPetType);
	$breed = (int) $data->whichPetBreed;

	return [
		"type"  => $type,
		"breed" => $breed
	];
}

function get_all_farm_animals(): array {
    $data = $GLOBALS["untreated_all_players_data"];
    $animals_data = [];
    
    $all_animals = [
        "Duck"              => "Duck",
        "White Chicken"     => "Chicken",
        "Brown Chicken"     => "Chicken",
        "Blue Chicken"      => "Chicken",
        "Golden Chicken"    => "Golden Chicken",
        "Void Chicken"      => "Void Chicken",
        "Rabbit"            => "Rabbit",
        "Dinosaur"          => "Dinosaur",
        "Brown Cow"         => "Cow",
        "White Cow"         => "Cow",
        "Pig"               => "Pig",
        "Goat"              => "Goat",
        "Sheep"             => "Sheep",
        "Ostrich"           => "Ostrich"
    ];

    foreach($data->locations->GameLocation as $location) {
        if(!isset($location->buildings)) {
            continue;
        }

        foreach($location->buildings->Building as $building) {
            if(!isset($building->indoors->animals)) {
                continue;
            }

            foreach($building->indoors->animals->item as $animal) {
				$name = (string) $animal->value->FarmAnimal->name;
                $full_animal_type = (string) $animal->value->FarmAnimal->type;
				$friendship = (int) $animal->value->FarmAnimal->friendshipTowardFarmer;
				$happiness = (int) $animal->value->FarmAnimal->happiness;

				$pet = ((string) $animal->value->FarmAnimal->wasPet == "true") ? true : false;
				$auto_pet = ((string) $animal->value->FarmAnimal->wasAutoPet == "true") ? true : false;
				$was_pet = (($pet) || ($auto_pet));

				$animal_data = [
					"name" => $name,
					"type" => $full_animal_type,
					"friendship_level" => floor($friendship / 100) / 2,
					"happiness" => $happiness,
					"was_pet" => $was_pet
				];

                if(!isset($all_animals[$full_animal_type])) {
                    continue;
                }

                $animal_type = $all_animals[$full_animal_type];

                if(!isset($animals_data[$animal_type])) {
                    $animals_data[$animal_type] = [
                        "id" => get_custom_id($animal_type),
						"animals_data" => [],
                        "counter" => 0
                    ];
                }

                $animals_data[$animal_type]["counter"]++;
				array_push($animals_data[$animal_type]["animals_data"], $animal_data);
            }
        }

        break;
    }

    return $animals_data;
}

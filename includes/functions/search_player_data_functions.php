<?php 

function get_player_gender(array $genders):string {
	foreach($genders as $gender) {
		if(empty($gender)) {
			continue;
		}

		if(is_numeric($gender[0])) {
			return ($gender[0] === 0) ? "Male" : "Female";
		} else {
			return ($gender[0]) ? "Male" : "Female";
		} 
	}

	return "Neutral";
}

function get_player_achievements(object $achievements):array {
	$achievements_data = [];
	
	foreach($achievements->int as $achievement) {
		$achievement = find_reference_in_json((int) $achievement, "achievements_details");
		extract($achievement);

		$achievements_data[$title] = [ "description" => $description ];
	}
	
	return $achievements_data;
}

function does_player_have_achievement(object $achievements, int $achievement_id):bool {
	foreach($achievements->int as $achievement) {
		if($achievement_id === $achievement) {
			return true;
		}
	}

	return false;
}

function get_player_unlockables_list():array {
	$data = $GLOBALS["untreated_player_data"];
	return [
		"forest_magic" => [
			"id"       => 107004,
			"is_found" => get_player_unlockable("forest_magic")
		],
		"dwarvish_translation_guide" => [
			"id"       => 107000,
			"is_found" => get_player_unlockable("dwarvish_translation_guide")
		],
		"rusty_key" => [
			"id"       => 107001,
			"is_found" => get_player_unlockable("rusty_key")
		],
		"club_card" => [
			"id"       => 107002,
			"is_found" => get_player_unlockable("club_card")
		],
		"special_charm" => [
			"id"       => 107007,
			"is_found" => get_player_unlockable("special_charm")
		],
		"skull_key" => [
			"id"       => 107003,
			"is_found" => get_player_unlockable("skull_key")
		],
		"magnifying_glass" => [
			"id"       => 107008,
			"is_found" => get_player_unlockable("magnifying_glass")
		],            
		"dark_talisman" => [
			"id"       => 107005,
			"is_found" => get_player_unlockable("dark_talisman")
		],
		"magic_ink" => [
			"id"       => 107006,
			"is_found" => get_player_unlockable("magic_ink")
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
			"is_found" => get_player_unlockable("town_key")
		]
	];
}

function get_player_unlockable(string $unlockable_name):int {
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

function get_player_shipped_items(object $items):array {
	$shipped_items_data = [];

	foreach($items->item as $item) {

		$item_id = (is_game_older_than_1_6()) ? $item->key->int : $item->key->string;
		$item_id = formate_original_data_string($item_id);
		
		get_correct_id($item_id);
		$shipped_items_reference = find_reference_in_json($item_id, "shipped_items");
		
		if(!empty($shipped_items_reference)) {
			$shipped_items_data[$shipped_items_reference] = [ "id" => $item_id ];
		}
	}
	
	return $shipped_items_data;
}

function get_player_skills_data(array $skills):array {
	$json_skills = sanitize_json_with_version("skills");
	$skills_data = [];

	foreach($json_skills as $key => $skill) {
		if(in_array($key, $skills)) {
			$skills_data[] = $json_skills[$key];
		}
	}

	return $skills_data;
}

function get_player_enemies_killed_data(object $data):array { 
	$enemies_data = [];
	
	foreach($data->specificMonstersKilled->item as $enemy) {
		$enemies_data[(string) $enemy->key->string] = [
			"id"             => get_custom_id((string) $enemy->key->string),
			"killed_counter" => (int) $enemy->value->int
		];
	}

	return $enemies_data;
}

function get_player_books(object $data):array {
	return get_player_items_list($data, "books");
}

function get_player_masteries(object $data):array {
	return get_player_items_list($data, "masteries");
}

function get_player_items_list(object $data, string $filename):array {
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

function get_player_fishes_caught(object $data):array {
	$version_score = $GLOBALS["game_version_score"];
	$fishes_data = [];

	foreach($data->item as $item) {

		$item_id = (is_game_older_than_1_6()) ? $item->key->int : $item->key->string;
		$item_id = formate_original_data_string($item_id);

		get_correct_id($item_id);

		$values_array = (array) $item->value->ArrayOfInt->int;
		$fish_reference = find_reference_in_json(
			$item_id,
			"fish"
		);

		if(empty($fish_reference) || $fish_reference === "") {
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

function get_player_friendship_data(object $data):array {
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

function get_player_quest_log(object $data):array {
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

			$goal_name = "";
			$keyword = "";
			$keyword_ing = "";
			$number_to_get = 0;
			$number_obtained = 0;
			
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
		if(((string) $special_order->questState) !== "InProgress") {
			continue;
		}

		$target = (string) $special_order->requester;
		$is_qi_order = ((string) $special_order->orderType === "Qi");

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

function get_player_crafting_recipes(object $recipes):array {
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

function get_player_cooking_recipes(object $recipes, object $recipes_cooked):array {
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

				if($recipe_id === $index) {
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

function get_player_artifacts(object $artifacts, object $general_data):array {
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
		$museum_index = get_gamelocation_index($general_data, "museumPieces");

		if(!empty($artifacts_reference)) {
			$artifacts_data[$artifacts_reference] = [
				"id"      => $artifact_id,
				"counter" => is_given_to_museum($artifact_id, $general_data, $museum_index, $version_score)
			];
		}
	}
	
	return $artifacts_data;
}

function get_player_minerals(object $minerals, object $general_data):array {
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
		
		$museum_index = get_gamelocation_index($general_data, "museumPieces");

		if(!empty($minerals_reference)) {
			$minerals_data[$minerals_reference] = [
				"id"      => $mineral_id,
				"counter" => is_given_to_museum($mineral_id, $general_data, $museum_index, $version_score)
			];
		}
	}
	
	return $minerals_data;
}

function get_player_adventurers_guild_data(int $player_id):array {
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

    $adventurers_guild_data["is_all_completed"] = are_all_adventurers_guild_categories_completed($adventurers_guild_data);

	return $adventurers_guild_data;
}

function are_all_adventurers_guild_categories_completed(array $adventurers_guild_data):bool {
    $counter = 0;
    foreach($adventurers_guild_data as $data) {
        if($data["is_completed"]) {	
			$counter++;
		}
    }

    return $counter === count($adventurers_guild_data);
}

function get_player_pet(object $data):array {
	$breed = (int) $data->whichPetBreed;
	$type = (is_game_older_than_1_6()) ?
		(((string) $data->catPerson === "true") ? "cat" : "dog")
		:
		lcfirst((string) $data->whichPetType);

	return [
		"type"  => $type,
		"breed" => $breed
	];
}

function get_player_farm_animals():array {
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

				$pet = ((string) $animal->value->FarmAnimal->wasPet === "true") ? true : false;
				$auto_pet = ((string) $animal->value->FarmAnimal->wasAutoPet === "true") ? true : false;
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

function get_player_secret_notes(object $notes):array {
	$notes = (array) $notes->int;
	sort($notes);
	$all_notes = [];

	foreach($notes as $note) {
		$note_name = find_reference_in_json($note, "secret_notes");
		$all_notes[$note_name] = [
			"id" => $note
		];
	}

	return $all_notes;
}

function get_jumino_kart_leaderboard():array {
	$data = $GLOBALS["untreated_all_players_data"];
	$all_entries = $data->junimoKartLeaderboards->entries;
	$leaderboard = [];

	foreach($all_entries as $entries) {
		foreach($entries as $entry) {
			$leaderboard[] = [
				"score" => (int) $entry->score->int,
				"name"  => (string) $entry->name->string
			];
		}
	}

	return $leaderboard;
}

function get_player_stardrops_found(int $player_stamina):int {
	$min_stamina = 270;
	$stamina_per_stardrop = 34;
	return ($player_stamina - $min_stamina) / $stamina_per_stardrop;
}

function get_player_visited_locations(object $player_data):array {
	$locations_to_visit = sanitize_json_with_version("locations_to_visit");
	$player_visited_locations = [];
	$locations_real_name = [
		"Club" => "Casino",
		"Desert" => "Calico Desert",
		"SkullCave" => "Skull Cavern",
		"Greenhouse" => "Greenhouse",
		"Woods" => "Secret Woods",
		"Sewer" => "The Sewers",
		"WitchSwamp" => "Witch's Swamp",
		"IslandSouth" => "Ginger Island",
		"QiNutRoom" => "Qi's Walnut Room",
		"Summit" => "The Summit",
		"MasteryCave" => "Mastery Cave"
	];

	foreach($player_data->locationsVisited->string as $location_visited) {
		$location_name = (string) $location_visited;
		$location_real_name = $locations_real_name[$location_name] ?? "";

		if(in_array($location_real_name, $locations_to_visit)) {
			$player_visited_locations[$location_real_name] = [
				"id" => get_item_id_by_name($location_real_name)
			];
		}
	}

	$additional_locations = [
		"VisitedQuarryMine" => "Quarry"
	];

	foreach($additional_locations as $additional_location => $location_real_name) {
		if(has_element($additional_location, $player_data)) {
			$player_visited_locations[$location_real_name] = [
				"id" => get_item_id_by_name($location_real_name)
			];
		}
	}

	return $player_visited_locations;
}

function get_player_bundles(object $general_data):array {
	$bundles_index = get_gamelocation_index($general_data, "bundles");
	$bundles_json = sanitize_json_with_version("bundles", true);
	$bundles_data = $general_data->bundleData;
	$bundle_arrays = $general_data->locations->GameLocation[$bundles_index]->bundles;

	foreach($bundle_arrays->item as $bundle_array) {
		$bundle_id = (int) $bundle_array->key->int;
		$bundle_booleans = (array) $bundle_array->value->ArrayOfBoolean->boolean;

		foreach($bundles_json as $bundle_room_name => $bundle_room_details) {
			if(!in_array($bundle_id, $bundle_room_details["bundle_ids"])) {
				continue;
			}

			$bundle_room = $bundle_room_name;
		}
		
		if(empty($bundle_room)) {
			continue;
		}

		$bundle_data_name = "$bundle_room/$bundle_id";
		$bundle_progress = [
			"room_name" => $bundle_room,
			"id" => $bundle_id,
			"progress"  => $bundle_booleans
		];

		foreach($bundles_data->item as $bundle_data) {
			if((string) $bundle_data->key->string != $bundle_data_name) {
				continue;
			}

			$player_bundles[$bundle_id] = get_player_bundle_progress($bundle_data, $bundle_progress);
		}
	}
	
	ksort($player_bundles);
	
	return $player_bundles;
}
<?php

function is_objective_completed(int $current_counter, int $limit):bool {
    return ($current_counter >= $limit);
}

function get_element_completion_percentage(int $max_amount, int $current_amount):float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
}

function does_host_has_element(string $element):int {
	return ($GLOBALS["host_player_data"]["unlockables"][$element]["is_found"]);
}

function has_element(string $element, object $data):int {
    return (in_array($element, (array) $data->mailReceived->string)) ? 1 : 0;
}

function has_element_ov(object $element):int {
    return !empty((array) $element);
}

function get_game_version_score(string $version):int {
	$version_numbers = explode(".", $version);

	while(count($version_numbers) < 3) {
        $version_numbers[] = 0;
    }

	$version_numbers = array_reverse($version_numbers);
	$score = 0;

	for($i = 0; $i < count($version_numbers); $i++) {
        $score += $version_numbers[$i] * pow(1000, $i); 
    }

	return (int) $score;
}

function get_player_season():string {
	return get_formatted_date(false)["season"];
}

function get_total_skills_level(object $data):int {
	return ($data->farmingLevel + $data->miningLevel + $data->combatLevel + $data->foragingLevel + $data->fishingLevel);
}

function get_pet_frienship_points():int {
	$locations = $GLOBALS["untreated_all_players_data"]->locations->GameLocation;
	foreach($locations as $location) {
		if(isset($location->characters)) {
            foreach($location->characters->NPC as $npc) {
                if(isset($npc->petType)) {
                    return (int) $npc->friendshipTowardFarmer;
                }
            }
        }
	}

	return 0;
}

function get_is_married():bool {
	$data = $GLOBALS["untreated_player_data"];
	return isset($data->spouse);
}

function get_spouse(object $data):mixed {
	return (!empty($data->spouse)) ? $data->spouse : null;
}

function is_this_the_same_day(string $date):bool {
    extract(get_formatted_date(false));
    return $date === "$day/$season";
}

function get_amount_obelisk_on_map():int {
	$locations = $GLOBALS["untreated_all_players_data"]->locations->GameLocation;
	$obelisk_count = 0;
	$obelisk_names = [
		"Earth Obelisk",
		"Water Obelisk",
		"Island Obelisk",
		"Desert Obelisk",
	];

	foreach($locations as $location) {
		if(isset($location->buildings->Building)) {
			foreach($location->buildings->Building as $building) {
				if(in_array((string) $building->buildingType, $obelisk_names)) {
                    $obelisk_count++;
                }
			}
		}
	}

	return $obelisk_count;
}

function is_golden_clock_on_farm():bool {
	$locations = $GLOBALS["untreated_all_players_data"]->locations->GameLocation;
	foreach($locations as $location) {
		if(isset($location->buildings->Building)) {
			foreach($location->buildings->Building as $building) {
				if((string) $building->buildingType === "Gold Clock") {
                    return true;
                }
			}
		}
	}

	return false;
}

function get_house_upgrade_level(object $data):int {
	return (int) $data->houseUpgradeLevel;
}

function get_children_amount(int $id):array {
	$locations = $GLOBALS["untreated_all_players_data"]->locations->GameLocation;
	$children_name = [];

	foreach($locations as $location) {
		if(isset($location->characters)) {
			foreach($location->characters->NPC as $npc) {
				if(!isset($npc->idOfParent)) {
                    continue;
                }

				if((int) $npc->idOfParent === $id) {
                    array_push($children_name, $npc->name);
                }
			}
		}
	}

	foreach($locations as $location) {
		if(isset($location->buildings)) {
			foreach($location->buildings->Building as $building) {
				if(isset($building->indoors->characters)) {
					foreach($building->indoors->characters->NPC as $npc) {
						if(!isset($npc->idOfParent)) {
                            continue;
                        }

						if((int) $npc->idOfParent === $id) {
                            array_push($children_name, $npc->name);
                        }
                    }
				}
			}
		}
	}
	
	return $children_name;
}

function get_the_married_person_gender(string $spouse):string {
	$wifes = ["abigail", "emily", "haley", "leah", "maru", "penny"];
	$husbands = ["alex", "elliott", "harvey", "sam", "sebastian", "shane"];

	if(in_array(strtolower($spouse), $wifes)) {
		return "your wife";
	}

	if(in_array(strtolower($spouse), $husbands)) {
		return "your husband";
	}

	return "";
}

function get_all_adventurers_guild_categories():array {
	return $GLOBALS["json"]["adventurer's_guild_goals"];
}

function get_weather(string $weather_location = "Default"):string {
    $data = $GLOBALS["untreated_all_players_data"];
    $locations = $data->locationWeather;

    foreach($locations as $complex_location) {
		foreach($complex_location as $location) {
			if($location->key->string === $weather_location) {
				if($location->value->LocationWeather->weather->string !== "Festival") {
					return formate_text_for_file((string)$location->value->LocationWeather->weather->string);
				}

				if($location->value->LocationWeather->isRaining->string === true) {
					return "rain";
				}

				if($location->value->LocationWeather->isSnowing->string === true) {
					return "snow";
				}

				if($location->value->LocationWeather->isLightning->string === true) {
					return "storm";
				}

				if($location->value->LocationWeather->isGreenRain->string === true) {
					return "green_rain";
				}
			}
        }
    }

	return "sun";
}

function is_given_to_museum(int $item_id, object $general_data, int $museum_index, int $version_score):int { 

	$museum_items = $general_data->locations->GameLocation[$museum_index]->museumPieces;

	foreach($museum_items->item as $museum_item) {
		if($version_score < get_game_version_score("1.6.0")) {
			if($item_id === (int) $museum_item->value->int) {
				return 1;
			}
		} else {
			if($item_id === (int) $museum_item->value->string) {
				return 1;
			}
		}
	}

	return 0;
}

function get_gamelocation_index(object $general_data, string $searched_location):int {
	$index = 0;
	$locations = $general_data->locations->GameLocation;

	foreach($locations as $location) {
		if(isset($location->$searched_location)) {
			break;
		}
		$index++;
	}

	return $index;
}

function get_farmer_level(object $data):string {
    $level_names = [
        "Newcomer",
        "Greenhorn",
        "Bumpkin",
        "Cowpoke",
        "Farmhand",
        "Tiller",
        "Smallholder",
        "Sodbuster",
        "Farmboy",
        "Granger",
        "Planter",
        "Rancher",
        "Farmer",
        "Agriculturist",
        "Cropmaster",
        "Farm King"
    ];
    $level = (get_total_skills_level($data) + $data->luckLevel) / 2;
    return $level_names[floor($level / 2)];
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

    $house_level = get_house_upgrade_level($data);
    $is_married = get_is_married();
    if($house_level >= 2 && $is_married) {
        $grandpa_points++;
    }

    $friendships = get_player_friendship_data($data->friendshipData);
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

    if(get_player_unlockable("skull_key")) {
        $grandpa_points++;
    }

    if(get_player_unlockable("rusty_key")) {
        $grandpa_points++;
    }

    return $grandpa_points;
}

function get_candles_lit(int $grandpa_score):int {
	if($grandpa_score <= 3) {
        return 1;
    }
	
	if($grandpa_score > 3 && $grandpa_score <= 7) {
        return 2;
    }
	
	if($grandpa_score > 7 && $grandpa_score <= 11) {
        return 3;
    }
	
	return 4;
}

function get_perfection_max_elements():array {
	return $GLOBALS["json"]["perfection_elements"];
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
	if((string) $untreated_data->farmPerfect === "true")
		return 100;

	$perfection_elements = get_perfection_elements();
	$percentage = 0;
	foreach($perfection_elements as $element_percent)
		$percentage += $element_percent;

	return round($percentage);
}

function get_highest_count_for_category(string $category):array {
	$game_version = substr($GLOBALS["game_version"], 0, 3);
	$total_players = get_number_of_player();
	$all_data = $GLOBALS["all_players_data"];
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
			$filtered_elements = array_filter($all_data[$current_player][$category], function($item) {
				return $item["counter"] > 0;
			});
			$amount_elements = count($filtered_elements);
		}
		else if(in_array($category, $exceptions_level)) {
			$level_category = $all_data[$current_player]["levels"];
			$amount_elements = 0;
			
			foreach($level_category as $level) {
				$amount_elements += $level;
			}
		}
		else {
			$amount_elements = count($all_data[$current_player][$category]);	
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

function get_player_with_highest_friendships():array {
	$game_version = substr($GLOBALS["game_version"], 0, 3);
	$total_players = get_number_of_player();
	$all_data = $GLOBALS["all_players_data"];
	$highest_player = 0;
	$max_elements = 0;
	
    $marriables_npc = sanitize_json_with_version("marriables");

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$friendships = $all_data[$current_player]["friendship"];
		$friend_counter = 0;

		foreach($friendships as $friendship_name => $friendship) {
			extract($friendship);
			$can_be_married = in_array($friendship_name, $marriables_npc) && $status === "Friendly";

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

function has_players_done_monster_slayer_hero():bool {
	$total_players = get_number_of_player();
	
	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(get_player_adventurers_guild_data($current_player)["is_all_completed"])
			return true;
	}

	return false;
}

function has_any_player_gotten_all_stardrops():bool {
	$total_players = get_number_of_player();
	$all_data = $GLOBALS["all_players_data"];

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$stardrops_founds = $all_data[$current_player]["general"]["stardrops_found"];
		if($stardrops_founds === 7) {
			return true;
		}
	}

	return false;
}

function get_junimo_leaderboard(object $junimo_leaderboard):object {
	if(is_object_empty($junimo_leaderboard)) {
		return get_junimo_kart_fake_leaderboard();
	}

	return $junimo_leaderboard;
}

function get_junimo_kart_fake_leaderboard(): object {
    return (object) [
        "NetLeaderboardsEntry" => [
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 50000]
            ],
            (object) [
                "name" => (object) ["string" => "Shane"],
                "score" => (object) ["int" => 25000]
            ],
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 10000]
            ],
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 5000]
            ],
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 250]
            ],
        ],
    ];
}


function get_museum_pieces_coords(object $data):array {
	$museum_index = get_gamelocation_index($data, "museumPieces");
	$in_game_museum_pieces = $data->locations->GameLocation[$museum_index]->museumPieces;
	$museum_piece_details = [];

	foreach($in_game_museum_pieces->item as $museum_piece) {
		$museum_piece_id = (is_game_older_than_1_6()) ? (int) $museum_piece->value->int : (int) $museum_piece->value->string;
		$museum_piece_name = get_item_name_by_id($museum_piece_id);
		$museum_piece_details[$museum_piece_name] = [
			"id" => $museum_piece_id,
			"type" => get_museum_piece_type($museum_piece_name),
			"coords" => [
				"X" => (int) $museum_piece->key->Vector2->X,
				"Y" => (int) $museum_piece->key->Vector2->Y
			]
		];
	}

	usort($museum_piece_details, function($a, $b) {
		return $a["coords"]["X"] <=> $b["coords"]["X"];
	});
	return $museum_piece_details;
}

function get_museum_piece_type(string $piece_name):string {
	$artifacts = sanitize_json_with_version("artifacts", true);
	return (in_array($piece_name, $artifacts)) ? "artifacts" : "minerals";
}

function get_cc_binary_hash(array $player_bundles):string {
	$bundles_json = sanitize_json_with_version("bundles", true);
	$room_indexes = [];

	foreach($bundles_json as $room_name => $room_details) {
		$room_indexes[$room_name] = [];
		foreach ($room_details["bundle_ids"] as $id) {
			$room_indexes[$room_name][] = [$id => false];
		}
	}

	foreach($player_bundles as $bundle_id => $player_bundle) {
		if(empty($player_bundle["is_complete"]) || $player_bundle["is_complete"] === false) {
			continue;
		}

		foreach($room_indexes[$player_bundle["room_name"]] as &$bundle) {
			if(!isset($bundle[$bundle_id])) {
				continue;
			}
			
			$bundle[$bundle_id] = true;
		}
	}

	$binary_result = "";
	foreach($room_indexes as $room_name => $bundles) {
		$all_complete = true;

		foreach($bundles as $bundle) {
			if(in_array(false, $bundle)) {
				$all_complete = false;
				break;
			}
		}
		
		$binary_result .= $all_complete ? "1" : "0";
	}

	$binary_result = str_pad($binary_result, 6, "0", STR_PAD_RIGHT);
	return $binary_result;
}

function get_player_bundle_progress(object $bundle_data, array $bundle_progress):array {
	$host_untreated_data = $GLOBALS["untreated_all_players_data"]->player;

	$cc_rooms = [
        "Boiler Room" => "ccBoilerRoom",
		"Crafts Room" => "ccCraftsRoom",
		"Pantry" => "ccPantry",
        "Fish Tank" => "ccFishTank",
		"Vault" => "ccVault",
		"Bulletin Board" => "ccBulletin"
    ];

	$joja_rooms = [
        "Boiler Room" => "jojaBoilerRoom",
		"Crafts Room" => "jojaCraftsRoom",
		"Pantry" => "jojaPantry",
        "Fish Tank" => "jojaFishTank",
		"Vault" => "jojaVault",
		"Bulletin Board" => "JojaMember"
    ];

	$bundle_details = get_player_bundle_details($bundle_data);
	$bundle_details["is_complete"] = false;
	$bundle_details["items_added"] = [];
	
	$bundle_details = [
		"room_name" => $bundle_progress["room_name"]
	] + $bundle_details;

	// Les bundles sont entièrement constitués de "true" si il a été complété SAUF pour les bundles de "Vault"
	$is_bundle_completed = ($bundle_progress["room_name"] !== "Vault") ?
	(
		!in_array("false", $bundle_progress["progress"], true)
		||
		has_element($cc_rooms[$bundle_progress["room_name"]], $host_untreated_data)
		||
		has_element($joja_rooms[$bundle_progress["room_name"]], $host_untreated_data)
	)
	:
	(
		$bundle_progress["progress"][0] === "true"
		||
		has_element($cc_rooms[$bundle_progress["room_name"]], $host_untreated_data)
		||
		has_element($joja_rooms[$bundle_progress["room_name"]], $host_untreated_data)
	);

	if(empty($bundle_details["limit"])) {
		$bundle_details["limit"] = count($bundle_details["requirements"]);
	}

	if($is_bundle_completed) {
		$bundle_details["is_complete"] = true;
		return $bundle_details;
	}

	for($item_in_bundle = 0; $item_in_bundle < count($bundle_details["requirements"]); $item_in_bundle++) {
		if($bundle_progress["progress"][$item_in_bundle] === "true") {
			array_push($bundle_details["items_added"], $bundle_details["requirements"][$item_in_bundle]);
		}
	}

	return $bundle_details;
}

function get_player_bundle_details(object $bundle_data):array {
	$formatted_bundle = explode("/", (string) $bundle_data->value->string);
	$bundle_name = $formatted_bundle[0];
	$bundle_requirements = get_bundle_requirements($formatted_bundle[2]);
	$bundle_limit = $formatted_bundle[4] ?? count($bundle_requirements);
	
	$bundle_details = [
		"bundle_name" => $bundle_name,
		"requirements" => $bundle_requirements,
		"limit" => $bundle_limit
	];

	return $bundle_details;
}

function get_bundle_requirements(string $requirements):array {
	$formatted_requirements = array_chunk(preg_split('/\s+/', $requirements), 3);
	$bundle_requirements = [];
	$item_types = [
		"artifacts"        => sanitize_json_with_version("artifacts"),
		"cooking_recipes"  => sanitize_json_with_version("cooking_recipes"),
		"crafting_recipes" => sanitize_json_with_version("crafting_recipes"),
		"fish"             => sanitize_json_with_version("fish"),
		"minerals"         => sanitize_json_with_version("minerals"),
		"shipped_items"    => sanitize_json_with_version("shipped_items")
	];

	foreach($formatted_requirements as $item) {
		get_correct_id($item[0]);
		$item[0] = abs($item[0]);
		$item_name = ($item[0] === 1) ? "Gold Coins" : get_item_name_by_id($item[0]);

		if($item_name === "None") {
			continue;
		}

		$item_type = "additionnal_items";
		foreach($item_types as $category => $values) {
			if(in_array($item_name, $values)) {
				$item_type = $category;
			}
		}

		$bundle_requirement_item = [
			"id" => $item[0],
			"name" => $item_name,
			"quantity" => $item[1],
			"quality" => $item[2],
			"type" => $item_type
		];

		array_push($bundle_requirements, $bundle_requirement_item);
	}

	return $bundle_requirements;
}

function has_been_donated_in_bundle(string $name, array $donated_items):bool {
	$has_been_donated = false;

	foreach($donated_items as $donated_item) {
		if($name === $donated_item["name"]) {
			$has_been_donated = true;
		}
	}

	return $has_been_donated;
}
<?php

function is_objective_completed(int $current_counter, int $limit):bool {
    return ($current_counter >= $limit);
}

function get_element_completion_percentage(int $max_amount, int $current_amount):float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
}

function does_host_has_element(string $element):int {
	return ($GLOBALS["host_player_data"]["has_element"][$element]["is_found"]);
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

function get_spouse():mixed {
	$data = $GLOBALS["untreated_player_data"];
	return (!empty($data->spouse)) ? $data->spouse : null;
}

function is_this_the_same_day(string $date):bool {
    extract(get_formatted_date(false));
    return $date == "$day/$season";
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
				if((string) $building->buildingType == "Gold Clock") {
                    return true;
                }
			}
		}
	}

	return false;
}

function get_house_upgrade_level():int {
	$data = $GLOBALS["untreated_player_data"];
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

				if((int) $npc->idOfParent == $id) {
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

						if((int) $npc->idOfParent == $id) {
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
			if($location->key->string == $weather_location) {
				if($location->value->LocationWeather->weather->string != "Festival") {
					return formate_text_for_file((string)$location->value->LocationWeather->weather->string);
				}

				if($location->value->LocationWeather->isRaining->string == true) {
					return "rain";
				}

				if($location->value->LocationWeather->isSnowing->string == true) {
					return "snow";
				}

				if($location->value->LocationWeather->isLightning->string == true) {
					return "storm";
				}

				if($location->value->LocationWeather->isGreenRain->string == true) {
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

function get_museum_index(object $general_data):int {
	$index_museum = 0;

	$locations = $general_data->locations->GameLocation;

	foreach($locations as $location) {
		if(isset($location->museumPieces)) {
			break;
		}
		$index_museum++;
	}

	return $index_museum;
}

function get_farmer_level():string {
	$data = $GLOBALS["untreated_player_data"];
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

    $house_level = get_house_upgrade_level();
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
	if((string) $untreated_data->farmPerfect == "true")
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
		if($stardrops_founds == 7) {
			return true;
		}
	}

	return false;
}

function get_junimo_kart_fake_leaderboard():object {
	return (object) [
		"NetLeaderboardsEntry" => [
			(object) [
				"name" => (object) [
					"string" => "Lewis"
				],
				"score" => (object) [
					"int" => 50000
				]
			]
		],
		"NetLeaderboardsEntry" => [
			(object) [
				"name" => (object) [
					"string" => "Shane"
				],
				"score" => (object) [
					"int" => 25000
				]
			]
		],
		"NetLeaderboardsEntry" => [
			(object) [
				"name" => (object) [
					"string" => "Lewis"
				],
				"score" => (object) [
					"int" => 10000
				]
			]
		],
		"NetLeaderboardsEntry" => [
			(object) [
				"name" => (object) [
					"string" => "Lewis"
				],
				"score" => (object) [
					"int" => 5000
				]
			]
		],
		"NetLeaderboardsEntry" => [
			(object) [
				"name" => (object) [
					"string" => "Lewis"
				],
				"score" => (object) [
					"int" => 250
				]
			]
		]
	];
}

function get_museum_pieces_coords(object $data):array {
	$museum_index = get_museum_index($data);
	$in_game_museum_pieces = $data->locations->GameLocation[$museum_index]->museumPieces;

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
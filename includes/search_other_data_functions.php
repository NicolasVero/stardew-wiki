<?php

//& Voir plus deplacer utility functions
function is_objective_completed(int $current_counter, int $limit): bool {
    return ($current_counter >= $limit);
}

//& Voir plus deplacer utility functions
function get_element_completion_percentage(int $max_amount, int $current_amount): float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
}


function has_element_in_mail(string $element): int {
	$player_data = $GLOBALS["untreated_player_data"] ?? $GLOBALS["untreated_all_players_data"]->player;
    return (in_array($element, (array) $player_data->mailReceived->string)) ? 1 : 0;
}

//& Voir plus deplacer utility functions
function has_element(object $element): int {
    return !empty((array) $element);
}

function has_element_based_on_version(string $element_older_version, string $element_newer_version): int {
	$player_data = $GLOBALS["untreated_player_data"];

	if(is_game_older_than_1_6()) {
		return has_element($player_data->$element_older_version);
	}

	return has_element_in_mail($element_newer_version);
}



//& Voir plus deplacer utility functions
function get_game_version_score(string $version): int {
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



function get_total_skills_level(): int {
    $player_data = $GLOBALS["untreated_player_data"];
	$skill_types = [
		"farmingLevel",
		"miningLevel",
		"combatLevel",
		"foragingLevel",
		"fishingLevel"
	];

	$total_levels = 0;
	foreach($skill_types as $skill) {
		$total_levels += $player_data->$skill;
	}

	return $total_levels;
}

function get_pet_frienship_points(): int {
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



//& Voir plus deplacer utility functions
function is_this_the_same_day(string $date): bool {
    extract(get_formatted_date(false));
    return $date === "$day/$season";
}

function get_amount_obelisk_on_map(): int {
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

function is_golden_clock_on_farm(): bool {
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



function get_all_adventurers_guild_categories(): array {
	return $GLOBALS["json"]["adventurer's_guild_goals"];
}

function is_given_to_museum(int $item_id, object $general_data, int $museum_index): int { 

	$museum_items = $general_data->locations->GameLocation[$museum_index]->museumPieces;

	foreach($museum_items->item as $museum_item) {
		$museum_item_id = (is_game_older_than_1_6()) ? (int) $museum_item->value->int : (int) $museum_item->value->string;

		if($item_id === $museum_item_id) {
			return 1;
		}
	}

	return 0;
}

function get_gamelocation_index(object $general_data, string $searched_location): int {
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

function get_junimo_leaderboard(object $junimo_leaderboard): object {
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

function get_museum_pieces_coords(): array {
    $untreated_all_data = $GLOBALS["untreated_all_players_data"];
	$museum_index = get_gamelocation_index($untreated_all_data, "museumPieces");
	$in_game_museum_pieces = $untreated_all_data->locations->GameLocation[$museum_index]->museumPieces;
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

function get_museum_piece_type(string $piece_name): string {
	$artifacts = sanitize_json_with_version("artifacts", true);
	return (in_array($piece_name, $artifacts)) ? "artifacts" : "minerals";
}

function get_cc_binary_hash(array $player_bundles): string {
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

function get_player_bundle_progress(object $bundle_data, array $bundle_progress): array {
	$bundle_details = get_player_bundle_details($bundle_data);
	$bundle_details["is_complete"] = false;
	$bundle_details["items_added"] = [];
	
	$bundle_details = [
		"room_name" => $bundle_progress["room_name"]
	] + $bundle_details;

	if(empty($bundle_details["limit"])) {
		$bundle_details["limit"] = count($bundle_details["requirements"]);
	}

	$is_bundle_completed = is_bundle_completed($bundle_progress["room_name"], $bundle_progress["progress"]);
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

function is_bundle_completed(string $room_name, array $progress): bool {
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

	// Les bundles sont entièrement constitués de "true" si il a été complété SAUF pour les bundles de "Vault"
	$is_bundle_completed = ($room_name !== "Vault") ?
	(
		!in_array("false", $progress, true)
		||
		has_element_in_mail($cc_rooms[$room_name])
		||
		has_element_in_mail($joja_rooms[$room_name])
	)
	:
	(
		$progress[0] === "true"
		||
		has_element_in_mail($cc_rooms[$room_name])
		||
		has_element_in_mail($joja_rooms[$room_name])
	);

	return $is_bundle_completed;
}

function get_player_bundle_details(object $bundle_data): array {
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

function get_bundle_requirements(string $requirements): array {
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
		$item[0] = get_correct_id($item[0]);
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

function has_been_donated_in_bundle(string $name, array $donated_items): bool {
	$has_been_donated = false;

	foreach($donated_items as $donated_item) {
		if($name === $donated_item["name"]) {
			$has_been_donated = true;
		}
	}

	return $has_been_donated;
}

function get_story_quest_data(array $quest): array {
	return [
		"time_limited"	=> false,
		"objective"   	=> $quest["objective"],
		"description" 	=> $quest["description"],
		"title"       	=> $quest["title"],
		"rewards"     	=> $quest["reward"]
	];
}

function get_daily_quest_data(object $quest): array|null {
	$quest_type = (int) $quest->questType;
	$days_left = (int) $quest->daysLeft;
	$rewards = [(int) $quest->reward];
	$target = $quest->target;
	$quest_configs = [
		3 => [
			'goal_name' => fn($quest) => find_reference_in_json(formate_original_data_string($quest->item), "shipped_items"),
			'keyword' => "Deliver",
			'keyword_ing' => "Delivering",
			'number_to_get' => fn($quest) => $quest->number,
			'number_obtained' => fn($quest) => 0,
		],
		4 => [
			'goal_name' => fn($quest) => $quest->monsterName,
			'keyword' => "Kill",
			'keyword_ing' => "Killing",
			'number_to_get' => fn($quest) => $quest->numberToKill,
			'number_obtained' => fn($quest) => $quest->numberKilled,
		],
		5 => [
			'goal_name' => fn() => "people",
			'keyword' => "Talk to",
			'keyword_ing' => "Socializing",
			'number_to_get' => fn($quest) => $quest->total,
			'number_obtained' => fn($quest) => $quest->whoToGreet,
		],
		7 => [
			'goal_name' => fn($quest) => find_reference_in_json(formate_original_data_string($quest->whichFish), "fish"),
			'keyword' => "Fish",
			'keyword_ing' => "Fishing",
			'number_to_get' => fn($quest) => $quest->numberToFish,
			'number_obtained' => fn($quest) => $quest->numberFished,
		],
		10 => [
			'goal_name' => fn($quest) => find_reference_in_json(formate_original_data_string($quest->resource), "shipped_items"),
			'keyword' => "Fish",
			'keyword_ing' => "Fishing",
			'number_to_get' => fn($quest) => $quest->number,
			'number_obtained' => fn($quest) => $quest->numberCollected,
		],
	];

	if (!isset($quest_configs[$quest_type])) {
		return null;
	}
	
	$config = $quest_configs[$quest_type];

	$goal_name = $config['goal_name']($quest);
	$keyword = $config['keyword'];
	$keyword_ing = $config['keyword_ing'];
	$number_to_get = $config['number_to_get']($quest);
	$number_obtained = $config['number_obtained']($quest);

	$title = "$keyword_ing Quest";
	$description = "Help $target with his $keyword_ing request.";
	$objective = "$keyword $number_to_get $goal_name for $target: $number_obtained/$number_to_get";

	return [
		"time_limited"	=> true,
		"objective"   	=> $objective,
		"description" 	=> $description,
		"title"       	=> $title,
		"daysLeft"    	=> $days_left,
		"rewards"     	=> $rewards
	];
}

function get_special_order_data(object $special_order): array|null {
	$special_orders_json = sanitize_json_with_version("special_orders", true);

	if(((string) $special_order->questState) !== "InProgress") {
		return null;
	}

	$is_qi_order = ((string) $special_order->orderType === "Qi");
	$title = ($is_qi_order) ? "QI's Special Order" : "Weekly Special Order";
	$description = $special_orders_json[(string) $special_order->questKey];
	$days_left = (int) $special_order->dueDate - get_number_of_days_ingame();
	
	$target = (string) $special_order->requester;
	$number_to_get = (int) $special_order->objectives->maxCount;
	$number_obtained = (int) $special_order->objectives->currentCount;
	$objective = "$target, $description: $number_obtained/$number_to_get";

	$rewards = [];
	foreach($special_order->rewards as $reward) {
		if(!isset($reward->amount)) {
			continue;
		}

		if($is_qi_order) {
			$rewards[] = (int) $reward->amount->int . "_q";
		} else {
			$rewards[] = ((int) $reward->amount->int) * ((int) $reward->multiplier->float);
		}
	}
		
	return [
		"time_limited"	=> true,
		"objective"   	=> $objective,
		"description" 	=> $description,
		"title"       	=> $title,
		"daysLeft"    	=> $days_left,
		"rewards"     	=> $rewards
	];
}
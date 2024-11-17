<?php

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

function is_objective_completed(int $current_counter, int $limit):bool {
    return ($current_counter >= $limit);
}

function is_this_the_same_day(string $date):bool {
    extract(get_formatted_date(false));
    return $date == "$day/$season";
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

function get_element_completion_percentage(int $max_amount, int $current_amount):float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
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

function get_child_tooltip(string $spouse, array $children):string {
	$gender = get_the_married_person_gender($spouse);
	$children_count = count($children);
	$children_names = ($children_count == 1) ? $children[0] : implode(" and ", $children);
	$nombre = ($children_count > 1) ? "children" : "child";

	if($children_count == 0) {   
        return "With $gender $spouse, haven't yet had $nombre";
    }

	return "With $gender $spouse, you had $children_count $nombre : $children_names";
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

function get_perfection_max_elements():array {
	return $GLOBALS["json"]["perfection_elements"];
}
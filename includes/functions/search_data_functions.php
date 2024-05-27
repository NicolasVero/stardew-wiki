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
   
    $datas = array();
    
    foreach($achievements->int as $achievement) {
        $json_data = find_reference_in_json((int) $achievement, 'achievements_details');
        $achievement_title = explode('µ', $json_data)[0]; 
        $achievement_description = explode('µ', $json_data)[1]; 

        $datas[$achievement_title] = array(
            'description' => $achievement_description
        );
    }
    
    return $datas;
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
			return ($is_older_version) ? has_element_ov($player_data->canUnderstandDwarves) : has_element("HasDwarvishTranslationGuide", $player_data);
			break;

		case "rusty_key":
			return ($is_older_version)
				? has_element_ov($player_data->hasRustyKey) :
					((isset($GLOBALS['host_data']))
						? does_host_has_element("rusty_key") : has_element("HasRustyKey", $player_data));
			break;
		case "club_card":
			return ($is_older_version) ? has_element_ov($player_data->hasClubCard) 			: has_element("HasClubCard", $player_data);
			break;

		case "special_charm":
			return ($is_older_version) ? has_element_ov($player_data->hasSpecialCharm) 		: has_element("HasSpecialCharm", $player_data);
			break;

		case "skull_key":
			return ($is_older_version)
				? has_element_ov($player_data->hasSkullKey) :
					((isset($GLOBALS['host_data']))
						? does_host_has_element("skull_key") : has_element("HasSkullKey", $player_data));
			break;

		case "magnifying_glass":
			return ($is_older_version) ? has_element_ov($player_data->hasMagnifyingGlass) 	: has_element("HasMagnifyingGlass", $player_data);
			break;

		case "dark_talisman":
			return ($is_older_version) ? has_element_ov($player_data->hasDarkTalisman) 		: has_element("HasDarkTalisman", $player_data);
			break;

		case "magic_ink":
			return ($is_older_version) ? has_element_ov($player_data->hasMagicInk) 			: has_element("HasPickedUpMagicInk", $player_data);
			break;

		case "town_key":
			return ($is_older_version) ? has_element_ov($player_data->HasTownKey) 			: has_element("HasTownKey", $player_data);
			break;
	}
}

function get_shipped_items(object $items, string $filename):array {

    $version_score = $GLOBALS['game_version_score'];
    $datas = array();

    foreach($items->item as $item) {

        if($version_score < get_game_version_score("1.6.0")) 
            $item_id = formate_original_data_string($item->key->int);
		else 
            $item_id = formate_original_data_string($item->key->string);
        

        if(!ctype_digit($item_id))
            $item_id = get_custom_id($item_id);

        $reference = find_reference_in_json($item_id, $filename);
        
        if(!empty($reference)) {

            $datas[$reference] = array(
                'id' => $item_id
            );
        }
    }
    
    return $datas;
}






function find_reference_in_json(mixed $id, string $file):mixed {
    //& Changer file_get_contents en curl -> problème de pare-feu en hébergé
    $json_file = sanitize_json_with_version($file);

    return isset($json_file[$id]) ? $json_file[$id] : null;
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

function get_game_duration(int $duration):string {

    $totalSeconds = intdiv($duration, 1000);
    $seconds      = $totalSeconds % 60;
    $totalMinutes = intdiv($totalSeconds, 60);
    $minutes      = $totalMinutes % 60;
    $hours        = intdiv($totalMinutes, 60);

    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

function get_enemies_killed_data(object $data):array { 
    $enemies = array();
    
    foreach($data->specificMonstersKilled->item as $item) {
        $enemies[(string) $item->key->string] = array(
            'id'             => get_custom_id((string) $item->key->string),
            'killed_counter' => (int) $item->value->int
        );
    }
    
    return $enemies;
}

function get_item_list_string(object $data, string $filename):array {
    
    $version_score = $GLOBALS['game_version_score'];

    if($version_score < get_game_version_score("1.6.0")) 
        return array();
    
    $items = array();

    foreach($data->item as $item) {

        $item_id = formate_original_data_string($item->key->string);

        if(!ctype_digit($item_id)) 
            $item_id = get_custom_id($item_id);

        $reference = find_reference_in_json($item_id, $filename);

        if(empty($reference)) 
            continue;

        $items[$reference] = array(
            'id' => $item_id
        );
    }
    
    return $items;
}

function get_fish_caught(object $data):array {
    
    $version_score = $GLOBALS['game_version_score'];
    $fishs = array();

    foreach($data->item as $item) {

        if($version_score < get_game_version_score("1.6.0")) 
            $item_id = formate_original_data_string($item->key->int);
        else 
            $item_id = formate_original_data_string($item->key->string);


        if(!ctype_digit($item_id)) 
            $item_id = get_custom_id($item_id);

        $values_array = (array) $item->value->ArrayOfInt->int;
        $index = find_reference_in_json(
            $item_id,
            'fish'
        );

        if(empty($index) || $index == "") 
            continue;
        
        $fishs[$index] = array(
            'id'             => (int) $item_id,
            'caught_counter' => (int) $values_array[0],
            'max_length'     => (int) $values_array[1]
        );
    }

    return $fishs;
}


function get_friendship_data(object $data):array { 
    $friends = array();
    $json_villagers = sanitize_json_with_version('villagers');
    $json_birthday = decode('villagers_birthday');

    foreach($data->item as $item) {
        
        $friend_name = (string) $item->key->string;

        if(!in_array($friend_name, $json_villagers)) continue;

        $friends[$friend_name] = array(
            'id'                => get_custom_id($friend_name),
            'points'            => (int) $item->value->Friendship->Points,
            'friend_level'      => (int) floor(($item->value->Friendship->Points) / 250),
            'birthday'          => $json_birthday[get_custom_id($friend_name)],
            'status'            => (string) $item->value->Friendship->Status,
            'week_gifts'        => (int) $item->value->Friendship->GiftsThisWeek,
            'talked_to_today'   => (int) $item->value->Friendship->TalkedToToday
        );
    }

    uasort($friends, function ($a, $b) {
        return $b['points'] - $a['points'];
    });

    return $friends; 
}


function get_quest_log(object $data):array {
    $quests = array();

    foreach($data->Quest as $item) {
        $quest_id = (int) $item->id;
        $index = find_reference_in_json(
            $quest_id,
            'quests'
        );

		// Quêtes histoire
		if (!empty($index)){
			$quests[] = array(
				'daily'		  => false,
				'objective'   => $index['objective'],
				'description' => $index['description'],
				'title'       => $index['title'],
				'rewards'     => $index['reward']
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
			$objective = "$keyword $number_to_get $goal_name for $target : $number_obtained/$number_to_get";
			$quests[] = array(
				'daily'		  => true,
				'objective'   => $objective,
				'description' => $description,
				'title'       => $title,
				'daysLeft'    => $days_left,
				'rewards'     => $rewards
			);
		}
    }

    return $quests;
}


function get_formatted_date(object $data, bool $display_date = true):mixed {

    $day    = $data->dayOfMonthForSaveGame;
    $season = array('spring', 'summer', 'fall', 'winter')[$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    if($display_date)
        return "Day $day of $season, Year $year";

    return array(
        'day' => $day,
        'season' => $season,
        'year' => $year
    );
}

function get_crafting_recipes(object $recipes):array {

    $version_score = $GLOBALS['game_version_score'];
    $return_datas = array();
    $json_recipes = sanitize_json_with_version('crafting_recipes');

    foreach($recipes->item as $recipe) {

        $item_name = formate_original_data_string($recipe->key->string);
        $index = array_search($item_name, $json_recipes);

		$return_datas[$item_name] = array(
			'id' => $index,
			'counter' => (int) $recipe->value->int
		);
    }
    
    return $return_datas;
}

function get_cooking_recipes(object $recipes, object $recipes_cooked):array {

    $version_score = $GLOBALS['game_version_score'];
    $return_datas = array();
    $json_recipes = sanitize_json_with_version('cooking_recipes');

    foreach($recipes->item as $recipe) {

        $item_name = formate_original_data_string($recipe->key->string);
        $index = array_search($item_name, $json_recipes);            

        foreach($recipes_cooked->item as $recipe_cooked) {

            if($version_score < get_game_version_score("1.6.0"))
                $recipe_id = (int) $recipe_cooked->key->int;
            else
                $recipe_id = (int) $recipe_cooked->key->string;

            if($recipe_id == $index) {
                $return_datas[$item_name] = array(
                    'id'      => $recipe_id,
                    'counter' => (int) $recipe_cooked->value->int
                );
                break;
            }
            else {
                $return_datas[$item_name] = array(
                    'id'      => $recipe_id,
                    'counter' => 0
                );
            }
            
        }
    }
    
    return $return_datas;
}

function get_artifacts(object $artifacts, object $general_data):array {

    $version_score = $GLOBALS['game_version_score'];
    $datas = array();

    foreach($artifacts->item as $artifact) {

        if($version_score < get_game_version_score("1.6.0")) 
            $artifact_id = formate_original_data_string($artifact->key->int);
        else 
            $artifact_id = formate_original_data_string($artifact->key->string);

        if(!ctype_digit($artifact_id)) 
            $artifact_id = get_custom_id($artifact_id);

        $reference = find_reference_in_json($artifact_id, 'artifacts');
        
        $museum_index = get_museum_index($general_data);

        if(!empty($reference)) {
            $datas[$reference] = array(
                'id'      => $artifact_id,
                'counter' => is_given_to_museum($artifact_id, $general_data, $museum_index, $version_score)
            );
        }
    }
    
    return $datas;
}

function get_minerals(object $minerals, object $general_data):array {
    
    $version_score = $GLOBALS['game_version_score'];
    $datas = array();

    foreach($minerals->item as $mineral) {

        if($version_score < get_game_version_score("1.6.0")) 
            $mineral_id = formate_original_data_string($mineral->key->int);
        else 
            $mineral_id = formate_original_data_string($mineral->key->string);


        if(!ctype_digit($mineral_id)) 
            $mineral_id = get_custom_id($mineral_id);

        $reference = find_reference_in_json($mineral_id, 'minerals');
        
        $museum_index = get_museum_index($general_data);

        if(!empty($reference)) {
            $datas[$reference] = array(
                'id'      => $mineral_id,
                'counter' => is_given_to_museum($mineral_id, $general_data, $museum_index, $version_score)
            );
        }
    }
    
    return $datas;
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

    foreach ($locations as $location) {
        if (isset($location->museumPieces))
            break;
        $index_museum++;
    }

    return $index_museum;
}
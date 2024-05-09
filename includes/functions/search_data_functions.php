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
        $achievement_title = explode(':', $json_data)[0]; 
        $achievement_description = explode(':', $json_data)[1]; 

        $datas[$achievement_title] = array(
            'description' => $achievement_description
        );
    }
    
    return $datas;
}


function get_item_list(object $items, string $filename, int $version_score):array {
    $datas = array();

    foreach($items->item as $item) {

        if($version_score < get_game_version_score("1.6.0")) 
            $item_id = formate_original_data_string($item->key->int);
		else 
            $item_id = formate_original_data_string($item->key->string);
        

        if(!ctype_digit($item_id))
            $item_id = get_custom_id($item_id);

        $reference = find_reference_in_json($item_id, $filename);
        
        if(!empty($reference))
            $datas[] = $reference;
    }
    
    return $datas;
}






function find_reference_in_json(mixed $id, string $file):mixed {
    //& Changer file_get_contents en curl -> problème de pare-feu en hébergé
    $json_file = json_decode(file_get_contents(get_json_folder() . $file . '.json'), true);

    return isset($json_file[$id]) ? $json_file[$id] : null;
}


function get_skills_data(array $skills):array {
    $json_skills = json_decode(file_get_contents(get_json_folder() . 'skills.json'), true);
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
            'killed_counter' => (int) $item->value->int
        );
    }
    
    return $enemies;
}

function get_item_list_string(object $data, string $filename, int $version_score):array {
    
    if($version_score < get_game_version_score("1.6.0")) 
        return array();
    
    $items = array();

    foreach($data->item as $item) {

        $item_id = formate_original_data_string($item->key->string);
        $reference = find_reference_in_json($item_id, $filename);

        if(empty($reference)) 
            continue;

        $items[] = $reference;
    }
    
    return $items;
}

function get_fish_caught_data(object $data, int $version_score):array {
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
            'caught_counter' => (int) $values_array[0],
            'max_length'     => (int) $values_array[1]
        );
    }

    return $fishs;
}


function get_friendship_data(object $data):array { 
    $friends = array();
    $json_villagers = json_decode(file_get_contents(get_json_folder() . 'villagers.json'), true);

    foreach($data->item as $item) {
        
        $friend_name = (string) $item->key->string;

        if(!in_array($friend_name, $json_villagers['villagers'])) continue;

        $friends[$friend_name] = array(
            'points'            => (int) $item->value->Friendship->Points,
            'friend_level'      => (int) floor(($item->value->Friendship->Points) / 250),
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


function get_formatted_date(object $data):string {

    $day    = $data->dayOfMonthForSaveGame;
    $season = array('spring', 'summer', 'fall', 'winter')[$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    return "Day $day of $season, Year $year";
}


function get_cooking_recipes(object $recipes, object $recipes_cooked, int $version_score):array {

    $return_datas = array();
    $json_recipes = json_decode(file_get_contents(get_json_folder() . 'recipes.json'), true);

    foreach($recipes->item as $recipe) {

        $item_name = formate_original_data_string($recipe->key->string);
        $index = array_search($item_name, $json_recipes);            

        foreach($recipes_cooked->item as $recipe_cooked) {

            if($version_score < get_game_version_score("1.6.0"))
                $recipe_id = (int) $recipe_cooked->key->int;
            else
                $recipe_id = (int) $recipe_cooked->key->string;

            if($recipe_id == $index) {
                $return_datas[$item_name] = array('counter' => (int) $recipe_cooked->value->int);
                break;
            }
            else 
                $return_datas[$item_name] = array('counter' => 0);
            
        }
    }
    
    return $return_datas;
}

function get_artifacts(object $artifacts, object $general_data, int $version_score):array {
    $datas = array();

    foreach($artifacts->item as $artifact) {

        if($version_score < get_game_version_score("1.6.0")) 
            $artifact_id = formate_original_data_string($artifact->key->int);
        else 
            $artifact_id = formate_original_data_string($artifact->key->string);

        if(!ctype_digit($artifact_id)) 
            $artifact_id = get_custom_id($artifact_id);

        $reference = find_reference_in_json($artifact_id, 'artifacts');
        

        if(!empty($reference))
            $datas[$reference] = array('counter' => is_given_to_museum($artifact_id, $general_data, $version_score));
    }
    
    return $datas;
}

function get_minerals(object $minerals, object $general_data, $version_score):array {
    $datas = array();

    foreach($minerals->item as $mineral) {

        if($version_score < get_game_version_score("1.6.0")) 
            $mineral_id = formate_original_data_string($mineral->key->int);
        else 
            $mineral_id = formate_original_data_string($mineral->key->string);


        if(!ctype_digit($mineral_id)) 
            $mineral_id = get_custom_id($mineral_id);

        $reference = find_reference_in_json($mineral_id, 'minerals');
        

        if(!empty($reference))
            $datas[$reference] = array('counter' => is_given_to_museum($mineral_id, $general_data, $version_score));
    }
    
    return $datas;
}

function is_given_to_museum(int $item_id, object $general_data, int $version_score):int { 

    $location_index = ($version_score < get_game_version_score("1.6.0")) ? 31 : 32;
    $museum_items = $general_data->locations->GameLocation[$location_index]->museumPieces;

    foreach($museum_items->item as $museum_item) {
        if($version_score < get_game_version_score("1.6.0")) {
            if($item_id == (int) $museum_item->value->int) return 1;
        } else {
            if($item_id == (int) $museum_item->value->string) return 1;
        }
    }

    return 0;
}
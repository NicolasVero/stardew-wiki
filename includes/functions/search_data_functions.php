<?php 


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


function get_item_list(object $items, string $filename):array {
    $datas = array();

    foreach($items->item as $item) {
		//& Anciennes versions ($item->key->int) sauf cookingRecipes ($item->key-string)
        $item_id = formate_original_data_string($item->key->string);

        if(!ctype_digit($item_id)) 
            $item_id = get_custom_id($item_id);

        $reference = find_reference_in_json($item_id, $filename);
        
        if(!empty($reference))
            $datas[] = $reference;
    }
    
    return $datas;
}






function find_reference_in_json(int $id, string $file):mixed {
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

function get_fish_caught_data(object $data):array {
    $fishs = array();

    foreach($data->item as $item) {

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
            'points'       => (int) $item->value->Friendship->Points,
            'friend_level' => (int) floor(($item->value->Friendship->Points) / 250),
            'status'       => (string) $item->value->Friendship->Status,
            'week_gifts'   => (int) $item->value->Friendship->GiftsThisWeek
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
        
        $quests[] = array(
            'objective'   => $index['objective'],
            'description' => $index['description'],
            'title'       => $index['title'],
            'rewards'     => $index['reward']
        );
    }

    return $quests;
}


function get_formatted_date(object $data):string {

    $day    = $data->dayOfMonthForSaveGame;
    $season = array('spring', 'summer', 'fall', 'winter')[$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    return "Day $day of $season, Year $year";
}


function get_cooking_recipes(object $recipes, object $recipes_cooked) {

    $return_datas = array();
    $json_recipes = json_decode(file_get_contents(get_json_folder() . 'recipes.json'), true);

    foreach($recipes->item as $recipe) {
        $item_name = formate_original_data_string($recipe->key->string);
        $index = array_search($item_name, $json_recipes);

        foreach($recipes_cooked->item as $recipe_cooked) {
            if ((int) $recipe_cooked->key->string == $index)
                $return_datas[$item_name] = array('counter' => (int) $recipe_cooked->value->int);
            else
                $return_datas[$item_name] = array('counter' => 0);
        }
    }
    
    return $return_datas;
}

function get_artifacts(object $artifacts, object $general_data):array {
    $datas = array();

    foreach($artifacts->item as $artifact) {
		//& Anciennes versions ($item->key->int) sauf cookingRecipes ($item->key-string)
        $artifact_id = formate_original_data_string($artifact->key->string);

        if(!ctype_digit($artifact_id)) 
            $artifact_id = get_custom_id($artifact_id);

        $reference = find_reference_in_json($artifact_id, 'artifacts');
        

        if(!empty($reference))
            $datas[$reference] = array('counter' => is_given_to_museum($artifact_id, $general_data));
    }
    
    return $datas;
}

function get_minerals(object $minerals, object $general_data):array {
    $datas = array();

    foreach($minerals->item as $mineral) {
		//& Anciennes versions ($item->key->int) sauf cookingRecipes ($item->key-string)
        $mineral_id = formate_original_data_string($mineral->key->string);

        if(!ctype_digit($mineral_id)) 
            $mineral_id = get_custom_id($mineral_id);

        $reference = find_reference_in_json($mineral_id, 'minerals');
        

        if(!empty($reference))
            $datas[$reference] = array('counter' => is_given_to_museum($mineral_id, $general_data));
    }
    
    return $datas;
}

function is_given_to_museum(int $item_id, object $general_data):int {   
    $museum_items = $general_data->locations->GameLocation[32]->museumPieces;

    foreach($museum_items->item as $museum_item) {
        if($item_id == (int) $museum_item->value->string) return 1;
    }

    return 0;
}
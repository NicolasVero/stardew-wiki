<?php

function load_save($save_file, $use_ajax = true):mixed {
    $uploadedFile = $save_file;
    $data = simplexml_load_file($uploadedFile);
    load_all_json();

    $GLOBALS["untreated_all_players_data"] = $data;
    $GLOBALS["game_version"] = $data->gameVersion;
	$GLOBALS["game_version_score"] = (int) get_game_version_score((string) $data->gameVersion);;
	$GLOBALS["should_spawn_monsters"] = (string) $data->shouldSpawnMonsters;
    $GLOBALS["shared_players_data"] = get_shared_aggregated_data($data->player);

    $players_data = get_all_players_data();
    $GLOBALS["players_names"] = get_players_name();
    $pages["sur_header"] = display_sur_header(false, false);

    
    for($player_count = 0; $player_count < count($players_data); $player_count++) {
        $GLOBALS["player_id"] = $player_count;
        $pages["player_" . $player_count] = "
            <div class='player_container player_{$player_count}_container'>" . 
                display_page() . "
            </div>
        ";
    }

    if($use_ajax) {
        return [
            "players" => $GLOBALS["players_names"],
            "html" => $pages,
            "code" => "success"
        ];
    } else {
        $structure = display_landing_page(false);

        foreach($pages as $page) {
            $structure .= $page;
        }
        
        $structure .= get_script_loader();
        echo $structure;
    }
    
    return true;
}

function get_farmhands():array {
    $data = $GLOBALS["untreated_all_players_data"];
    $all_farmhands = [];

    if(is_game_older_than_1_6()) {
        foreach($data->locations->GameLocation as $game_location) {
            if(!isset($game_location->buildings)) {
                continue;
            }

            foreach($game_location->buildings->Building as $building) {
                if(!isset($building->indoors->farmhand) || (string) $building->indoors->farmhand->name === "") {
                    continue;
                }

                $farmhand = $building->indoors->farmhand;
                array_push($all_farmhands, $farmhand);
            }
        }
    } else {
        if(empty($data->farmhands) || (string) $data->farmhands->name === "") {
            return [];
        }

        foreach($data->farmhands->Farmer as $farmhand) {
            array_push($all_farmhands, $farmhand);
        }
    }

    return $all_farmhands;
}

function get_all_players_data():array {
    $players_data = [];
    $data = $GLOBALS["untreated_all_players_data"];
    array_push($players_data, get_aggregated_data($data->player));
	$GLOBALS["host_player_data"] = $players_data[0];

    $farmhands = get_farmhands();

    foreach($farmhands as $farmhand) {
        array_push($players_data, get_aggregated_data($farmhand));
    }

	$GLOBALS["all_players_data"] = $players_data;
    return $players_data;
}

function get_aggregated_data(object $data):array {
    $general_data = $GLOBALS["untreated_all_players_data"];
	$GLOBALS["untreated_player_data"] = $data;
    
    return [
        "general" => [
            "id"                    => (int) $data->UniqueMultiplayerID,
            "name"                  => (string) $data->name,
            "gender"                => get_player_gender([$data->gender, $data->isMale]),
            "farm_name"             => (string) $data->farmName,
            "farmer_level"          => get_farmer_level($data),
            "favorite_thing"        => (string) $data->favoriteThing,
            "pet"                   => get_player_pet($data),
            "spouse"                => get_spouse($data),
            "children"              => get_children_amount((int) $data->UniqueMultiplayerID),
            "house_level"           => get_house_upgrade_level($data),
            "date"                  => get_formatted_date(),
            "game_duration"         => get_game_duration((int) $data->millisecondsPlayed),
            "mine_level"            => (int) $data->deepestMineLevel,
            "max_items"             => (int) $data->maxItems,
            "max_health"            => (int) $data->maxHealth,
            "max_stamina"           => (int) $data->maxStamina,
            "grandpa_score"         => get_grandpa_score(),
            "golds"                 => (int) $data->money,
            "total_golds"           => (int) $data->totalMoneyEarned,
            "golden_walnuts"        => (int) $general_data->goldenWalnutsFound,
            "qi_gems"               => (int) $data->qiGems,
            "casino_coins"          => (int) $data->clubCoins,
            "raccoons"              => (int) $general_data->timesFedRaccoons,
            "stardrops_found"       => get_player_stardrops_found((int) $data->maxStamina)
        ],
        "levels" => [
            "farming_level"  => (int) $data->farmingLevel,
            "mining_level"   => (int) $data->miningLevel,
            "combat_level"   => (int) $data->combatLevel,
            "foraging_level" => (int) $data->foragingLevel,
            "fishing_level"  => (int) $data->fishingLevel,
        ],
        "unlockables"       => get_player_unlockables_list(),
        "crafting_recipes"  => get_player_crafting_recipes($data->craftingRecipes),
        "books"             => get_player_books($data->stats->Values),
        "masteries"         => get_player_masteries($data->stats->Values),
        "fish_caught"       => get_player_fishes_caught($data->fishCaught),
        "artifacts_found"   => get_player_artifacts($data->archaeologyFound, $general_data),
        "minerals_found"    => get_player_minerals($data->mineralsFound, $general_data),
        "cooking_recipes"   => get_player_cooking_recipes($data->cookingRecipes, $data->recipesCooked),
        "shipped_items"     => get_player_shipped_items($data->basicShipped),
        "achievements"      => get_player_achievements($data->achievements),
        "skills"            => get_player_skills_data((array) $data->professions->int),
        "friendship"        => get_player_friendship_data($data->friendshipData),
        "enemies_killed"    => get_player_enemies_killed_data($data->stats),
        "quest_log"         => get_player_quest_log($data->questLog),
        "secret_notes"      => get_player_secret_notes($data->secretNotesSeen)
    ];
}

function get_shared_aggregated_data(object $data):array {
    $general_data = $GLOBALS["untreated_all_players_data"];

    return [
        "farm_animals"          => get_player_farm_animals(),
        "weather"               => get_weather(),
        "jumino_kart"           => get_jumino_kart_leaderboard(),
        "museum_coords"         => get_museum_pieces_coords($general_data),
        "locations_visited"     => get_player_visited_locations($data),
        "cc_bundles"            => get_player_bundles($general_data)
    ];
}
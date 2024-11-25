<?php

function display_panels():string {
	$structure = display_friendships();
	$structure .= display_quest_panel();
	$structure .= display_monster_eradication_goals_panel();
	$structure .= display_calendar_panel();
	$structure .= display_farm_animals_panel();
	$structure .= display_junimo_kart_panel();
	$structure .= display_museum_panel();

    return $structure;
}
// [] --> *icon, *value, tooltip, alt, label, wiki_link
function display_stat(array $parameters):string {
    extract($parameters);
    $images_path = get_images_folder();
    $formatted_icon = formate_text_for_file($icon);
    $formatted_value = filter_var($value, FILTER_VALIDATE_INT) ? formate_number($value) : $value;
    $alt = $alt ?? $icon;
    $label = $label ?? $icon;
    $image = "<img src='{$images_path}icons/$formatted_icon.png' alt='$alt' />";

    if(isset($tooltip)) {
        $image = "
            <span class='tooltip'>
                $image
                <span>$tooltip</span>
            </span>
        ";
    }

    $image_field = "
        <span>
            $image
            <span class='data $formatted_icon'>$formatted_value</span>
            <span class='data-label'>$label</span>
        </span>
    ";

    if(isset($wiki_link)) {
        return "
            <a class='wiki_link' href='https://stardewvalleywiki.com/$wiki_link' target='_blank'>
                $image_field
            </a>
        ";
    }

    return $image_field;
}

function display_spouse(mixed $spouse, array $children):string {
    if(empty($spouse)) {
        return "";
    }

    $images_path = get_images_folder();
    return "
        <span>
            <span class='tooltip'>
                <a class='wiki_link' href='https://stardewvalleywiki.com/Children' target='_blank'>
                    <img src='{$images_path}characters/" . lcfirst($spouse) . ".png' alt='$spouse'/>
                </a>
                <span> " . get_child_tooltip($spouse, $children) . "</span>
            </span>
            <span class='data data-family'>" . count($children) . "</span>
            <span class='data-label'>" . ((count($children) > 1) ? 'children' : 'child') . "</span>
        </span>
    ";
}

function display_sur_header(bool $is_landing_page = false, bool $is_error_screen = false):string {
	$menu_id = ($is_landing_page) ? "landing_menu" : (($is_error_screen) ? "error_menu" : "dashboard_menu");
	$save_id = ($is_landing_page) ? "landing" : "file";
	$settings_id = ($is_landing_page) ? "landing" : "main";
	
	$structure = "<div id='$menu_id' class='sur-header'>";
		$structure .= (!$is_landing_page && !$is_error_screen) ? display_player_selection() : "";
		$structure .= "<span>";
			$structure .= (!$is_landing_page && !$is_error_screen) ? display_game_version() : "";
			$structure .= display_save_button($save_id);
			$structure .= display_settings_button($settings_id);
			$structure .= display_feedback_button();
			$structure .= (!$is_landing_page && !$is_error_screen) ? display_home_button() : "";
		$structure .= "</span>";
	$structure .= "</div>";

    return $structure;
}

function display_header():string {
	$player_id = $GLOBALS["player_id"];
	$all_players_data = $GLOBALS["all_players_data"][$player_id]["general"];
	$festival_icon = display_festival_icon();
    $weather_icon = display_weather_icon();
    
    extract($all_players_data);  

    $images_path = get_images_folder();
	$farm_name = str_contains(strtolower($farm_name), "farm") ? $farm_name : $farm_name . " farm";
	$gender = ($gender == null) ? "neutral" : $gender;

    $structure = "
        <header>
            <div class='header'>
                <span class='player'>
                    <img src='{$images_path}icons/" . $pet['type'] . "_" . $pet['breed'] . ".png' alt='Pet type'/>
                    <img src='{$images_path}icons/" . strtolower($gender) . ".png' alt='Gender logo: $gender' class='player_gender_logo'/>
                    <span class='data player_name'>" . formate_usernames($name) . "<span class='data-label'> $farmer_level at $farm_name</span></span>
                </span>

                <span class='date'>
                    $weather_icon
                    <span class='data date-in-game view-calendar-$player_id modal-opener'>$date</span>
					$festival_icon
                </span>

                <span class='game_time'>
                    <span class='data time-in-game'>$game_duration</span>
                    <span class='data-label'>time in game</span>
                </span>
            </div>

            <div class='sub-header'>
                <span class='all-money'>" 
                    .
                    display_stat([
                        "icon" => "Gold coins", "value" => $golds, "wiki_link" => "Gold"
                    ])
                    .
                    display_stat([
                        "icon" => "Golden Walnuts", "value" => $golden_walnuts, "wiki_link" => "Golden_Walnut", "tooltip" => "$golden_walnuts / 130 golden walnuts found"
                    ])
                    .
                    display_stat([
                        "icon" => "Qi gems", "value" => $qi_gems, "wiki_link" => "Qi_Gem"
                    ])
                    .
                    display_stat([
                        "icon" => "Casino coins", "value" => $casino_coins, "wiki_link" => "Casino"
                    ])
                . "</span>
                <span class='perfection-stats'> ".
                    display_stat([
                        "icon" => "Grandpa", "alt" => "GrandPa candles", "label" => "candles lit", "value" => get_candles_lit($grandpa_score), "wiki_link" => "Grandpa", "tooltip" => "Number of candles lit on the altar ($grandpa_score points)"
                    ])
                    .
                    display_stat([
                        "icon" => "Stardrop", "alt" => "Perfection", "label" => "perfection progression", "value" => get_perfection_percentage() . "%", "wiki_link" => "Perfection"
                    ])
                . "</span>
            </div>
        </header>
    ";

    return $structure;
}

function display_general_stats():string {
	$player_id = $GLOBALS["player_id"];
	$all_players_data = $GLOBALS["all_players_data"][$player_id]["general"];
	$junimo_kart_button = display_junimo_kart_button();
	$quest_button = display_quest_button();

    extract($all_players_data);

    $images_path = get_images_folder();
    $max_mine_level = 120;
    $deepest_mine_level = ($mine_level > $max_mine_level) ? $max_mine_level : $mine_level; 
    $deepest_skull_mine_level = ($mine_level - $max_mine_level < 0) ? 0 : $mine_level - $max_mine_level;
    $deepest_mine_level_tooltip = "$deepest_mine_level floors in the Stardew Mine" . (($deepest_skull_mine_level > 0) ? " & $deepest_skull_mine_level floors in the Skull Mine" : "");

    return "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>General stats</h2>
            $junimo_kart_button
			$quest_button
            <div>" .
                display_stat([
                    "icon" => "Energy", "label" => "max energy", "value" => $max_stamina, "wiki_link" => "Energy", "tooltip" => "$stardrops_found / 7 stardrops found" 
                ])
                .
                display_stat([
                    "icon" => "Health", "label" => "max health", "value" => $max_health, "wiki_link" => "Health"
                ])
                .
                display_stat([
                    "icon" => "Inventory", "label" => "inventory spaces", "value" => $max_items, "wiki_link" => "Inventory"
                ])
                .
                display_stat([
                    "icon" => "Mine level", "label" => "deepest mine level", "value" => $mine_level, "wiki_link" => "The_Mines", "tooltip" => $deepest_mine_level_tooltip
                ])
                .
                display_spouse($spouse, $children)
                .
                display_stat([
                    "icon" => "House", "alt" => "House upgrades", "label" => "upgrades done", "value" => $house_level, "wiki_link" => "Farmhouse", "tooltip" => "$house_level / 3 improvements"
                ])
                .
                display_stat([
                    "icon" => "Raccoons", "label" => "raccoons helped", "value" => $raccoons, "wiki_link" => "Giant_Stump", "tooltip" => "$raccoons / 10 missions for the raccoon family"
                ])
            . "</div>
        </section>
    ";
}

function display_skills():string {
	$this_player_data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
	$this_player_skills = $this_player_data["skills"];
	$this_player_skills_levels = $this_player_data["levels"];
	$this_player_masteries = $this_player_data["masteries"];

    $structure = "
		<section class='skills-section info-section'>
			<h2 class='section-title'>Skills</h2>
            <span>
    ";

    $mastery_visible_class = (empty($this_player_masteries)) ? "" : "not-hide";

    foreach($this_player_skills_levels as $key => $level) {
        $level_icon_name = explode('_', $key)[0];
        $level_icon      = get_images_folder() . "skills/$level_icon_name.png";
        $mastery_icon    = get_images_folder() . "skills/mastery.png";
        $mastery_class   = (array_key_exists(ucfirst(explode('_', $key)[0]) . " Mastery", $this_player_masteries)) ? 'found' : 'not-found';
        $mastery_tooltip = ucfirst(explode('_', $key)[0]) . " mastery";
        $is_newer_version_class = (is_game_older_than_1_6()) ? "newer-version" : "older-version";

        $structure .= "
            <span class='skill $key'>
                <span class='tooltip'>
                    <a class='wiki_link' href='https://stardewvalleywiki.com/Mastery_Cave' target='_blank'>
                        <img src='$mastery_icon' class='level-icon $mastery_class $mastery_visible_class $is_newer_version_class' alt='$key'/>
                    </a>
                    <span>" . ucfirst($mastery_tooltip) . "</span>
                </span>
        
                <span class='tooltip'>
                    <a class='wiki_link' href='https://stardewvalleywiki.com/Skills#" . ucfirst($level_icon_name) . "' target='_blank'>
                        <img src='$level_icon' class='level-icon' alt='$key'/>
                    </a>
                    <span>" . ucfirst($level_icon_name) . "</span>
                </span>
                " . get_level_progress_bar($level) . "
                <span class='level data'>$level</span>
                <span>
                    <a class='wiki_link' href='https://stardewvalleywiki.com/Skills' target='_blank'>" 
                        . get_skills_icons($this_player_skills, $level_icon_name) . "
                    </a>
                </span>
            </span>
        ";
    }

    $structure .= "
            </span>
        </section>
    ";

    return $structure;
}

function display_top_friendships(int $limit = 5):string {
    return display_friendships($limit);
}

function display_friendships(int $limit = -1): string {
    $player_id = $GLOBALS["player_id"];
    $friendship_data = $GLOBALS["all_players_data"][$player_id]["friendship"];
    $images_path = get_images_folder();
    
    $marriables_npc = sanitize_json_with_version("marriables");
    $villagers_json = sanitize_json_with_version("villagers");
    $birthday_json = sanitize_json_with_version("villagers_birthday");
    $json_with_version = sanitize_json_with_version("villagers", true);
    
    $section_class = ($limit == -1) ? "all-friends" : "top-friends";
    $view_all = ($limit == -1) ? "" : "<span class='view-all-friends view-all-friendships-$player_id modal-opener'>- View all friendships</span>";
    $structure = ($limit == -1)
        ? "
        <section class='info-section friends-section $section_class $section_class-$player_id modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Friendship progression</h2>
                <img src='{$images_path}icons/exit.png' class='exit-all-friendships-$player_id exit' alt=''/>
            </div>
            <span class='friendlist'>
        "
        : "
        <section class='info-section friends-section $section_class _50'>
            <span class='has_panel'>
                <h2 class='section-title'>Friendship progression</h2>
                $view_all
            </span>
            <span class='friendlist'>
        ";

    foreach($friendship_data as $name => $friend) {
        if($limit == 0) {
            break;
        }
        
        $friendship_info = [
            "name" => $name,
            "friend" => $friend,
            "images_path" => $images_path,
            "marriables_npc" => $marriables_npc,
            "birthday_json" => $birthday_json,
            "json_with_version"=> $json_with_version
        ];

        $structure .= get_friendship_structure($friendship_info);
        $limit--;
    }

    foreach($villagers_json as $villager_name) {
        if($limit == 0) {
            break;
        }

        if(isset($friendship_data[$villager_name])) {   
            continue;
        }

        $friendship_info = [
            "name" => $villager_name,
            "friend" => null,
            "images_path" => $images_path,
            "marriables_npc" => $marriables_npc,
            "birthday_json" => $birthday_json,
            "json_with_version"=> $json_with_version
        ];
        
        $structure .= get_friendship_structure($friendship_info);
        $limit--;
    }

    $structure .= "
            </span>
        </section>";
    return $structure;
}

function display_unlockables():string {
	$player_unlockables = $GLOBALS["all_players_data"][$GLOBALS["player_id"]]["has_element"];
	$version_score = $GLOBALS["game_version_score"];
    $images_path = get_images_folder() . "unlockables/";
	$decoded_unlockables = $GLOBALS["json"]["unlockables"];

    $structure = "
        <section class='gallery unlockables-section _50'>
            <h2 class='section-title'>Unlockables</h2>
            <span>
				<h3 class='no-spoil-title'>Nothing to see here yet</h3>
    ";

	foreach($decoded_unlockables as $version => $unlockables) {
        $is_newer_version_class = ($version_score < get_game_version_score($version)) ? "newer-version" : "older-version";
        
		foreach($unlockables as $unlockable) {
			$formatted_name = formate_text_for_file($unlockable);
			if(!isset($player_unlockables[$formatted_name]["is_found"])) {
				continue;
            }
	
			$unlockable_class = ($player_unlockables[$formatted_name]["is_found"]) ? "found" : "not-found";
			$unlockable_image = "$images_path$formatted_name.png";
			$wiki_url = get_wiki_link(get_item_id_by_name($unlockable));
			
			$structure .= "
				<span class='tooltip'>
					<a class='wiki_link' href='$wiki_url' target='_blank'>
						<img src='$unlockable_image' alt='$unlockable' class='gallery-item unlockables $unlockable_class $is_newer_version_class'/>
					</a>
					<span>$unlockable</span>
				</span>
			";
		}
	}

    $structure .= "
			</span>
		</section>
	";

    return $structure;
}

function display_detailled_gallery(array $gallery_details, string $width = "", array $panel_details = []):string {
    extract($gallery_details);
	$version_score = $GLOBALS["game_version_score"];
	$images_path = get_images_folder() . "$json_filename/";
    $json_data = $GLOBALS["json"][$json_filename];

    extract($panel_details);

    $player_id = $GLOBALS["player_id"];
    $title = (!empty($panel_details)) ?
        "<span class='has_panel'>
            <h2 class='section-title'>$section_title</h2>
            <span class='view-$panel_alt view-$panel_alt-$player_id modal-opener'>- View $panel_name</span>
        </span>"
        :
        "<h2 class='section-title'>$section_title</h2>";

    $structure = "
        <section class='gallery $json_filename-section $width'>
            $title
            <span>
				<h3 class='no-spoil-title'>Nothing to see here yet</h3>
    ";
    
    foreach($json_data as $key => $json_version) {
        $is_newer_version_class = ($version_score < get_game_version_score($key)) ? "newer-version" : "older-version";
        
        foreach($json_version as $json_line_name) {
            $is_found = array_key_exists($json_line_name, $player_data);
            $element_class = ($is_found) ? "found" : "not-found";

            // Wilderness Golem désactivé si pas la ferme wilderness
            if($json_filename == "enemies" && $json_line_name == "Wilderness Golem" && $GLOBALS["should_spawn_monsters"] == "false") {
                continue;
            }

            if(in_array($json_filename, ["cooking_recipes", "crafting_recipes", "artifacts", "minerals"])) {
                if($is_found && $player_data[$json_line_name]["counter"] == 0) {
                    $element_class .= " unused";
                }
            }

            $element_image = $images_path . formate_text_for_file((string) explode('µ', $json_line_name)[0]). ".png";
            if(in_array($json_filename, ["secret_notes"])) {
                $line_name = explode(" ", $json_line_name);
                $icon_name = formate_text_for_file(implode(" ", array_slice($line_name, 0, 2)));
                $element_image = get_images_folder() . "icons/$icon_name.png";
            }

            if(in_array($json_filename, ["achievements", "secret_notes"])) {
                $wiki_url = [
                    "achievements" => "https://stardewvalleywiki.com/Achievements",
                    "secret_notes" => "https://stardewvalleywiki.com/Secret_Notes"
                ][$json_filename];
            }
            else {
                $wiki_url = get_wiki_link(get_item_id_by_name($json_line_name));
            }

            $element_tooltip = ($is_found) ? get_tooltip_text($player_data, $json_line_name, $json_filename) : $json_line_name;

			$structure .= "
				<span class='tooltip'>
					<a class='wiki_link' href='$wiki_url' target='_blank'>
                        <img src='$element_image' alt='$json_line_name' class='gallery-item $json_filename $element_class $is_newer_version_class'/>
                    </a>
                    <span>$element_tooltip</span>
                </span>
            ";
        }
    }

	$structure .= "
			</span>
		</section>
	";

    return $structure;
}

function display_books():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["books"],
        "json_filename" => "books",
        "section_title" => "Books"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_fish():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["fish_caught"],
        "json_filename" => "fish",
        "section_title" => "Fish caught"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_cooking_recipes():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["cooking_recipes"],
        "json_filename" => "cooking_recipes",
        "section_title" => "Cooking recipes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_minerals():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["minerals_found"],
        "json_filename" => "minerals",
        "section_title" => "Minerals"
    ];
    $panel_details = [
        "panel_alt"     => "museum",
        "panel_name"    => "museum pieces"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}

function display_artifacts():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["artifacts_found"],
        "json_filename" => "artifacts",
        "section_title" => "Artifacts"
    ];
    $panel_details = [
        "panel_alt"     => "museum",
        "panel_name"    => "museum pieces"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}

function display_enemies():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["enemies_killed"],
        "json_filename" => "enemies",
        "section_title" => "Enemies killed"
    ];
    $panel_details = [
        "panel_alt"     => "monster-eradication-goals",
        "panel_name"    => "Monster Eradication Goals"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}

function display_achievements():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["achievements"],
        "json_filename" => "achievements",
        "section_title" => "In-game achievements"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_shipped_items():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["shipped_items"],
        "json_filename" => "shipped_items",
        "section_title" => "Shipped items"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}

function display_crafting_recipes():string {
	$data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["crafting_recipes"],
        "json_filename" => "crafting_recipes",
        "section_title" => "Crafting recipes"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}

function display_farm_animals():string {
    $data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["farm_animals"],
        "json_filename" => "farm_animals",
        "section_title" => "Farm animals"
    ];
    $panel_details = [
        "panel_alt"     => "all-animals",
        "panel_name"    => "all farm animals"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}

function display_secret_notes():string {
    $data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["secret_notes"],
        "json_filename" => "secret_notes",
        "section_title" => "Secret notes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_locations_visited():string {
    $data = $GLOBALS["all_players_data"][$GLOBALS["player_id"]];
    $gallery_details = [
        "player_data" => $data["locations_visited"],
        "json_filename" => "locations_to_visit",
        "section_title" => "Locations visited"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}
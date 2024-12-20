<?php

function display_player_selection(): string {
    $players_names = $GLOBALS["players_names"];
    $players_name_structure = "";

    if(count($players_names) > 1) {
        for($i = 0; $i < count($players_names); $i++) {
            $players_name_structure .= "<li class='player_selection' value='player_$i'>" . formate_usernames($players_names[$i]) . "</option>";
        }
    }

    return "
        <ul id='players_selection'>
            $players_name_structure
        </ul>
    ";
}

function display_game_version(): string {
    return "<span class='game_version'>V " . $GLOBALS["game_version"] . "</span>";
}

function display_settings_button(string $prefix): string {
    return "
        <span class='$prefix-settings modal-opener'>
            <img src='" . get_images_folder() . "/icons/settings.png' class='modal-opener' alt='Settings icon'/>
        </span>
    ";
}

function display_save_button(string $prefix): string {
    return "
        <span class='$prefix-upload modal-opener'>
            <img src='" . get_images_folder() . "/icons/file.png' class='modal-opener' alt='File upload icon'/>
        </span>
    ";
}

function display_feedback_button(): string {
    return "
        <span class='feedback-opener modal-opener'>
            <img src='" . get_images_folder() . "/icons/feedback.png' class='modal-opener' alt='Feedback icon'/>
        </span>
    ";
}

function display_home_button(): string {
    return "
        <span class='landing-page-opener'>
            <img src='" . get_images_folder() . "/icons/home.png' id='home-icon' alt='Home icon'/>
        </span>
    ";
}

function display_junimo_kart_button(): string {
	return "<img src='" . get_images_folder() . "/icons/controller.png' class='controller-icon view-junimo-kart-leaderboard view-junimo-kart-leaderboard-" . get_current_player_id() . " button-elements modal-opener icon' alt='Controller icon'/>";
}

function display_community_center(): string {
	return "<img src='" . get_images_folder() . "/icons/golden_scroll.png' class='golden-scroll-icon view-community-center view-community-center-" . get_current_player_id() . " button-elements modal-opener icon' alt='Golden Scroll icon'/>";
}

function display_quest_button(): string {
	return "<img src='" . get_images_folder() . "/icons/quest_icon.png' class='quest-icon view-all-quests view-all-quests-" . get_current_player_id() . " button-elements modal-opener icon' alt='Quest icon'/>";
}

function display_visited_locations_button(): string {
	return "<img src='" . get_images_folder() . "/icons/quest_icon.png' class='visited-locations-icon view-visited-locations view-visited-locations-" . get_current_player_id() . " button-elements modal-opener icon' alt='Visited locations icon'/>";
}

function get_level_progress_bar(int $level, int $max_level = 10): string {
    $images_path = get_images_folder();
    $level_structure = "";
    
    for($i = 1; $i <= $max_level; $i++) {
        $state = ($level >= $i) ? "" : "_empty";
        $icon_type = ($i % ($max_level / 2) === 0) ? "big_level" : "level";
        $level_bar = $images_path . "/icons/{$icon_type}{$state}.png";
        
        $level_structure .= "<img src='$level_bar' alt=''/>";
    }
    
    return "
        <span class='level-progress-bar'>
            $level_structure
        </span>
    ";
}

function get_skills_icons(array $skills, string $current_skill): string {
    $images_path = get_images_folder();
    $skill_structure = "";

    foreach($skills as $skill) {
        if($current_skill === strtolower($skill["source"])) {

            $skill_icon = strtolower($skill["skill"]);
            $skill_icon_path = "$images_path/skills/$skill_icon.png";
            $skill_description = $skill["description"];

            $skill_structure .= "
                <span class='tooltip'>
                    <img src='$skill_icon_path' alt='$skill_description'/>
                    <span>$skill_description</span>
                </span>
			";
        }
    }

    return "
        <div class='skills-section'>
            $skill_structure
        </div>
    ";
}

function get_tooltip_text(array $player_data, string $json_line_name, string $data_type): string {
    $data_array = $player_data[$json_line_name];

    if(empty($data_array)) {
        return $json_line_name;
    }

    extract($data_array);

    switch($data_type) {
        case "locations_to_visit" :
            return "$json_line_name";

        case "farm_animals" : 
            return "$json_line_name: $counter in your farm";

        case "fish" : 
            if($max_length > 0) return "$json_line_name: caught $caught_counter times ($max_length inches)";
            return "$json_line_name: caught $caught_counter times";

        case "enemies" : 
            return "$json_line_name: $killed_counter killed";

        case "cooking_recipes" :
            if(!$counter) return "$json_line_name: not cooked yet";
            return "$json_line_name: cooked " . (int) $counter . " times";

		case "crafting_recipes" :
			if(!$counter) return "$json_line_name: not crafted yet";
			return "$json_line_name: crafted " . (int) $counter . " times";

        case "achievements" :
            return "$json_line_name: $description";

        case "artifacts":
        case "minerals":  
            if($counter === 0) return "$json_line_name: not given yet";
            return "$json_line_name: given to museum";

        default : return $json_line_name;
    }
}

function get_friendship_structure(array $friendship_info): string {
    extract($friendship_info);
    $friend_icon = "$images_path/characters/" . strtolower($name) . ".png";
    $is_newer_version = array_search($name, $json_with_version) ? "older-version" : "newer-version";
    
    $birthday = $birthday_json[get_custom_id($name)] ?? null;
    $is_birthday = $birthday && is_this_the_same_day($birthday) ? "is_birthday" : "isnt_birthday";
    $birthday_date = $birthday ? "Day " . explode("/", $birthday)[0] . " of " . explode("/", $birthday)[1] : "Unknown";
    
    $wiki_url = get_wiki_link(get_item_id_by_name($name));
    
    $friend_level = $friend["friend_level"] ?? 0;
    $status = $friend["status"] ?? "Unknown";
    $can_be_married = in_array($name, $marriables_npc) && $status === "Friendly";
    $max_heart = ($status) === "Married" ? 14 : 10;
    $is_met = ($status === "Unknown") ? "not-met" : "met";
    
    $hearts_html = "";

    for($i = 1; $i <= $max_heart; $i++) {
        $heart_icon = "$images_path/icons/" . (($i > 8 && $can_be_married) ? "locked_heart.png" : (($friend_level >= $i) ? "heart.png" : "empty_heart.png"));
        $hearts_html .= "<img src='$heart_icon' class='hearts' alt=''/>";
    }

    $gifted = ($friend) ? [
        $friend["week_gifts"] > 0 ? "gifted" : "not-gifted",
        $friend["week_gifts"] === 2 ? "gifted" : "not-gifted"
    ] : ["not-gifted", "not-gifted"];

    return "
        <span>
            <a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                <img src='$friend_icon' class='character-icon $is_newer_version $is_met' alt='$name icon'/>
            </a>
            <span class='character-name " . strtolower($name) . "'>$name</span>
            <span class='hearts-level'>$hearts_html</span>
            <span class='tooltip'> 
                <img src='$images_path/icons/birthday_icon.png' class='birthday_icon $is_birthday' alt=''/>
                <span>$birthday_date</span>
            </span>
            <span class='interactions'>
                <span class='tooltip'>
                    <img src='$images_path/icons/gift.png' class='interaction {$gifted[0]}' alt=''/>
                    <img src='$images_path/icons/gift.png' class='interaction {$gifted[1]}' alt=''/>
                    <span>Gifts made in the last week</span>
                </span>
            </span>
            <span class='friend-status'>$status</span>
        </span>
    ";
}

function get_animal_status_tooltip(string $status, string $animal_name): string {
    return [
        "happy" => "$animal_name looks really happy today!",
        "fine"  => "$animal_name looks fine today!",
        "angry" => "$animal_name looks sad today :("
    ][$status] ?? "";
}

function get_weather_tooltip(string $weather): string {
	return [
		"sun"        => "It's going to be clear and sunny all day",
		"rain"       => "It's going to rain all day tomorrow",
		"green_rain" => "Um... There appears to be some kind of... anomalous reading... I... don't know what this means...",
		"wind"       => "It's going to be cloudy, with gusts of wind throughout the day",
		"storm"      => "Looks like a storm is approaching. Thunder and lightning is expected",
		"snow"       => "Expect a few inches of snow tomorrow"
	][$weather] ?? "";
}

function get_child_tooltip(string $spouse, array $children): string {
	$gender = get_the_married_person_gender($spouse);
	$children_count = count($children);
	$children_names = ($children_count === 1) ? $children[0] : implode(" and ", $children);
	$nombre = ($children_count > 1) ? "children" : "child";

	if($children_count === 0) {   
        return "With $gender $spouse, haven't yet had $nombre";
    }

	return "With $gender $spouse, you had $children_count $nombre : $children_names";
}

function display_festival_icon(): string {
    $images_path = get_images_folder();
    $festivals = sanitize_json_with_version("festivals", true);
	$festival_name = "Not a festival day";
	$festival_class = "isnt_festival";

	foreach($festivals as $key => $festival) {
		for($i = 0; $i < count($festival["date"]); $i++) {
			if(is_this_the_same_day($festival["date"][$i])) {
				$festival_class = "is_festival";
				$festival_name = $festival["name"];
				$wiki_url = get_wiki_link($key);
				break;
			}
		}
	}

	return (isset($wiki_url)) 
    ? 
	"<span class='tooltip'>
		<a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
			<img src='$images_path/icons/festival_icon.gif' class='festival_icon $festival_class' alt='Festival icon'/>
		</a>
		<span class='right'>$festival_name</span>
	</span>"
	:
	"<span class='tooltip'>
        <a href='" . get_wiki_link_by_name("festival") . "' class='wiki_link' rel='noreferrer' target='_blank'>
		    <img src='$images_path/icons/festival_icon.png' class='festival_icon $festival_class' alt='Festival icon'/>
		</a>
        <span class='right'>$festival_name</span>
	</span>";
}

function display_weather_icon(): string {
    $data = $GLOBALS["shared_players_data"];
    $images_path = get_images_folder();
    $weather = $data["weather"];

    return "
        <span class='tooltip'>
            <a href='https://stardewvalleywiki.com/Weather' class='wiki_link' rel='noreferrer' target='_blank'>
                <img src='$images_path/icons/$weather.png' class='weather_icon' alt='Weather icon'/>
            </a>
            <span class='left'>" . get_weather_tooltip($weather) . "</span>
        </span>
    ";
}

function display_project_contributor(array $options): string {
    extract($options);

    $images_path = get_images_folder();
    $portrait =  "$images_path/content/$icon.png";
    $presentation = "";
    $socials_links = "";

    foreach($texts as $text) {
        $presentation .= "<span>$text</span>";
    }

    foreach($socials as $social_name => $social) {
        extract($social);
        if($on_display) {
            $socials_links .= "<a href='$url' rel='noreferrer' target='_blank'><img src='$images_path/social/$social_name.png' alt='$social_name'/></a>";
        }
    }

    return "
        <span>
            <img src='$portrait' class='character-image $icon' alt='$name'/>
            <span>
                <span class='character-presentation'>
                    $presentation
                </span>
                <span class='socials'>
                    $socials_links
                </span>
            </span>
        </span>
    ";
}

function display_bundle_requirements(array $requirements, array $added_items): string {
    $images_path = get_images_folder();
    $structure = "";
    
    foreach($requirements as $requirement) {
        extract($requirement);

        $formatted_item_name = formate_text_for_file($name);
        $has_been_donated = (has_been_donated_in_bundle($name, $added_items)) ? "donated" : "not-donated";
        $quantity = ($quantity > 1) ? "<span class='quantity'>$quantity</span>" : "";

        $structure .= "
            <span class='required-item'>
                <img src='$images_path/$type/$formatted_item_name.png' class='item $has_been_donated' alt='$name'/>
                <img src='$images_path/icons/quality_$quality.png' class='quality' alt=''/>
                $quantity
            </span>
        ";
    }

    return $structure;
}

function display_bundle_added_items(array $added_items, int $limit): string {
    $structure = "";
    $images_path = get_images_folder();
    
    for($i = 0; $i < $limit; $i++) {
        $added_item = "";

        if(isset($added_items[$i])) {
            $item_name = $added_items[$i]["name"];
            $formatted_item_name = formate_text_for_file($item_name);
            $type = $added_items[$i]["type"];
            $added_item = "<img src='$images_path/$type/$formatted_item_name.png' class='added-item' alt='$item_name'/>";
        }

        $structure .= "
            <span class='slot'>
                <img src='$images_path/icons/bundle_slot.png' class='empty-slot' alt=''/>
                $added_item
            </span>
        ";
    }

    return $structure;
}

function display_bundle_purchase(): string {
    return "<img src='" . get_images_folder() . "/content/purchase.png' class='purchase' alt=''/>";
}
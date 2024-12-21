<?php

function display_junimo_kart_button(): string {
	return "<img src='" . get_images_folder() . "/icons/controller.png' class='controller-icon view-junimo-kart-leaderboard view-junimo-kart-leaderboard-" . get_current_player_id() . " button-elements modal-opener icon' alt='Controller icon'/>";
}



function display_quest_button(): string {
	return "<img src='" . get_images_folder() . "/icons/quest_icon.png' class='quest-icon view-all-quests view-all-quests-" . get_current_player_id() . " button-elements modal-opener icon' alt='Quest icon'/>";
}

function display_visited_locations_button(): string {
	return "<img src='" . get_images_folder() . "/icons/location_icon.png' class='visited-locations-icon view-visited-locations view-visited-locations-" . get_current_player_id() . " button-elements modal-opener icon' alt='Visited locations icon'/>";
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




function get_animal_status_tooltip(string $status, string $animal_name): string {
    return [
        "happy" => "$animal_name looks really happy today!",
        "fine"  => "$animal_name looks fine today!",
        "angry" => "$animal_name looks sad today :("
    ][$status] ?? "";
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
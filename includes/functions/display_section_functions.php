<?php 

function display_sur_header():string {

    $structure = "<div class='sur-header'>";
        $structure .= display_player_selection();
        $structure .= "<span>";
            $structure .= display_game_version();
            $structure .= display_secondary_upload();
            $structure .= display_settings_button();
        $structure .= "</span>";
    $structure .= "</div>";

    return $structure;
}

function display_player_selection():string {

	$players = $GLOBALS['players_names'];

    $structure = "
		<ul id='players_selection'>
	";

    for($i = 0; $i < count($players); $i++) {
        $structure .= "<li class='player_selection' value='player_$i'>" . formate_usernames($players[$i]) . "</option>";
    }

    $structure .= "</ul>";
    
    return $structure;
}

function display_game_version():string {
    $structure = "
            <span class='game_version'>V " . $GLOBALS['game_version'] . "</span>
    ";
    return $structure;
}

function display_settings_button(string $prefix = 'main'):string {
    $structure = "
        <span class='$prefix-settings modal-opener'><img src='" . get_images_folder() ."icons/settings.png' class='modal-opener'></span>
    ";
    return $structure;
}

function display_settings_panel():string {
    return "
        <section class='settings settings-panel'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Settings</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-settings exit' />
            </div>
            <span class='checkboxes'>
                <span class='checkbox'>
                    <input type='checkbox' id='no_spoil_mode'>
                    <span class='checkmark'><img src='" . get_images_folder() . "icons/checked.png'></span>
                    <label for='no_spoil_mode' id='no-spoil-label'>No spoil mode</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='toggle_versions_items_mode' checked>
                    <span class='checkmark'><img src='" . get_images_folder() . "icons/checked.png'></span>
                    <label for='toggle_versions_items_mode' id='toggle-versions-items-label'>Hide items from more recent versions</label>
                </span>
            </span>
        </section>
    ";
}

function display_save_button():string {
    return "
        <span class='landing-upload modal-opener'><img src='" . get_images_folder() ."icons/file.png' class='modal-opener'></span>
    ";
}

function display_secondary_upload():string {
    return "
        <span class='file-upload modal-opener'><img src='" . get_images_folder() ."icons/file.png' class='modal-opener'></span>
    ";
}

function display_save_panel():string {
    return "
        <section class='upload-panel to-keep-open'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Upload a save</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-upload exit' />
            </div>
            <span>
                <span>
                    <label id='browse-files' for='save-upload'>Browse</label>
                    <span id='new-filename'>Choose a file</span>
                    <input type='file' id='save-upload'>
                </span>
            </span>
        </section>
    ";
}

function display_header():string {

	$player_id = $GLOBALS['player_id'];
	$datas = $GLOBALS['all_players_data'][$player_id]['general'];
    
    extract($datas);    
    $images_path = get_images_folder();
	$farm_name = str_contains(strtolower($farm_name), 'farm') ? $farm_name : $farm_name . ' farm';
	$gender = ($gender == null) ? 'neutral' : $gender;

    $structure = "
        <header>
            <div class='header'>
                <span>
                    <img src='{$images_path}icons/" . strtolower($gender) . ".png' alt='Gender logo' class='player_gender_logo' />
                    <span class='data player_name'>$name <span>- $farmer_level</span></span>
                </span>

                <span>
                    <span class='data date-in-game'>$date</span>
                </span>

                <span>
                    <span class='data time-in-game'>$game_duration</span>
                    <span class='data-label'>time in game</span>
                </span>
            </div>

            <div class='sub-header'>
                <span class='all-money'>
                    <span>
                        <img src='{$images_path}icons/gold.png' alt='Gold coin' />
                        <span class='data actual-gold'>" . formate_number($golds) . "</span>
                        <span class='data-label'>golds</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/golden_walnut.png' alt='Golden walnut' />
                        <span class='data actual-golden-walnut'>" . formate_number($golden_walnuts) . "</span>
                        <span class='data-label'>golden walnut</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/qi_gem.png' alt='Qi gems' />
                        <span class='data actual-qi-gem'>" . formate_number($qi_gems) . "</span>
                        <span class='data-label'>qi gem</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/casino_coins.png' alt='Casino coins' />
                        <span class='data actual-golden-walnut'>" . formate_number($casino_coins) . "</span>
                        <span class='data-label'>casino coins</span>
                    </span>
                </span>
                <span>
                    <span class='data-info farm-name'>$farm_name</span>
                </span>
            </div>
        </header>
    ";

    return $structure;
}

function display_general_stats():string {


	$player_id = $GLOBALS['player_id'];
	$datas = $GLOBALS['all_players_data'][$player_id]['general'];

    extract($datas);
    $images_path = get_images_folder();
    $deepest_mine_level = ($mine_level > 120) ? 120 : $mine_level; 
    $deepest_skull_mine_level = ($mine_level - 120 < 0) ? 0 : $mine_level - 120;
    
    $deepest_mine_level_tooltip = "$deepest_mine_level floors in the Stardew Mine " . (($deepest_skull_mine_level > 0) ? "& $deepest_skull_mine_level floors in the Skull Mine" : "");

    $structure = "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>General stats</h2>
			<img src='" . get_images_folder() . "/icons/quest.png' class='quest-icon view-all-quests-$player_id button-elements modal-opener'>
            <div>
                <span>
                    <img src='{$images_path}icons/energy.png' alt='Energy' />
                    <span class='data data-energy'>" . formate_number($max_stamina) . "</span>
                    <span class='data-label'>max stamina</span>
                </span>

                <span>
                    <img src='{$images_path}icons/health.png' alt='Health' />
                    <span class='data data-health'>" . formate_number($max_health) . "</span>
                    <span class='data-label'>max health</span>
                </span>

                <span>
                    <img src='{$images_path}icons/inventory.png' alt='Inventory' />
                    <span class='data data-inventory'>" . formate_number($max_items) . "</span>
                    <span class='data-label'>max inventory</span>
                </span>

                <span>
                    <span class='tooltip'>
                        <img src='{$images_path}icons/mine_level.png' alt='Mine level' />
                        <span>$deepest_mine_level_tooltip</span>
                    </span>
                    <span class='data data-mine-level'>" . formate_number($mine_level) . "</span>
                    <span class='data-label'>deepest mine level</span>
                </span>
            </div>
        </section>
    ";

    return $structure;
}

function display_quests():string {

	$player_id = $GLOBALS['player_id'];
	$datas = $GLOBALS['all_players_data'][$player_id]['quest_log'];

    extract($datas);
    $images_path = get_images_folder();

    $structure = "
        <section class='quests-section info-section all-quests-$player_id'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Quests in progress</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-all-quests-$player_id exit' />
            </div>
            <span class='quests'>
    ";

	if(empty($datas)) {
		$structure .= "
					<h3>Nothing to see here yet</h3>
				</span>
			</section>
		";
	}

    foreach($datas as $data) {
        $title = $data['title'];
        $description = $data['description'];
        $objective = $data['objective'];
        $rewards = $data['rewards'];

        $structure .= "
            <span class='quest'>
                <span class='quest-infos'>
                    <span class='quest-description'>$objective</span>
                    <span class='quest-title'>$title</span>
                </span>
        ";

        if(empty($rewards)) {
			$structure .= "</span>";
			continue;
		}
        
		if(isset($data['daysLeft']))
			$structure .= " <span class='days-left'><img src='$images_path/icons/timer.png'/>" . $data['daysLeft'] . " day</span>";

		$structure .= "<span class='quest-rewards'>";
		
        for($i = 0; $i<count($rewards); $i++) {
            $structure .= (is_numeric($rewards[$i])) ? "<span class='quest-reward'>" : "<span class='quest-reward tooltip'>";
            
            if(strstr($rewards[$i], "Friendship")) {
                $reward_number = explode(" ", $rewards[$i])[0];
                $structure .= "<img src='$images_path/icons/heart_" . $reward_number .".png'/>";
            }
            elseif(is_numeric($rewards[$i]))
                $structure .= formate_number($rewards[$i]) . "<img src='$images_path/icons/gold.png'/>";
            else
                $structure .= $rewards[$i];

            $structure .= (is_numeric($rewards[$i])) ? "" : "<span class='left'>$rewards[$i]</span>";
            $structure .= "</span>";
        }
        $structure .= "
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

function display_skills():string {
    
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];

    $structure = "
		<section class='skills-section info-section'>
			<h2 class='section-title'>Skills</h2>
            <span>    
    ";

    $mastery_visible_class = (empty($datas['masteries'])) ? "" : "not-hide";

    foreach($datas['levels'] as $key => $level) {
        
        $level_icon_name = explode('_', $key)[0];
        $level_icon      = get_images_folder() . "icons/$level_icon_name.png";
        $mastery_icon    = get_images_folder() . "icons/mastery.png";
        $mastery_class   = (in_array(ucfirst(explode('_', $key)[0]) . " Mastery", $datas['masteries'])) ? 'found' : 'not-found';
        $mastery_tooltip = ucfirst(explode('_', $key)[0]) . " mastery";

        $structure .= "<span class='skill $key'>";

        $is_newer_version_class = ($GLOBALS['game_version_score'] < get_game_version_score("1.6.0")) ? 'newer-version' : 'older-version';

        $structure .= "
            <span class='tooltip'>
                <img src='$mastery_icon' class='level-icon $mastery_class $mastery_visible_class $is_newer_version_class' alt='$key'/>
                <span>" . ucfirst($mastery_tooltip) . "</span>
            </span>
       

            <span class='tooltip'>
                <img src='$level_icon' class='level-icon' alt='$key'/>
                <span>" . ucfirst($level_icon_name) . "</span>
            </span>
                
                " . get_level_progress_bar($level) . "
                <span class='level data'>$level</span>
                <span>" . get_skills_icons($datas['skills'], $level_icon_name) . "</span>
            </span>
        ";
    }

    $structure .= "
            </span>
        </section>
    ";

    return $structure;
}

function get_level_progress_bar(int $level):string {

    $structure = "<span class='level-progress-bar'>";

    for($i = 1; $i <= 10; $i++) {
        if($level >= $i) $level_bar = get_images_folder() . (($i % 5 == 0) ? "icons/big_level.png"       : "icons/level.png");
        else             $level_bar = get_images_folder() . (($i % 5 == 0) ? "icons/big_level_empty.png" : "icons/level_empty.png");
        
        $structure .= "<img src='$level_bar' alt=''/>";        
    }

    $structure .= "</span>";

    return $structure;
}

function get_skills_icons(array $skills, string $current_skill):string {

    $structure = "<div class='skills-section'>";

    foreach($skills as $skill) {
        if($current_skill == strtolower($skill['source'])) {

            $skill_icon = strtolower($skill['skill']);
            $skill_icon_path = get_images_folder() . "skills/$skill_icon.png";
            $skill_description = $skill['description'];
            
            $structure .= "
			<span class='tooltip'>
				<img src='$skill_icon_path' alt='$skill_description' />
				<span>$skill_description</span>
			</span>
			";
        }
    }

    $structure .= "</div>";

    return $structure;
}

function display_top_friendships(int $limit = 4):string {
    return display_friendships($limit);
}

function display_friendships(int $limit = -1):string {

	$player_id = $GLOBALS['player_id'];
	$friends = $GLOBALS['all_players_data'][$player_id]['friendship'];

    $images_path = get_images_folder();

    $marriables_npc = sanitize_json_with_version('marriables');
    $villagers_json = sanitize_json_with_version('villagers');

    $section_class = ($limit == -1) ? 'all-friends' : 'top-friends';
    $view_all = ($limit == -1) ? '' : "<span class='view-all-friends view-all-friendships-$player_id modal-opener'>View all friendships</span>";
    $structure = ($limit == -1) ? 
	"
        <section class='info-section friends-section $section_class $section_class-$player_id'>
			<div class='panel-header'>
           		<h2 class='section-title panel-title'>Friendship progression</h2>
				<img src='" . get_images_folder() . "icons/exit.png' class='exit-all-friendships-$player_id exit' />
			</div>
            <span>
    "
	:
	"
        <section class='info-section friends-section $section_class'>
            <h2 class='section-title'>Friendship progression</h2>
            <span>
    "
	;

    foreach($friends as $name => $friend) {
        if($limit == 0)
            break;

        $limit--;

        extract($friend);
        $friend_icon = $images_path . "characters/" . strtolower($name) . ".png";

        $structure .= "
			<span>
				<img src='$friend_icon' class='character-icon' alt='$name icon' />
				<span class='character-name'>$name</span>
			    <span class='hearts-level'>
        ";

		
        $can_be_married = in_array($name, $marriables_npc) && $status == "Friendly";

        for($i = 1; $i <= 10; $i++) {

            if($i > 8 && $can_be_married) {
                $heart_icon = get_images_folder() . "icons/locked_heart.png";
                $structure .= "<img src='$heart_icon' class='hearts' alt='' />";
                continue;
            }

            $heart_icon = get_images_folder() . (($friend_level >= $i) ? "icons/heart.png" : "icons/empty_heart.png");
            $structure .= "<img src='$heart_icon' class='hearts' alt='' />";
        }
        
        $gifted = [];
        $gifted[0] = ($week_gifts > 0) ? "gifted" : "not-gifted";
        $gifted[1] = ($week_gifts == 2) ? "gifted" : "not-gifted";

        $structure .= "
				</span>
				<span class='interactions'>
                    <span class='tooltip'>
                        <img src='{$images_path}icons/gift.png' class='interaction $gifted[0]' alt=''/>
                        <img src='{$images_path}icons/gift.png' class='interaction $gifted[1]' alt=''/>
                        <span class='left'>Gifts made in the last week</span>
                    </span>
				</span>
				<span class='friend-status'>$status</span>
			</span>
        ";
    }

    foreach($villagers_json as $villager_name) {
        if($limit == 0)
            break;

		if(isset($friends[$villager_name]))
			continue;

        $limit--;
        $friend_icon = $images_path . "characters/" . strtolower($villager_name) . ".png";

        $can_be_married = in_array($villager_name, $marriables_npc);

        $structure .= "
			<span>
				<img src='$friend_icon' class='character-icon not-found' alt='$villager_name icon' />
				<span class='character-name'>$villager_name</span>
			    <span class='hearts-level'>
        ";
		
		$status = "Unknown";
        $can_be_married = in_array($villager_name, $marriables_npc);

        for($i = 1; $i <= 10; $i++) {

            if($i > 8 && $can_be_married) {
                $heart_icon = get_images_folder() . "icons/locked_heart.png";
                $structure .= "<img src='$heart_icon' class='hearts' alt='' />";
                continue;
            }

            $heart_icon = get_images_folder() . (($friend_level >= $i) ? "icons/heart.png" : "icons/empty_heart.png");
            $structure .= "<img src='$heart_icon' class='hearts' alt='' />";
        }
        
        $gifted = ["not-gifted", "not-gifted"];

        $structure .= "
				</span>
				<span class='interactions'>
                    <span class='tooltip'>
                        <img src='{$images_path}icons/gift.png' class='interaction $gifted[0]' alt=''/>
                        <img src='{$images_path}icons/gift.png' class='interaction $gifted[1]' alt=''/>
                        <span class='left'>Gifts made in the last week</span>
                    </span>
				</span>
				<span class='friend-status'>$status</span>
			</span>
        ";
    }


    $structure .= "
			$view_all
        </span>
    </section>";

    return $structure;
}

function display_unlockables():string {

	$player_id = $GLOBALS['player_id'];
	$player_elements = $GLOBALS['all_players_data'][$player_id]['has_element'];

    $images_path = get_images_folder() . "unlockables/";
    $elements = sanitize_json_with_version('unlockables');
    sort($elements);

    $structure = "
        <section class='gallery unlockables-section'>
            <h2 class='section-title'>Unlockables</h2>
            <span>
				<h3 class='no-spoil-title'>Nothing to see here yet</h2>
    ";

	foreach($elements as $element) {
		$formatted_name = formate_text_for_file($element);
		if(!isset($player_elements[$formatted_name]['is_found']))
			continue;

		$element_class = ($player_elements[$formatted_name]['is_found']) ? "found" : "not-found";
		$element_image = "$images_path$formatted_name.png";
		
		$structure .= "
			<span class='tooltip'>
                <a href='" . get_wiki_link(get_item_id_by_name($element)) . "' target='_blank'>
				    <img src='$element_image' alt='$element' class='gallery-item unlockables $element_class' />
				</a>
                <span>$element</span>
			</span>
		";
	}

    $structure .= "
			</span>
		</section>
	";

    return $structure;
}

function display_detailled_gallery(array $player_datas, string $json_filename, string $section_title):string {
    
	$version_score = $GLOBALS['game_version_score'];

	$images_path = get_images_folder() . "$json_filename/";
    $json_datas = json_decode(file_get_contents(get_json_folder() . $json_filename . '.json'), true);

    $structure = "
        <section class='gallery $json_filename-section'>
            <h2 class='section-title'>$section_title</h2>
            <span>
				<h3 class='no-spoil-title'>Nothing to see here yet</h2>
    ";
    
    foreach($json_datas as $key => $json_version) {
        
        $is_newer_version_class = ($version_score < get_game_version_score($key)) ? 'newer-version' : 'older-version';
        
        foreach($json_version as $json_line_name) {

    
            $is_found = array_key_exists($json_line_name, $player_datas);

            $element_class   = ($is_found) ? 'found' : 'not-found';


            if(in_array($json_filename, array('recipes', 'artifacts', 'minerals'))) 
                if($is_found && $player_datas[$json_line_name]['counter'] == 0)
                    $element_class .= ' unused';

            $element_image = $images_path . formate_text_for_file((string) explode(':', $json_line_name)[0]). '.png';
            $element_tooltip = ($is_found) ? get_tooltip_text($player_datas, $json_line_name, $json_filename) : $json_line_name;
           
            if(!in_array($json_filename, array('achievements')))
                $wiki_url = get_wiki_link(get_item_id_by_name($json_line_name));
            else 
                $wiki_url = 'https://stardewvalleywiki.com/Achievements';
            

            $structure .= "
                <span class='tooltip'>
                    <a href='$wiki_url' target='_blank'>
                        <img src='$element_image' alt='$json_line_name' class='gallery-item $json_filename $element_class $is_newer_version_class' />
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

function get_tooltip_text(array $player_data, string $json_line_name, string $data_type):string {
    
    $data_array = $player_data[$json_line_name];

    if(empty($data_array))
        return $json_line_name;

    extract($data_array);

    switch($data_type) {
        case 'fish' : 
            if($max_length > 0) return "$json_line_name : caught $caught_counter times ($max_length inches)";
            return "$json_line_name : caught $caught_counter times";

        case 'enemies' : 
            return "$json_line_name : $killed_counter killed";

        case 'recipes' :
            if(!$counter) return "$json_line_name : not cooked yet";
            return "$json_line_name : cooked " . (int) $counter . " times";

        case 'achievements' :
            return "$json_line_name : $description";

        case 'artifacts':
        case 'minerals':  
            if($counter == 0) return "$json_line_name : not given yet";
            return "$json_line_name : given to museum";

        default : return $json_line_name;
    }
}



function display_books():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['books'], 'books', 'Books');
}

function display_fish():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['fish_caught'], 'fish', 'Fish caught');
}

function display_cooking_recipes():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['cooking_recipes'], 'recipes', 'Cooking recipes');
}

function display_minerals():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['minerals_found'], 'minerals', 'Minerals');
}

function display_artifacts():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['artifacts_found'], 'artifacts', 'Artifacts');
}

function display_enemies():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['enemies_killed'], 'enemies', 'Enemies killed');
}

function display_achievements():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['achievements'], 'achievements', 'Achievements');
}

function display_shipped_items():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['shipped_items'], 'shipped_items', 'Shipped items');
}




function get_farmer_level(object $data):string {
    
    $level_names = array(
        'Newcomer',
        'Greenhorn',
        'Bumpkin',
        'Cowpoke',
        'Farmhand',
        'Tiller',
        'Smallholder',
        'Sodbuster',
        'Farmboy',
        'Granger',
        'Planter',
        'Rancher',
        'Farmer',
        'Agriculturist',
        'Cropmaster',
        'Farm King'
    );

    $level = ($data->farmingLevel + $data->miningLevel + $data->combatLevel + $data->foragingLevel + $data->fishingLevel + $data->luckLevel) / 2;

    return $level_names[floor($level / 2)];
}
<?php 

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

function display_player_selection():string {
	$players = $GLOBALS['players_names'];
    
    if(count($players) < 2)
        return "<ul id='players_selection'></ul>";

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
    $structure = "<span class='game_version'>V " . $GLOBALS['game_version'] . "</span>";
    return $structure;
}

function display_settings_button(string $prefix):string {
    $structure = "<span class='$prefix-settings modal-opener'><img src='" . get_images_folder() ."icons/settings.png' class='modal-opener' alt='Settings icon'></span>";
    return $structure;
}

function display_settings_panel():string {
    return "
        <section class='settings settings-panel modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Settings</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-settings exit' alt=''/>
            </div>
            <span class='checkboxes'>
                <span class='checkbox'>
                    <input type='checkbox' id='spoil_mode'>
                    <span class='checkmark'><img src='" . get_images_folder() . "icons/checked.png' alt=''></span>
                    <label for='spoil_mode' id='spoil-label'>Hide discovered items</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='no_spoil_mode'>
                    <span class='checkmark'><img src='" . get_images_folder() . "icons/checked.png' alt=''></span>
                    <label for='no_spoil_mode' id='no-spoil-label'>Hide undiscovered items</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='toggle_versions_items_mode' checked>
                    <span class='checkmark'><img src='" . get_images_folder() . "icons/checked.png' alt=''></span>
                    <label for='toggle_versions_items_mode' id='toggle-versions-items-label'>Hide items from more recent versions</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='wiki_redirections' checked>
                    <span class='checkmark'><img src='" . get_images_folder() . "icons/checked.png' alt=''></span>
                    <label for='wiki_redirections' id='wiki_redirections-label'>Activate wiki redirections</label>
                </span>
            </span>
        </section>
    ";
}

function display_save_button(string $prefix):string {
    return "<span class='$prefix-upload modal-opener'><img src='" . get_images_folder() ."icons/file.png' class='modal-opener' alt='File upload icon'></span>";
}

function display_save_panel():string {
    return "
        <section class='upload-panel to-keep-open modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Upload a save</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-upload exit'/>
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

function display_feedback_button():string {
    return "<span class='feedback-opener modal-opener'><img src='" . get_images_folder() ."icons/feedback.png' class='modal-opener' alt='Feedback icon'></span>";
}

function display_feedback_panel():string {
    return "
        <section class='feedback-panel modal-window to-destroy'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Your feedback</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-feedback exit' alt=''/>
            </div>
            <span>
                <form id='feedback_form'>
                    <span>
                        <span class='label_and_input'>
                            <label for='username'>Username</label>
                            <input type='text' id='username' name='username' required>
                        </span>

                        <span class='label_and_input mail_input'>
                            <label for='mail'>Email address</label>
                            <input type='email' id='mail' name='mail' required>
                        </span>
                    </span>

                    <span class='label_and_input full_width'>
                        <label>Topic</label>

                        <span class='topic_selection'>
                            <span>
                                <input type='radio' id='feature_request' value='Feature request' name='topic' class='feedback_real_radio' required checked>
                                <img src='" . get_images_folder() . "icons/feature.png' class='feedback_custom_radio' alt='Feature request topic'>
                                <label for='feature_request'>Feature request</label>
                            </span>

                            <span>
                                <input type='radio' id='bug_report' value='Bug report' name='topic' class='feedback_real_radio'>
                                <img src='" . get_images_folder() . "icons/bug.png' class='feedback_custom_radio topic_not_selected' alt='Bug report topic'>
                                <label for='bug_report'>Bug report</label>
                            </span>

                            <span>
                                <input type='radio' id='other' value='Other' name='topic' class='feedback_real_radio'>
                                <img src='" . get_images_folder() . "icons/other.png' class='feedback_custom_radio topic_not_selected' alt='Other topic'>
                                <label for='other'>Other</label>
                            </span>
                        </span>
                    </span>

                    <span class='label_and_input full_width'>
                        <label for='message'>Message</label>
                        <textarea rows='8' id='message' name='message' required></textarea>
                    </span>

                    <input type='submit'>
                </form>
            </span>
        </section>
    ";
}

function display_home_button():string {
    return "<span class='landing-page-opener'><img src='" . get_images_folder() ."icons/home.png' id='home-icon' alt='Home icon'></span>";
}

function display_header():string {
	$player_id = $GLOBALS['player_id'];
	$all_players_data = $GLOBALS['all_players_data'][$player_id]['general'];

	$festival_icon = display_festival_icon();
    
    extract($all_players_data);    
    $images_path = get_images_folder();
	$farm_name = str_contains(strtolower($farm_name), 'farm') ? $farm_name : $farm_name . ' farm';
	$gender = ($gender == null) ? 'neutral' : $gender;

    $structure = "
        <header>
            <div class='header'>
                <span class='player'>
                    <img src='{$images_path}icons/" . strtolower($gender) . ".png' alt='Gender logo: $gender' class='player_gender_logo'/>
                    <span class='data player_name'>" . formate_usernames($name) . "<span class='data-label'> $farmer_level</span></span>
                    <span class='data player_name'> works at : $farm_name</span>
                </span>

                <span class='date'>
                    <span class='data date-in-game'>$date</span>
					$festival_icon
                </span>

                <span class='game_time'>
                    <span class='data time-in-game'>$game_duration</span>
                    <span class='data-label'>time in game</span>
                </span>
            </div>

            <div class='sub-header'>
                <span class='all-money'>
                    <span>
                        <img src='{$images_path}icons/gold.png' alt='Gold coins'/>
                        <span class='data actual-gold'>" . formate_number($golds) . "</span>
                        <span class='data-label'>golds</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/golden_walnut.png' alt='Golden walnuts'/>
                        <span class='data actual-golden-walnut'>" . formate_number($golden_walnuts) . " / 130</span>
                        <span class='data-label'>golden walnut</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/qi_gem.png' alt='Qi gems'/>
                        <span class='data actual-qi-gem'>" . formate_number($qi_gems) . "</span>
                        <span class='data-label'>qi gem</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/casino_coins.png' alt='Casino coins'/>
                        <span class='data actual-golden-walnut'>" . formate_number($casino_coins) . "</span>
                        <span class='data-label'>casino coins</span>
                    </span>
                </span>
                <span class='perfection-stats'>
                    <span>
                        <span class='tooltip'>
                            <img src='{$images_path}characters/grandpa.png' alt='GrandPa candles'/>
                            <span>Number of candles lit on the altar ($grandpa_score points)</span>
                        </span>
                        <span class='data data-candles'>" . get_candles_lit($grandpa_score) . "</span>
                        <span class='data-label'>candles lit</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/stardrop.png' alt='Perfection'/>
                        <span class='data data-perfection'>" . get_perfection_percentage() . "%</span>
                        <span class='data-label'>perfection progression</span>
                    </span>
                </span>
            </div>
        </header>
    ";

    return $structure;
}

function display_quest_button():string {
	return "<img src='" . get_images_folder() . "/icons/quest_icon.png' class='quest-icon view-all-quests-" . $GLOBALS['player_id'] . " button-elements modal-opener' alt='Quest icon'>";
}

function display_general_stats():string {
	$player_id = $GLOBALS['player_id'];
	$all_players_data = $GLOBALS['all_players_data'][$player_id]['general'];

	$quest_button = display_quest_button();

    extract($all_players_data);
    $images_path = get_images_folder();
    $deepest_mine_level = ($mine_level > 120) ? 120 : $mine_level; 
    $deepest_skull_mine_level = ($mine_level - 120 < 0) ? 0 : $mine_level - 120;
    
    $deepest_mine_level_tooltip = "$deepest_mine_level floors in the Stardew Mine" . (($deepest_skull_mine_level > 0) ? " & $deepest_skull_mine_level floors in the Skull Mine" : "");

    return "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>General stats</h2>
			$quest_button
            <div>
                <span>
                    <img src='{$images_path}icons/energy.png' alt='Energy'/>
                    <span class='data data-energy'>" . formate_number($max_stamina) . "</span>
                    <span class='data-label'>max stamina</span>
                </span>

                <span>
                    <img src='{$images_path}icons/health.png' alt='Health'/>
                    <span class='data data-health'>" . formate_number($max_health) . "</span>
                    <span class='data-label'>max health</span>
                </span>

                <span>
                    <img src='{$images_path}icons/inventory.png' alt='Inventory'/>
                    <span class='data data-inventory'>" . formate_number($max_items) . "</span>
                    <span class='data-label'>max inventory</span>
                </span>

                <span>
                    <span class='tooltip'>
                        <img src='{$images_path}icons/mine_level.png' alt='Mine level'/>
                        <span>$deepest_mine_level_tooltip</span>
                    </span>
                    <span class='data data-mine-level'>" . formate_number($mine_level) . "</span>
                    <span class='data-label'>deepest mine level</span>
                </span>
                "
                .
                (($spouse) ?
                "
                <span>
                    <span class='tooltip'>
                        <img src='{$images_path}characters/" . lcfirst($spouse) . ".png' alt='$spouse'/>
                        <span> " . get_child_tooltip($spouse, $children) . "</span>
                    </span>
                    <span class='data data-family'>" . count($children) . "</span>
                    <span class='data-label'>children</span>
                </span>
                "
                : 
                "")
                .
                "
                <span>
                    <span class='tooltip'>
                        <img src='{$images_path}icons/house.png' alt='House upgrades'/>
                        <span>$house_level / 3 improvements</span>
                    </span>
                    <span class='data data-house-upgrade'>$house_level</span>
                    <span class='data-label'>upgrades done</span>
                </span>
            </div>
        </section>
    ";
}

function display_quests():string {
	$player_id = $GLOBALS['player_id'];
	$this_player_data = $GLOBALS['all_players_data'][$player_id]['quest_log'];

    $images_path = get_images_folder();

    $structure = "
        <section class='quests-section info-section all-quests-$player_id modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Quests in progress</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-all-quests-$player_id exit' alt=''/>
            </div>
            <span class='quests'>
    ";

	if(empty($this_player_data)) {
		$structure .= "
					<h3>Nothing to see here yet</h3>
				</span>
			</section>
		";
	}

    foreach($this_player_data as $data) {
		extract($data);

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
        
		if(isset($daysLeft)) {
			$day_text = ($daysLeft > 1) ? "days" : "day";
			$structure .= " <span class='days-left'><img src='$images_path/icons/timer.png' alt='Time left'/>$daysLeft $day_text</span>";
		}

		$structure .= "<span class='quest-rewards'>";
		
        for($i = 0; $i<count($rewards); $i++) {
			// Does reward need a tooltip (gold and qi gems don't)
            $structure .= ((is_numeric($rewards[$i]) || str_ends_with($rewards[$i], 'q'))) ? "<span class='quest-reward'>" : "<span class='quest-reward tooltip'>";
            
			// If reward is Friendship hearts/points
            if(strstr($rewards[$i], "Friendship")) {
                $reward_number = explode(" ", $rewards[$i])[0];
                $structure .= "<img src='$images_path/rewards/heart_$reward_number.png' alt='Friendship reward'/>";
            }
			// If reward is Gold
            elseif(is_numeric($rewards[$i]))
                $structure .= formate_number($rewards[$i]) . "<img src='$images_path/rewards/gold.png' alt='Gold coins reward'/>";
			// If reward is Qi Gems
            elseif(str_ends_with($rewards[$i], 'q'))
                $structure .= explode('_', $rewards[$i])[0] . "<img src='$images_path/rewards/qi_gem.png' alt='Qi gems reward'/>";
			// If reward is something else
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
	$this_player_data = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
	$this_player_skills = $this_player_data['skills'];
	$this_player_skills_levels = $this_player_data['levels'];
	$this_player_masteries = $this_player_data['masteries'];

    $structure = "
		<section class='skills-section info-section _50'>
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

        $structure .= "<span class='skill $key'>";

        $is_newer_version_class = ($GLOBALS['game_version_score'] < get_game_version_score("1.6.0")) ? 'newer-version' : 'older-version';

        $structure .= "
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
            </span>" 
            . get_level_progress_bar($level) . 
            
            "<span class='level data'>$level</span>
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

function display_top_friendships(int $limit = 4):string {
    return display_friendships($limit);
}

function display_friendships(int $limit = -1):string {
	$player_id = $GLOBALS['player_id'];
	$friendship_data = $GLOBALS['all_players_data'][$player_id]['friendship'];

    $images_path = get_images_folder();

    $marriables_npc = sanitize_json_with_version('marriables');
    $villagers_json = sanitize_json_with_version('villagers');
	$birthday_json = sanitize_json_with_version('villagers_birthday');
	
    $json_with_version = sanitize_json_with_version('villagers', true);

    $section_class = ($limit == -1) ? 'all-friends' : 'top-friends';
    $view_all = ($limit == -1) ? '' : "<span class='view-all-friends view-all-friendships-$player_id modal-opener'>- View all friendships</span>";
    $structure = ($limit == -1) ? 
	"
        <section class='info-section friends-section $section_class $section_class-$player_id modal-window'>
			<div class='panel-header'>
           		<h2 class='section-title panel-title'>Friendship progression</h2>
				<img src='" . get_images_folder() . "icons/exit.png' class='exit-all-friendships-$player_id exit' alt=''/>
			</div>
            <span class='friendlist'>
    "
	:
	"
        <section class='info-section friends-section $section_class _50'>
            <span class='has_panel'>
                <h2 class='section-title'>Friendship progression</h2>
                $view_all
            </span>
            <span class='friendlist'>
    "
	;

    foreach($friendship_data as $name => $friend) {
        if($limit == 0)
            break;

        $limit--;

        extract($friend);
        $friend_icon = $images_path . "characters/" . strtolower($name) . ".png";

		$is_newer_version = (array_search($name, $json_with_version)) ? "older-version" : "newer-version";

        $is_birthday = (is_this_the_same_day($birthday)) ? "is_birthday" : "isnt_birthday";
        $birthday_date = explode('/', $birthday);
        $birthday_date = "Day " . $birthday_date[0] . " of " . $birthday_date[1];

        $wiki_url = get_wiki_link(get_item_id_by_name($name));

        $structure .= "
			<span>
                <a class='wiki_link' href='$wiki_url' target='_blank'>
				    <img src='$friend_icon' class='character-icon $is_newer_version' alt='$name icon'/>
				</a>
                <span class='character-name'>$name</span>
			    <span class='hearts-level'>
        ";

		
        $can_be_married = in_array($name, $marriables_npc) && $status == "Friendly";
        $max_heart = ($status == "Married") ? 14 : 10;

        for($i = 1; $i <= $max_heart; $i++) {

            if($i > 8 && $can_be_married) {
                $heart_icon = get_images_folder() . "icons/locked_heart.png";
                $structure .= "<img src='$heart_icon' class='hearts' alt=''/>";
                continue;
            }

            $heart_icon = get_images_folder() . (($friend_level >= $i) ? "icons/heart.png" : "icons/empty_heart.png");
            $structure .= "<img src='$heart_icon' class='hearts' alt=''/>";
        }
        
        $gifted = [];
        $gifted[0] = ($week_gifts > 0) ? "gifted" : "not-gifted";
        $gifted[1] = ($week_gifts == 2) ? "gifted" : "not-gifted";

        $structure .= "
				</span>
                <span class='tooltip'>
                    <img src='{$images_path}icons/birthday_icon.png' class='birthday_icon $is_birthday' alt=''/>
                    <span class='left'>$birthday_date</span>
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

		if(isset($friendship_data[$villager_name]))
			continue;

        $limit--;
        $friend_icon = $images_path . "characters/" . strtolower($villager_name) . ".png";

		$is_newer_version = (array_search($villager_name, $json_with_version)) ? "older-version" : "newer-version";

        $can_be_married = in_array($villager_name, $marriables_npc);

		$villager_birthday = $birthday_json[(get_custom_id($villager_name))];
        $is_birthday = (is_this_the_same_day($villager_birthday)) ? "is_birthday" : "isnt_birthday";
        $birthday_date = explode('/', $villager_birthday);
        $birthday_date = "Day " . $birthday_date[0] . " of " . $birthday_date[1];

        $wiki_url = get_wiki_link(get_item_id_by_name($villager_name));

        $structure .= "
			<span>
				<a class='wiki_link' href='$wiki_url' target='_blank'>
					<img src='$friend_icon' class='character-icon not-found $is_newer_version' alt='$villager_name icon'/>
				</a>
				<span class='character-name'>$villager_name</span>
			    <span class='hearts-level'>
        ";
		
		$status = "Unknown";
        $can_be_married = in_array($villager_name, $marriables_npc);

        for($i = 1; $i <= 10; $i++) {

            if($i > 8 && $can_be_married) {
                $heart_icon = get_images_folder() . "icons/locked_heart.png";
                $structure .= "<img src='$heart_icon' class='hearts' alt=''/>";
                continue;
            }

            $heart_icon = get_images_folder() . (($friend_level >= $i) ? "icons/heart.png" : "icons/empty_heart.png");
            $structure .= "<img src='$heart_icon' class='hearts' alt=''/>";
        }
        
        $gifted = ["not-gifted", "not-gifted"];

        $structure .= "
				</span>
                <span class='tooltip'>
                    <img src='{$images_path}icons/birthday_icon.png' class='birthday_icon $is_birthday' alt=''/>
                    <span class='left'>$birthday_date</span>
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
        </span>
    </section>";

    return $structure;
}

function display_unlockables():string {
	$player_unlockables = $GLOBALS['all_players_data'][$GLOBALS['player_id']]['has_element'];
	
	$version_score = $GLOBALS['game_version_score'];

    $images_path = get_images_folder() . "unlockables/";
	$decoded_unlockables = $GLOBALS['json']['unlockables'];

    $structure = "
        <section class='gallery unlockables-section _50'>
            <h2 class='section-title'>Unlockables</h2>
            <span>
				<h3 class='no-spoil-title'>Nothing to see here yet</h3>
    ";

	foreach($decoded_unlockables as $version => $unlockables) {

        $is_newer_version_class = ($version_score < get_game_version_score($version)) ? 'newer-version' : 'older-version';
        
		foreach($unlockables as $unlockable) {
			$formatted_name = formate_text_for_file($unlockable);
			if(!isset($player_unlockables[$formatted_name]['is_found']))
				continue;
	
			$unlockable_class = ($player_unlockables[$formatted_name]['is_found']) ? "found" : "not-found";
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

function display_detailled_gallery(array $player_datas, string $json_filename, string $section_title, string $width, bool $has_panel = false, array $panel_details = array()):string {
    
	$version_score = $GLOBALS['game_version_score'];

	$images_path = get_images_folder() . "$json_filename/";
    $json_datas = $GLOBALS['json'][$json_filename];

    extract($panel_details);
    $player_id = $GLOBALS['player_id'];
    $title = ($has_panel) ?
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
    
    foreach($json_datas as $key => $json_version) {
        
        $is_newer_version_class = ($version_score < get_game_version_score($key)) ? 'newer-version' : 'older-version';
        
        foreach($json_version as $json_line_name) {

    
            $is_found = array_key_exists($json_line_name, $player_datas);

            $element_class   = ($is_found) ? 'found' : 'not-found';

            // Wilderness Golem désactivé si pas la ferme wilderness
            if($json_filename == 'enemies' && $json_line_name == 'Wilderness Golem' && $GLOBALS['should_spawn_monsters'] == 'false')
                continue;

            if(in_array($json_filename, array('cooking_recipes', 'crafting_recipes', 'artifacts', 'minerals'))) 
                if($is_found && $player_datas[$json_line_name]['counter'] == 0)
                    $element_class .= ' unused';

            $element_image = $images_path . formate_text_for_file((string) explode('µ', $json_line_name)[0]). '.png';
            $element_tooltip = ($is_found) ? get_tooltip_text($player_datas, $json_line_name, $json_filename) : $json_line_name;

            if(!in_array($json_filename, array('achievements')))
                $wiki_url = get_wiki_link(get_item_id_by_name($json_line_name));
            else 
                $wiki_url = 'https://stardewvalleywiki.com/Achievements';
            

            $structure .= "
                <span class='tooltip'>
			";

			$structure .= '<a class="wiki_link" href="' . $wiki_url . '" target="_blank">';

			$structure .= "
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

function display_festival_icon():string {
    $festivals = sanitize_json_with_version('festivals', true);

	$festival_name = "Not a festival day";
	$festival_class = "isnt_festival";

	foreach($festivals as $key => $festival) {
		for($i = 0; $i < count($festival['date']); $i++) {
			if(is_this_the_same_day($festival['date'][$i])) {
				$festival_class = "is_festival";
				$festival_name = $festival['name'];
				$wiki_url = get_wiki_link($key);
				break;
			}
		}
	}


	return (isset($wiki_url)) 
    ? 
	"<span class='tooltip'>
		<a class='wiki_link' href='$wiki_url' target='_blank'>
			<img src='" . get_images_folder() . "/icons/festival_icon.gif' class='festival_icon $festival_class' alt='Festival icon'>
		</a>
		<span class='right'>$festival_name</span>
	</span>"
	:
	"<span class='tooltip'>
        <a class='wiki_link' href='https://stardewvalleywiki.com/Festivals' target='_blank'>
		    <img src='" . get_images_folder() . "/icons/festival_icon.png' class='festival_icon $festival_class' alt='Festival icon'>
		</a>
        <span class='right'>$festival_name</span>
	</span>";
}

function display_books():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['books'], 'books', 'Books', "_50");
}

function display_fish():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['fish_caught'], 'fish', 'Fish caught', "_50");
}

function display_cooking_recipes():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['cooking_recipes'], 'cooking_recipes', 'Cooking recipes', "_50");
}

function display_minerals():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['minerals_found'], 'minerals', 'Minerals', "_50");
}

function display_artifacts():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['artifacts_found'], 'artifacts', 'Artifacts', "_50");
}

function display_enemies():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    $panel_details = array(
        "panel_alt"     => "monster-eradication-goals",
        "panel_name"    => "Monster Eradication Goals"
    );
    return display_detailled_gallery($datas['enemies_killed'], 'enemies', 'Enemies killed', "_50", true, $panel_details);
}

function display_monster_eradication_goals_panel():string {
	$player_id = $GLOBALS['player_id'];
    $goals_data = get_adventurers_guild_data($player_id);
    $goals = '';

    foreach($goals_data as $goal_data) {
        if(is_bool($goal_data))
            continue;

        extract($goal_data);
        extract($reward);

        $wiki_link = get_wiki_link(get_item_id_by_name($alt));

        $is_found = ($counter < $limit) ? "not-found" : "found";
        $reward_icon = "
            <span class='tooltip' style='display: flex;'>
                <a class='wiki_link' href='$wiki_link' target='_blank'>
                    <img src='" . get_images_folder() . "rewards/$src.png' alt='$alt' class='reward $is_found always-on-display'/>
                </a>
                <span class='right'>$alt</span>
            </span>
        ";

        $is_completed_icon = ($is_completed) ? "<img src='" . get_images_folder() . "content/goal_star.png' class='star'>" : "";
        $total = ($is_completed) ? $counter : "$counter/$limit";
        $goals .= "<span class='goal'>$reward_icon $total $target $is_completed_icon</span>";
    }

    return "
        <section class='monster-eradication-goals-section info-section monster-eradication-goals-$player_id modal-window'>
            <span class='header'>
                <img src='" . get_images_folder() . "content/dashes.png' class='dashes' alt=''>
                <span class='title'>
                    <span>&emsp;&emsp;&emsp;&emsp;--Monster Eradication Goals</span>
                    <span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;\"Help us keep the valley safe.\"</span>
                </span>
                <img src='" . get_images_folder() . "content/dashes.png' class='dashes' alt=''>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-monster-eradication-goals exit-monster-eradication-goals-$player_id exit' alt=''>
            </span>
            <span class='goals'>
                $goals
            </span>
        </section>
    ";
}

function display_achievements():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['achievements'], 'achievements', 'In-game achievements', "_50");
}

function display_shipped_items():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['shipped_items'], 'shipped_items', 'Shipped items', "_100");
}

function display_crafting_recipes():string {
	$datas = $GLOBALS['all_players_data'][$GLOBALS['player_id']];
    return display_detailled_gallery($datas['crafting_recipes'], 'crafting_recipes', 'Crafting recipes', "_100");
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
				<img src='$skill_icon_path' alt='$skill_description'/>
				<span>$skill_description</span>
			</span>
			";
        }
    }

    $structure .= "</div>";

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

        case 'cooking_recipes' :
            if(!$counter) return "$json_line_name : not cooked yet";
            return "$json_line_name : cooked " . (int) $counter . " times";

		case 'crafting_recipes' :
			if(!$counter) return "$json_line_name : not crafted yet";
			return "$json_line_name : crafted " . (int) $counter . " times";

        case 'achievements' :
            return "$json_line_name : $description";

        case 'artifacts':
        case 'minerals':  
            if($counter == 0) return "$json_line_name : not given yet";
            return "$json_line_name : given to museum";

        default : return $json_line_name;
    }
}

function get_farmer_level():string {
	$data = $GLOBALS['untreated_player_data'];

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

    $level = (get_total_skills_level($data) + $data->luckLevel) / 2;

    return $level_names[floor($level / 2)];
}
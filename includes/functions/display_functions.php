<?php 

function display_landing_page():string {
    $save_button = display_save_button();
    $save_panel = display_save_panel();

    $settings_button = display_settings_button("landing");
    $settings_panel = display_settings_panel();


    return "
        <script src='" . get_site_root() ."script/functions.js' defer></script>
        <div id='landing' class='sur-header'>
            $save_button
            $settings_button
        </div>
        $save_panel
        $settings_panel
        <div id='landing-page'>
            <main>
                <h2 class='section-title'>Welcome to Stardew Dashboard</h2>
                <section class='project-description'>
                    <h2 class='section-title'>What is Stardew Dashboard?</h2>
                    <span>
                        <span>
                            Are you an avid farmer in Stardew Valley looking to optimize your gameplay experience? Look no further! Stardew Dashboard is your ultimate companion for managing your farm and tracking your progress.
                        </span>
                        <span>
                            Upload your game save file effortlessly and gain access to a wealth of information about your farm, from tracking your progress in mastering recipes to discovering new elements of the game world. With our intuitive interface, staying on top of your farm's needs and exploring all that Stardew Valley has to offer has never been easier.
                        </span>
                        <span>
                            Whether you're a seasoned veteran or just starting out, Stardew Dashboard is here to enhance your Stardew Valley experience. Join our community today and take your farming to the next level!
                        </span>
                    </span>
                </section>
                <section class='how-to-use-it'>
                    <h2 class='section-title'>How to use it</h2>
                    <span>
                        <span>
                            To start using Stardew Dashboard, retrieve your backup
                            <code>(C:\Users\UserName\AppData\Roaming\StardewValley\Saves\SaveName).</code>
                            The backup file is the one with the same name as your backup.
                        </span>
                        <span>
                            Well done! The hardest part is done! Now you just have to upload
                            <span class='img-embed landing-upload'>
                                <img src='" . get_images_folder() ."icons/file.png'>
                            </span>
                            your backup directly to our site and let the magic happen.
                        </span>
                        <span>
                            There's also a range of options
                            <span class='img-embed landing-settings'>
                                <img src='" . get_images_folder() ."icons/settings.png'>
                            </span>
                            to custom your experience!
                        </span>
                    </span>
                </section>
                <section class='about'>
                    <h2 class='section-title'>About us</h2>
                    <span>
                        <span>
                            Stardew Dashboard is a project by two French students in their third year of a bachelor's degree in web development.
                            Created during our spare time, this website serves as a tool for us to conveniently track our progress in Stardew Valley. 
                        </span>
                    </span>
                    <span class='characters'>
                        <span>
                            <img src='" . get_images_folder() ."content/romain.png' class='character-image'>
                            <span>
                                <span class='character-presentation'>
                                    <span>
                                        Romain is a hard-working web developer. He loves taking on challenges and always going the extra mile. 
                                    </span>
                                    <span>
                                        He took care of the front-end integration, and helped Nicolas with the back-end.
                                    </span>
                                </span>
                                <span class='socials'>
                                    <a href='https://github.com/BreadyBred' target='_blank'><img src='" . get_images_folder() ."social/github.png'></a>
                                    <a href='https://www.linkedin.com/in/romain-gerard/' target='_blank'><img src='" . get_images_folder() ."social/linkedin.png'></a>
                                </span>
                            </span>
                        </span>
                        <span>
                            <img src='" . get_images_folder() ."content/nico.png' class='character-image'>
                            <span>
                                <span class='character-presentation'>
                                    <span>
                                        Nicolas is a young man with a passion for development and sleeping.
                                    </span>
                                    <span>
                                        He took care of the UX / UI design, as well as the Back-End of the website.
                                    </span>
                                </span>
                                <span class='socials'>
                                    <a href='https://github.com/NicolasVero' target='_blank'><img src='" . get_images_folder() ."social/github.png'></a>
                                    <a href='https://www.linkedin.com/in/nicolas-vero/' target='_blank'><img src='" . get_images_folder() ."social/linkedin.png'></a>
                                </span>
                            </span>
                        </span>
                    </span>
                </section>
            </main>
        </div>
        <img src='" . get_images_folder() . "content/loading.png' id='loading-strip' class='loading'>
    ";
}

function display_page(array $all_datas, array $players):string {

    $structure = "";

    $structure .= display_sur_header($all_datas['general']['game_version'], $players);
    $structure .= display_header($all_datas['general']);
    $structure .= "<main>";
    $structure .= display_general_stats($all_datas['general']);
    $structure .= display_quests($all_datas['quest_log']);
    $structure .= display_skills(array(
            'levels'    => $all_datas['levels'], 
            'skills'    => $all_datas['skills'],
            'masteries' => $all_datas['masteries']
        )
    );
    $structure .= display_top_friendships($all_datas['friendship'], 4);
    $structure .= display_friendships($all_datas['friendship']);

    $structure .= "<div class='separated-galleries'>";
        $structure .= display_unlockables($all_datas['has_element']);
        $structure .= display_gallery($all_datas['books'], 'books', 'Books');
        $structure .= display_detailled_gallery($all_datas['fish_caught'], 'fish', 'Fish caught');
        $structure .= display_detailled_gallery($all_datas['cooking_recipes'], 'recipes', 'Cooking recipes');
        $structure .= display_detailled_gallery($all_datas['minerals_found'], 'minerals', 'Minerals');
        $structure .= display_detailled_gallery($all_datas['artifacts_found'], 'artifacts', 'Artifacts');
        $structure .= display_detailled_gallery($all_datas['enemies_killed'], 'enemies', 'Enemies killed');
        $structure .= display_detailled_gallery($all_datas['achievements'], 'achievements', 'Achievements');
    $structure .= "</div>";

    $structure .= display_gallery($all_datas['shipped_items'], 'shipped_items', 'Shipped items');


    $structure .= "</main>";

    return $structure;
}

function display_sur_header(string $game_version, array $players):string {

    $structure = "<div class='sur-header'>";
        $structure .= display_player_selection($players);
        $structure .= "<span>";
            $structure .= display_game_version($game_version);
            $structure .= display_secondary_upload();
            $structure .= display_settings_button();
        $structure .= "</span>";
    $structure .= "</div>";

    return $structure;
}

function display_player_selection(array $players):string {
    $structure = "
		<ul id='player_selection'>
	";

    foreach($players as $player) {
        $structure .= "<li value='$player'>" . formate_usernames($player) . "</option>";
    }

    $structure .= "</ul>";
    
    return $structure;
}

function display_game_version(string $game_version):string {
    $structure = "
            <span class='game_version'>V $game_version</span>
    ";
    return $structure;
}

function display_settings_button(string $prefix = 'main'):string {
    $structure = "
        <span class='$prefix-settings'><img src='" . get_images_folder() ."icons/settings.png'></span>
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
            </span>
        </section>
    ";
}

function display_save_button():string {
    return "
        <span class='landing-upload'><img src='" . get_images_folder() ."icons/file.png'></span>
    ";
}

function display_secondary_upload():string {
    return "
        <span class='file-upload'><img src='" . get_images_folder() ."icons/file.png'></span>
    ";
}

function display_save_panel():string {
    return "
        <section class='upload-panel'>
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

function display_header(array $datas):string {
    
    extract($datas);    
    $images_path = get_images_folder();
	$farm_name = str_contains(strtolower($farm_name), 'farm') ? $farm_name : $farm_name . ' farm';
	$gender = ($gender == null) ? 'neutral' : $gender;

    $structure = "
        <header>
            <div class='header'>
                <span>
                    <img src='{$images_path}icons/" . strtolower($gender) . ".png' alt='Gender logo' class='player_gender_logo' />
                    <span class='data player_name'>$name</span>
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

function display_general_stats(array $datas):string {

    extract($datas);
    $images_path = get_images_folder();
    $deepest_mine_level = ($mine_level > 120) ? 120 : $mine_level; 
    $deepest_skull_mine_level = ($mine_level - 120 < 0) ? 0 : $mine_level - 120;
    
    $deepest_mine_level_tooltip = "$deepest_mine_level stardew mine " . (($deepest_skull_mine_level > 0) ? "+ $deepest_skull_mine_level skull mine" : "");

    $structure = "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>General stats</h2>
			<img src='" . get_images_folder() . "/icons/quest.png' class='quest-icon view-all-quests button-elements'>
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

function display_quests(array $datas):string {
    
    extract($datas);
    $images_path = get_images_folder();

    $structure = "
        <section class='quests-section info-section all-quests'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Quests in progress</h2>
                <img src='" . get_images_folder() . "icons/exit.png' class='exit-all-quests exit' />
            </div>
            <span class='quests'>
    ";

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

function display_skills(array $datas):string {
    
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

        $structure .= "
            <span class='skill $key'>

                <span class='tooltip'>
                    <img src='$mastery_icon' class='level-icon $mastery_class $mastery_visible_class' alt='$key'/>
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

function display_top_friendships(array $friends, int $limit):string {
    return display_friendships($friends, $limit);
}

function display_friendships(array $friends, $limit = -1):string {

    $images_path = get_images_folder();
    $marriables_npc = json_decode(file_get_contents(get_json_folder() . 'marriables.json'), true);

    $section_class = ($limit == -1) ? 'all-friends' : 'top-friends';
    $view_all = ($limit == -1) ? '' : "<span class='view-all view-all-friendships'>View all friendships</span>";
    $structure = ($limit == -1) ? 
	"
        <section class='info-section friends-section $section_class'>
			<div class='panel-header'>
           		<h2 class='section-title panel-title'>Friendship progression</h2>
				<img src='" . get_images_folder() . "icons/exit.png' class='exit-all-friendships exit' />
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


        $can_be_married = in_array($name, $marriables_npc['marriables']) && $friend['status'] == "Friendly";

        for($i = 1; $i <= 10; $i++) {

            if($i > 8 && $can_be_married) {
                $heart_icon = get_images_folder() . "icons/locked_heart.png";
                $structure .= "<img src='$heart_icon' class='hearts' alt='' />";
                continue;
            }

            $heart_icon = get_images_folder() . (($friend['friend_level'] >= $i) ? "icons/heart.png" : "icons/empty_heart.png");
            $structure .= "<img src='$heart_icon' class='hearts' alt='' />";
        }
        
        $gifted = [];
        $gifted[0] = ($friend['week_gifts'] > 0) ? "gifted" : "not-gifted";
        $gifted[1] = ($friend['week_gifts'] == 2) ? "gifted" : "not-gifted";

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

function display_unlockables(array $player_elements):string {
    $images_path = get_images_folder() . "unlockables/";
    $elements = json_decode(file_get_contents(get_json_folder() . 'unlockables.json'), true);
    $elements = $elements['unlockables'];
    sort($elements);

    $structure = "
        <section class='gallery unlockables-section'>
            <h2 class='section-title'>Unlockables</h2>
            <span>
    ";

	foreach($elements as $element) {
		$formatted_name = formate_text_for_file($element);
		if (!isset($player_elements[$formatted_name]))
			continue;

		$element_class = ($player_elements[$formatted_name]) ? "found" : "not-found";
		$element_image = "$images_path$formatted_name.png";
		
		$structure .= "
			<span class='tooltip'>
				<img src='$element_image' alt='$element' class='gallery-item unlockables $element_class' />
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

function display_gallery(array $player_elements, string $json_filename, string $section_title):string {
    $images_path = get_images_folder() . "$json_filename/";
    $elements = json_decode(file_get_contents(get_json_folder() . $json_filename . '.json'), true);
    sort($elements);

    $structure = "
        <section class='gallery $json_filename-section'>
            <h2 class='section-title'>$section_title</h2>
            <span>
    ";

    foreach($elements as $element) {

        $element_class = in_array($element, $player_elements) ? "found" : "not-found"; 
        $element_image = $images_path . formate_text_for_file($element) . ".png";

        $structure .= "
            <span class='tooltip'>
                <img src='$element_image' alt='$element' class='gallery-item $json_filename $element_class' />
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
    $images_path = get_images_folder() . "$json_filename/";
    $json_datas = json_decode(file_get_contents(get_json_folder() . $json_filename . '.json'), true);
    sort($json_datas);

    $structure = "
        <section class='gallery $json_filename-section'>
            <h2 class='section-title'>$section_title</h2>
            <span>
    ";
    
    foreach($json_datas as $json_line_name) {

        $is_found = array_key_exists($json_line_name, $player_datas);

        $element_class   = ($is_found) ? 'found' : 'not-found';


        if(in_array($json_filename, array('recipes', 'artifacts', 'minerals'))) 
            if($is_found && $player_datas[$json_line_name]['counter'] == 0)
                $element_class .= ' unused';


        $element_image = $images_path . formate_text_for_file((string) explode(':', $json_line_name)[0]). '.png';
        $element_tooltip = ($is_found) ? get_tooltip_text($player_datas, $json_line_name, $json_filename) : $json_line_name;


        $structure .= "
            <span class='tooltip'>
                <img src='$element_image' alt='$json_line_name' class='gallery-item $json_filename $element_class' />
                <span>$element_tooltip</span>
            </span>
        ";
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

        case ('artifacts' || 'minerals') : 
            if($counter == 0) return "$json_line_name : not given yet";
            return "$json_line_name : given to museum";

        default : return $json_line_name;
    }
}
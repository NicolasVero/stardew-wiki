<?php 

function display_page(array $all_datas, array $players):string {

    $structure = "";

    $structure .= display_player_selection($players);
    $structure .= display_header($all_datas['general']);
    $structure .= "<main>";
    $structure .= display_general_stats($all_datas['general']);
    $structure .= display_skills(array(
        'levels' => $all_datas['levels'], 
        'skills' => $all_datas['skills'])
    );
    $structure .= display_top_friendships($all_datas['friendship'], 4);
    $structure .= display_friendships($all_datas['friendship']);

	// TODO Futur Unlockable section
    $structure .= display_gallery($all_datas['has_element'], 'unlockables', 'Unlockables');

    $structure .= "<div class='separated-galleries'>";
        $structure .= display_detailled_gallery($all_datas['fish_caught'], 'fish', 'Fish caught');
        $structure .= display_detailled_gallery($all_datas['cooking_recipe'], 'recipes', 'Cooking recipes');
        $structure .= display_gallery($all_datas['minerals_found'], 'minerals', 'Minerals');
        $structure .= display_gallery($all_datas['artifacts_found'], 'artifacts', 'Artifacts');
        $structure .= display_detailled_gallery($all_datas['enemies_killed'], 'enemies', 'Enemies killed');
        $structure .= display_detailled_gallery($all_datas['achievements'], 'achievements', 'Achievements');
    $structure .= "</div>";

    $structure .= display_gallery($all_datas['shipped_items'], 'shipped_items', 'Shipped items');


    $structure .= "</main>";

    return $structure;
}


function display_player_selection(array $players):string {
    $structure = "
		<div class='sur-header'>
			<ul id='player_selection'>
	";

    foreach($players as $player) {
        $structure .= "<li value='$player' class='button-elements'>$player</option>";
    }
	/* Test username max length */
    // $structure .= "
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// 	<li class='button-elements'>WWWWWW</option>
	// ";

    $structure .= "</ul>";
    
    return $structure;
}


function display_header(array $datas):string {
    
    extract($datas);    
    $images_path = get_images_folder();
	$farm_name = str_contains(strtolower($farm_name), 'farm') ? $farm_name : $farm_name . ' farm';
	$gender = ($gender == null) ? 'neutral' : $gender;

    $structure = "
		<button class='button-elements save-file'>Upload a save file</button>
		</div>
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
                        <span class='data actual-gold'>" . number_format($golds) . "</span>
                        <span class='data-label'>golds</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/golden_walnut.png' alt='Golden walnut' />
                        <span class='data actual-golden-walnut'>" . number_format($golden_walnuts) . "</span>
                        <span class='data-label'>golden walnut</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/qi_gem.png' alt='Qi gems' />
                        <span class='data actual-qi-gem'>" . number_format($qi_gems) . "</span>
                        <span class='data-label'>qi gem</span>
                    </span>

                    <span>
                        <img src='{$images_path}icons/casino_coins.png' alt='Casino coins' />
                        <span class='data actual-golden-walnut'>" . number_format($casino_coins) . "</span>
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

    $structure = "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>General stats</h2>
			<img src='" . get_images_folder() . "/content/quest_icon.png' class='quest-icon view-all-quests button-elements'>
            <div>
                <span>
                    <img src='{$images_path}icons/energy.png' alt='Energy' />
                    <span class='data data-energy'>" . number_format($max_stamina) . "</span>
                    <span class='data-label'>max stamina</span>
                </span>

                <span>
                    <img src='{$images_path}icons/health.png' alt='Health' />
                    <span class='data data-health'>" . number_format($max_health) . "</span>
                    <span class='data-label'>max health</span>
                </span>

                <span>
                    <img src='{$images_path}icons/inventory.png' alt='Inventory' />
                    <span class='data data-inventory'>" . number_format($max_items) . "</span>
                    <span class='data-label'>max inventory</span>
                </span>

                <span>
                    <img src='{$images_path}icons/mine_level.png' alt='Mine level' />
                    <span class='data data-mine-level'>" . number_format($mine_level) . "</span>
                    <span class='data-label'>deepest mine level</span>
                </span>
            </div>
        </section>
    ";

    return $structure;
}

function display_skills(array $datas):string {
    
    $structure = "
		<section class='skills info-section'>
			<h2 class='section-title'>Skills</h2>
            <span>    
    ";

    foreach($datas['levels'] as $key => $level) {
        
        $level_icon_name = explode('_', $key)[0];
        $level_icon = get_images_folder() . "icons/$level_icon_name.png";

        $structure .= "
            <span class='skill $key'>
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
			<div>
           		<h2 class='section-title'>Friendship progression</h2>
				<img src='" . get_images_folder() . "content/exit.png' class='exit-all-friendships' />
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
				<span class='tooltip'>
					<img src='$friend_icon' class='character-icon' alt='$name icon' />
					<span>$name</span>
				</span>
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
        
        $structure .= "
				</span>
				<span class='gifts'>
					<img src='{$images_path}icons/gift.png' class='gift' alt=''/>
					<span class='gift-counter'>$week_gifts</span><span class='week'>this week</span>
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

        if($json_filename == 'recipes' && $is_found && $player_datas[$json_line_name]['cooked_count'] == 0)
            $element_class .= ' not-cooked';

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
            return "$json_line_name : caught $caught_counter times ($max_length inches)";

        case 'enemies' : 
            return "$json_line_name : $killed_counter killed";

        case 'recipes' :
            if(!$player_data[$json_line_name]['cooked_count']) return "$json_line_name : not cooked yet";
            return "$json_line_name : cooked " . (int) $player_data[$json_line_name]['cooked_count'] . " times";

        case 'achievements' :
            return "$json_line_name : $description";

         default : return $json_line_name;
    }
}
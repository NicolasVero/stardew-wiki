<?php 

function display_page(array $all_datas):string {

    $structure = "";

    $structure .= display_header($all_datas['general']);
    $structure .= "<main>";
    $structure .= display_general_stats($all_datas['general']);
    $structure .= display_skills(array(
        'levels' => $all_datas['levels'], 
        'skills' => $all_datas['skills'])
    );
    $structure .= display_top_friendships($all_datas['friendship'], 4);
    $structure .= display_friendships($all_datas['friendship']);

    $structure .= display_gallery($all_datas['fish_caught'], 'fish', 'Fish caught');
    $structure .= display_gallery($all_datas['artifacts_found'], 'artifacts', 'Artifacts');
    $structure .= display_gallery($all_datas['minerals_found'], 'minerals', 'Minerals');
    $structure .= display_gallery($all_datas['cooking_recipe'], 'recipes', 'Cooking recipes');
    $structure .= display_gallery($all_datas['shipped_items'], 'shipped_items', 'Shipped items');

    $structure .= "</main>";

    return $structure;
}

function display_header(array $datas):string {
    
    extract($datas);    
    $images_path = get_images_folder();

    $structure = "
		<button class='data save-file'>Upload a save file</button>
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
                    <span class='data-info farm-name'>$farm_name farm</span>
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
			<h2 class='section-title'>Skills</h2>";

    foreach($datas['levels'] as $key => $level) {
        
        $level_icon_name = explode('_', $key)[0];
        $level_icon = get_images_folder() . "icons/$level_icon_name.png";

        $structure .= "
            <span class='skill $key'>
                <img src='$level_icon' class='level-icon' alt='$key'/>
                
                " . get_level_progress_bar($level) . "
                <span class='level'>$level</span>
                <span>" . get_skills_icons($datas['skills'], $level_icon_name) . "</span>
            </span>
        ";
    }

    $structure .= "</section>";

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

    $structure = "<section class='skills-section'>";

    foreach($skills as $skill) {
        if($current_skill == strtolower($skill['source'])) {

            $skill_icon = strtolower($skill['skill']);
            $skill_icon_path = get_images_folder() . "skills/$skill_icon.png";
            $skill_description = $skill['description'];
            
            $structure .= "
			<span class='labeled'>
				<img src='$skill_icon_path' alt='$skill_description' />
				<span>$skill_description</span>
			</span>
			";
        }
    }

    $structure .= "</section>";

    return $structure;
}

function display_top_friendships(array $friends, int $limit):string {
    return display_friendships($friends, $limit);
}

function display_friendships(array $friends, $limit = -1):string {

    $images_path = get_images_folder();
    $marriables_npc = json_decode(file_get_contents(get_json_folder() . 'marriables.json'), true);

    $section_class = ($limit == -1) ? 'all-friends' : 'top-friends';
    $structure = "
        <section class='friends-section $section_class'>
            <h2>Friendship progression</h2>
            <div>
    ";

    foreach($friends as $name => $friend) {
        if($limit == 0)
            break;

        $limit--;

        extract($friend);
        $friend_icon = $images_path . "characters/" . strtolower($name) . ".png";


        $structure .= "
            <span class='labeled'>
                <img src='$friend_icon' alt='$name icon' />
                <span>$name</span>
            </span>  
        ";
            
        $can_be_married = in_array($name, $marriables_npc['marriables']) && $friend['status'] == "Friendly";

        for($i = 1; $i <= 10; $i++) {

            if($i > 8 && $can_be_married) {
                $heart_icon = get_images_folder() . "icons/locked_heart.png";
                $structure .= "<img src='$heart_icon' alt='' />";
                continue;
            }

            $heart_icon = get_images_folder() . (($friend['friend_level'] >= $i) ? "icons/heart.png" : "icons/empty_heart.png");
            $structure .= "<img src='$heart_icon' alt='' />";
        }
        
        $structure .= "
            <span>
                <span class='week-gifts-counter'>$week_gifts</span>
                <img src='{$images_path}icons/gift.png' alt=''/>
                <span class='friend-status'>$status</span>
            </span>
        ";
    }


    $structure .= "
        </div>
        <span class='view-all view-all-friendships'>View all friendships</span>    
    </section>";

    return $structure;
}

function display_gallery(array $player_elements, string $json_file, string $section_title):string {
    $structure = "";
    $images_path = get_images_folder() . "$json_file/";

    $elements = json_decode(file_get_contents(get_json_folder() . $json_file . '.json'), true);

    $structure .= "
        <section class='gallery $json_file-section'>
            <h2>$section_title</h2>
            <span>
    ";

    foreach($elements as $element) {

        $element_class = in_array($element, $player_elements) ? "found" : "not-found"; 
        $element_image = $images_path . formate_text_for_file($element) . ".png";

        $structure .= "
            <span class='labeled'>
                <img src='$element_image' alt='$element' class='gallery-item $json_file $element_class' />
                <span>$element</span>
            </span>
        ";
    }

    $structure .= "</span>";

    return $structure;
}
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
    
    $structure .= "</main>";

    return $structure;
}


function display_header(array $datas):string {
    
    extract($datas);    
    $images_path = get_images_folder_root();

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
    $images_path = get_images_folder_root();

    $structure = "
        <h2 class='section-title'>General stats</h2>
        <div class='info-section general-stats'>
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
    ";

    return $structure;
}


function display_skills(array $datas):string {
    
    $structure = "";

    foreach($datas['levels'] as $key => $level) {
        
        $level_icon_name = explode('_', $key)[0];
        $level_icon = get_images_folder_root() . "icons/$level_icon_name.png";

        $structure .= "
            <span class='skill $key'>
                <img src='$level_icon' alt='$key'/>
                
                " . get_level_progress_bar($level) . "
                <span>$level</span>
                <span>" . get_skills_icons($datas['skills'], $level_icon_name) . "</span>
            </span>
        ";
    }

    return $structure;
}

function get_level_progress_bar(int $level):string {

    $structure = "<span class='level-progress-bar'>";

    for($i = 1; $i <= 10; $i++) {
        if($level >= $i) $level_bar = get_images_folder_root() . (($i % 5 == 0) ? "icons/big_level.png"       : "icons/level.png");
        else             $level_bar = get_images_folder_root() . (($i % 5 == 0) ? "icons/big_level_empty.png" : "icons/level_empty.png");
        
        $structure .= "<img src='$level_bar' alt=''/>";        
    }

    $structure .= "</span>";

    return $structure;
}

function get_skills_icons(array $skills, string $current_skill):string {

    $structure = "";

    foreach($skills as $skill) {
        if($current_skill == strtolower($skill['source'])) {

            $skill_icon = strtolower($skill['skill']);
            $skill_icon_path = get_images_folder_root() . "skills/$skill_icon.png";
            $skill_description = $skill['description'];
            
            $structure .= "<img src='$skill_icon_path' alt='' title='$skill_description' />";
        }
    }

    return $structure;
}
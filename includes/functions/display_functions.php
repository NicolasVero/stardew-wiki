<?php 

function display_page(array $all_datas):string {
    
    $structure = "";
    $structure .= display_header($all_datas['general']);
    
    return $structure;
}


function display_header(array $datas):string {
    
    extract($datas);
    
    $images_path = get_images_folder_root();
    $gender_logo = $images_path . "icons/" . strtolower($gender) . ".png";

    $structure = "
        <header>
            <div class='header'>
                <span>
                    <img src='$gender_logo' alt='Gender logo' class='player_gender_logo' />
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
                <span>
                    <img src='{$images_path}/icons/gold.png' alt='Gold coin' />
                    <span class='data actual-gold'>" . number_format($gold) . "</span>
                    <span class='data-label'>actual gold</span>
                </span>

                <span>
                    <img src='{$images_path}icons/gold.png' alt='Gold coin' />
                    <span class='data cumulative-gold'>" . number_format($total_gold) . "</span>
                    <span class='data-label'>cumulative gold</span>
                </span>
            </div>
        </header>
    ";

    return $structure;
}
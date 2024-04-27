<?php 

function display_page(array $all_datas):string {
    
    $structure = "";
    $structure .= display_header($all_datas['general']);
    
    return $structure;
}


function display_header(array $datas):string {
    
    log_(get_images_folder_root());
    log_($datas);
    
    $images_path = get_images_folder_root();

    extract($datas);

    $gender_logo = $images_path . "icons/" . strtolower($gender) . ".png";

    echo $money;

    $structure = "
        <header>
            <div class='header'>
                <span>
                    <img src='$gender_logo' alt='Gender logo' class='player_gender_logo' />
                    <span class='player_name'>$name</span>
                </span>
            </div>
            <div class='sub-header'></div>
        </header>
    ";

    return $structure;
}
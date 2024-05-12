<?php 

require 'display_section_functions.php';

function display_landing_page():string {
    $save_button     = display_save_button();
    $save_panel      = display_save_panel();

    $settings_button = display_settings_button("landing");
    $settings_panel  = display_settings_panel();


    return "
        <div id='landing' class='sur-header'>
            $save_button
            $settings_button
        </div>
        $save_panel
        $settings_panel
        <div id='landing-page'>
            <main>
                <h1 class='section-title'>Welcome to Stardew Dashboard</h1>
                <section class='project-description'>
                    <h2 class='section-title'>What is Stardew Dashboard?</h2>
                    <span>
                        <span>
                            Are you an avid farmer in Stardew Valley looking to optimize your gameplay experience? Look no further! Stardew Dashboard is your ultimate companion to manage your farm and track your progress.
                        </span>
                        <span>
                            Upload your game save file effortlessly and gain access to a wealth of information about your farm, from tracking your progress in mastering recipes to discovering new elements of the game world. With our intuitive interface, staying on top of your farm's needs and exploring everything that Stardew Valley has to offer has never been easier.
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
                            To start using Stardew Dashboard, retrieve your save
                            <code id='save_os_path'>(C:\Users\UserName\AppData\Roaming\StardewValley\Saves\SaveName).</code>
                            The save file is the one with the same name as your folder.
                        </span>
                        <span>
                            Well done! The hardest part is behind us! Now you just have to upload
                            <span class='img-embed landing-upload'>
                                <img src='" . get_images_folder() ."icons/file.png'>
                            </span>
                            your save directly to our website and let the magic happen.
                        </span>
                        <span>
                            There's also a range of settings
                            <span class='img-embed landing-settings'>
                                <img src='" . get_images_folder() ."icons/settings.png'>
                            </span>
                            to customize your experience!
                        </span>
                    </span>
                </section>
                <section class='about'>
                    <h2 class='section-title'>About us</h2>
                    <span>
                        <span>
                            Stardew Dashboard is a project made by two French students in their third year of a bachelor's degree in web development.
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
                                        He took care of the Front-End, and helped Nicolas with the Back-End.
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
                                        Nicolas is a young man with a passion for development, sleep, and who loves to make web development during his weekends. 
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
    $structure .= display_skills($all_datas);
    $structure .= display_top_friendships($all_datas['friendship'], 4);
    $structure .= display_friendships($all_datas['friendship']);

    $structure .= "<div class='separated-galleries'>";
        $structure .= display_unlockables($all_datas['has_element']);
        $structure .= display_books($all_datas);
        $structure .= display_fish($all_datas);
        $structure .= display_cooking_recipes($all_datas);
        $structure .= display_minerals($all_datas);
        $structure .= display_artifacts($all_datas);
        $structure .= display_enemies($all_datas);
        $structure .= display_achievements($all_datas);
    $structure .= "</div>";

    $structure .= display_shipped_items($all_datas);

    $structure .= "</main>";

    return $structure;
}

function display_error_page(Exception $exception):string {

    $exception_dialogues = array(
        "Error loading file." => array(
            "dialogue" => "Oh, bother! It seems like the file got lost in the mines. Could you try again? Or perhaps seek help from a trusty adventurer to retrieve it?",
            "image"    => "dialogue_box_dwarf"
        ),
        "Error downloading file." => array(
            "dialogue" => "Oops! Looks like the file is playing hide and seek in the shadows. Maybe a stealthier approach is needed to capture it. Keep your eyes peeled, friend!",
            "image"    => "dialogue_box_henchman"
        ),
        "Invalid file size." => array(
            "dialogue" => "Hold up there! The file size seems a bit too hefty for our cozy little village. Let's trim it down a tad before trying to squeeze it through the gate, shall we?",
            "image"    => "dialogue_box_bouncer"
        ),
        "File not conforming to a Stardew Valley save." => array(
            "dialogue" => "Ah, shucks! This file doesn't quite match the charm of Stardew Valley. It's like trying to plant a melon seed in winter – just won't work! Let's find a file more in tune with the rhythm of the seasons, shall we?",
            "image"    => "dialogue_box_grandpa"
        )
    );

    extract($exception_dialogues[$exception->getMessage()]);

    $save_button = display_secondary_upload();
    $settings_button = display_settings_button();

    $strucure = "
        <div id='landing' class='sur-header'>
            $save_button
            $settings_button
        </div>
        <div class='error-wrapper'>
            <div class='dialogue-box-error-container'>
                <img src='" . get_images_folder() . "dialogue_boxes/$image.png' alt='$image' />
                <span>$dialogue</span>
            </div>
        </div>
    ";

    return $strucure;
}
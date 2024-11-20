<?php

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

                    <input type='submit' value='Send feedback'>
                </form>
            </span>
        </section>
    ";
}

function display_monster_eradication_goals_panel():string {
	$player_id = $GLOBALS["player_id"];
    $goals_data = get_player_adventurers_guild_data($player_id);
    $goals = "";

    foreach($goals_data as $goal_data) {
        if(is_bool($goal_data)) {
            continue;
        }

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

function display_calendar_panel():string {
	$player_id = $GLOBALS["player_id"];
    $season = get_player_season();
    $all_dates = $GLOBALS["json"]["all_dates"];
    $week_count = 4;
    $day_count = 7;

    $table_structure = "
        <table>
            <tbody>";

    for($lines = 0; $lines < $week_count; $lines++) {
        $table_structure .= "<tr>";

        for($columns = 1; $columns <= $day_count; $columns++) {
            $day_digit = ($lines * $day_count) + $columns;
            $date = $day_digit . "/" . $season;

            if(array_key_exists($date, $all_dates)) {
                $wiki_link = (is_array($all_dates[$date])) ?
                    [
                        get_wiki_link(get_custom_id($all_dates[$date][0])),
                        get_wiki_link(get_custom_id($all_dates[$date][1]))
                    ]
                    :
                    get_wiki_link(get_custom_id($all_dates[$date]));
                $table_structure .= (is_array($all_dates[$date])) ?
                    "<td class='double-event'>
                        <div>
                            <a href='" . $wiki_link[0] . "' class='wiki_link' target='_blank'></a>
                        </div>
                        <div>
                            <a href='" . $wiki_link[1] . "' class='wiki_link' target='_blank'></a>
                        </div>
                    </td>"
                    :
                    "<td class='simple-event'>
                        <div>
                            <a href='$wiki_link' class='wiki_link' target='_blank'></a>
                        </div>
                    </td>";
            } else {
                $table_structure .= "
                <td class='simple-event'>
                    <div></div>
                </td>";
            }

        }

        $table_structure .= "</tr>";
    }

    $table_structure .= "
            </tbody>
        </table>";

    return "
        <section class='calendar-section info-section calendar-$player_id modal-window'>
            <span class='calendar-block'>
                <img src='" . get_images_folder() . "content/calendar_$season.png'>
                $table_structure
            </span>
        </section>
    ";
}

function display_farm_animals_panel():string {
    $player_id = $GLOBALS["player_id"];
    $animals_friendship = $GLOBALS["all_players_data"][$player_id]["farm_animals"];
    $images_path = get_images_folder();
    $friendships = "";

    foreach($animals_friendship as $animal_friendship) {
        extract($animal_friendship);

        foreach($animals_data as $animal_data) {
            extract($animal_data);

            $formatted_name = formate_usernames($name);
            $formatted_type = formate_text_for_file($type);
            $wiki_url = get_wiki_link($id);
            $animal_icon = "{$images_path}farm_animals/$formatted_type.png";
            $pet_class = ($was_pet) ? "pet" : "not-petted";
            $pet_tooltip = ($was_pet) ? "Caressed by the auto-petter" : "No auto-petter in this building";
            $status = ($happiness > 200) ? "happy" : (($happiness > 30) ? "fine" : "sad");
            $status_icon = "{$images_path}icons/{$status}_emote.png";


            $hearts_html = "";
            $max_heart = 5;
            for($i = 1; $i <= $max_heart; $i++) {
                $heart_icon = 
                (($friendship_level >= $i) ?
                    "heart.png" :
                        (($friendship_level == ($i - 0.5)) ?
                            "half_heart.png" : "empty_heart.png"));
                $hearts_html .= "<img src='{$images_path}icons/$heart_icon' class='hearts' alt=''/>";
            }

            $friendships .= "
            <span>
                <a class='wiki_link' href='$wiki_url' target='_blank'>
                    <img src='$animal_icon' class='animal-icon' alt='$type icon'/>
                </a>
                <span class='animal-name'>$formatted_name</span>
                <span class='hearts-level'>$hearts_html</span>
                <span class='interactions'>
                    <span class='tooltip'>
                        <img src='{$images_path}icons/pet.png' class='interaction $pet_class' alt=''/>
                        <span class='left'>$pet_tooltip</span>
                    </span>
                    <span class='tooltip'>
                        <img src='$status_icon' class='status' alt='$status'/>
                        <span class='left'>" . get_animal_status_tooltip($status, $formatted_name) . "</span>
                    </span>
                </span>
            </span>";
        }
    }

    $structure = "       
        <section class='info-section all-animals-section all-animals-$player_id modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Farm animals friendships</h2>
                <img src='{$images_path}icons/exit.png' class='exit-all-animals-$player_id exit' alt=''/>
            </div>
            <span class='friendlist'>
                $friendships
            </span>
        </section>
        ";

    return $structure;
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "display_feedback_panel") {
    require "utility_functions.php";
	echo display_feedback_panel();
}
<?php

function display_save_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='upload-panel panel to-keep-open modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Upload a save</h2>
                <img src='$images_path/icons/exit.png' class='exit-upload exit' alt='Exit'/>
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

function display_settings_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='settings-panel panel settings modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Settings</h2>
                <img src='$images_path/icons/exit.png' class='exit-settings exit' alt='Exit'/>
            </div>
            <span class='checkboxes'>
                <span class='checkbox'>
                    <input type='checkbox' id='spoil_mode'>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='spoil_mode' id='spoil-label'>Hide discovered items</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='no_spoil_mode'>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='no_spoil_mode' id='no-spoil-label'>Hide undiscovered items</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='steam_achievements'>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='steam_achievements' id='steam_achievements-label'>Show Steam achievements icons</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='toggle_versions_items_mode' checked>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='toggle_versions_items_mode' id='toggle-versions-items-label'>Hide items from newer versions</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='wiki_redirections' checked>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='wiki_redirections' id='wiki_redirections-label'>Activate wiki redirections</label>
                </span>
            </span>
        </section>
    ";
}

function display_visited_locations(): string {
    $player_id = get_current_player_id();
    $visited_locations = get_locations_visited_data();
    $images_path = get_images_folder();
    $json_data = $GLOBALS["json"]["locations_to_visit"];

    $locations = "";
    foreach($json_data as $key => $json_version) {
        foreach($json_version as $json_line_name) {
            $is_found = array_key_exists($json_line_name, $visited_locations);
            $element_class = ($is_found) ? "found" : "not-found";

            $wiki_link = get_wiki_link(get_item_id_by_name($json_line_name));
            $locations .= "
                <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                    <span class='$element_class'>$json_line_name</span>
                </a>
            ";
        }
    }

    return "
        <section class='visited-locations-$player_id panel visited-locations-panel modal-window'>
             <span class='header'>
                <span class='title'>
                    <span>Visited Locations</span>
                </span>
                <img src='$images_path/content/dashes.png' class='dashes' alt=''/>
                <img src='$images_path/icons/exit.png' class='exit-monster-eradication-goals exit-monster-eradication-goals-$player_id exit' alt='Exit'/>
            </span>
            <span class='locations'>
                $locations
            </span>
        </section>
    ";
}

function display_quest_panel(): string {
	$player_id = get_current_player_id();
	$this_player_data = get_quest_log_data();
    $images_path = get_images_folder();
    $quest_structure = "";

	if(empty($this_player_data)) {
        return "
            <section class='all-quests-$player_id panel quests-panel modal-window'>
                <div class='panel-header'>
                    <h2 class='section-title panel-title'>Quests in progress</h2>
                    <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'/>
                </div>
                <span class='quests'>
					<h3>" . no_items_placeholder() . "</h3>
				</span>
			</section>
        ";
	}

    foreach($this_player_data as $data) {
		extract($data);

        $quest_structure .= "
            <span class='quest'>
                <span class='quest-infos'>
                    <span class='quest-description'>$objective</span>
                    <span class='quest-title'>$title</span>
                </span>
        ";

        if(empty($rewards)) {
			$quest_structure .= "</span>";
			continue;
		}
        
		if(isset($daysLeft)) {
			$day_text = ($daysLeft > 1) ? "days" : "day";
			$quest_structure .= " <span class='days-left'><img src='$images_path/icons/timer.png' alt='Time left'/>$daysLeft $day_text</span>";
		}

		$quest_structure .= "<span class='quest-rewards'>";
		
        for($i = 0; $i<count($rewards); $i++) {
			// Reward tooltip (pas besoin pourgold and qi gems)
            $quest_structure .= ((is_numeric($rewards[$i]) || str_ends_with($rewards[$i], 'q'))) ? "<span class='quest-reward'>" : "<span class='quest-reward tooltip'>";
            
			/*
            if Friendship hearts/points
			elseif Gold
			elseif Qi Gems
			elseif something else
            */
            if(strstr($rewards[$i], "Friendship")) {
                $reward_number = explode(" ", $rewards[$i])[0];
                $quest_structure .= "<img src='$images_path/rewards/heart_$reward_number.png' alt='Friendship reward'/>";
            } elseif(is_numeric($rewards[$i])) {
                $quest_structure .= formate_number($rewards[$i]) . "<img src='$images_path/rewards/gold.png' alt='Gold coins reward'/>";
            } elseif(str_ends_with($rewards[$i], 'q')) {
                $quest_structure .= explode('_', $rewards[$i])[0] . "<img src='$images_path/rewards/qi_gem.png' alt='Qi gems reward'/>";
            } else {
                $quest_structure .= $rewards[$i];
            }

            $quest_structure .= (is_numeric($rewards[$i])) ? "" : "<span>$rewards[$i]</span>";
            $quest_structure .= "</span>";
        }

        $quest_structure .= "
                </span>
            </span>
        ";
    }

    return "
        <section class='all-quests-$player_id panel quests-panel modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Quests in progress</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'/>
            </div>
            <span class='quests'>
                $quest_structure
            </span>
        </section>
    ";
}

function display_feedback_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='feedback-panel panel modal-window to-destroy'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Your feedback</h2>
                <img src='$images_path/icons/exit.png' class='exit-feedback exit' alt='Exit'/>
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
                                <img src='$images_path/icons/feature.png' class='feedback_custom_radio' alt='Feature request topic'/>
                                <label for='feature_request'>Feature request</label>
                            </span>

                            <span>
                                <input type='radio' id='bug_report' value='Bug report' name='topic' class='feedback_real_radio'>
                                <img src='$images_path/icons/bug.png' class='feedback_custom_radio topic_not_selected' alt='Bug report topic'/>
                                <label for='bug_report'>Bug report</label>
                            </span>

                            <span>
                                <input type='radio' id='other' value='Other' name='topic' class='feedback_real_radio'>
                                <img src='$images_path/icons/other.png' class='feedback_custom_radio topic_not_selected' alt='Other topic'/>
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

function display_monster_eradication_goals_panel(): string {
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
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
        $total = ($is_completed) ? $counter : "$counter/$limit";
        $is_completed_icon = ($is_completed) ? "<img src='$images_path/content/goal_star.png' class='star' alt=''/>" : "";
        $reward_icon = "
            <span class='tooltip' style='display: flex;'>
                <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                    <img src='$images_path/rewards/$src.png' class='reward $is_found always-on-display' alt='$alt'/>
                </a>
                <span>$alt</span>
            </span>
        ";

        $goals .= "<span class='goal'>$reward_icon $total $target $is_completed_icon</span>";
    }

    return "
        <section class='monster-eradication-goals-$player_id panel monster-eradication-goals-panel modal-window'>
            <span class='header'>
                <span class='title'>
                    <span>Monster Eradication Goals</span>
                    <span>Help us keep the valley safe.</span>
                </span>
                <img src='$images_path/content/dashes.png' class='dashes' alt=''/>
                <img src='$images_path/icons/exit.png' class='exit-monster-eradication-goals exit-monster-eradication-goals-$player_id exit' alt='Exit'/>
            </span>
            <span class='goals'>
                $goals
            </span>
        </section>
    ";
}

function display_calendar_panel(): string {
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
    $season = get_player_season();
    $all_dates = $GLOBALS["json"]["all_dates"];
    $villagers = sanitize_json_with_version("villagers");
    $week_count = 4;
    $day_count = 7;

    $table_structure = "";

    for($lines = 0; $lines < $week_count; $lines++) {
        $table_structure .= "<tr>";

        for($columns = 1; $columns <= $day_count; $columns++) {
            $day_digit = ($lines * $day_count) + $columns;
            $date = "$day_digit/$season";

            if(!array_key_exists($date, $all_dates)) {
                $table_structure .= "
                <td class='simple-event not-filled'>
                    <span></span>
                </td>";

                continue;
            }

            if(!is_array($all_dates[$date])) {
                $wiki_link = get_wiki_link(get_custom_id($all_dates[$date]));
                $calendar_tooltip = (in_array($all_dates[$date], $villagers)) ? $all_dates[$date] . "'s Birthday" : $all_dates[$date];
                $table_structure .= "
                    <td class='simple-event filled'>
                        <span class='calendar-tooltip tooltip'>
                            <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'></a>
                            <span>$calendar_tooltip</span>
                        </span>
                    </td>
                ";

                continue;
            }

            $wiki_link = [
                get_wiki_link(get_custom_id($all_dates[$date][0])),
                get_wiki_link(get_custom_id($all_dates[$date][1]))
            ];
            $calendar_tooltip = [
                (in_array($all_dates[$date][0], $villagers)) ? $all_dates[$date][0] . "'s Birthday" : $all_dates[$date][0],
                (in_array($all_dates[$date][1], $villagers)) ? $all_dates[$date][1] . "'s Birthday" : $all_dates[$date][1]
            ];

            $table_structure .= "
                <td class='double-event filled'>
                    <span class='calendar-tooltip tooltip'>
                        <a href='" . $wiki_link[0] . "' class='wiki_link' rel='noreferrer' target='_blank'></a>
                        <span class='left'>" . $calendar_tooltip[0] . "</span>
                    </span>
                    <span class='calendar-tooltip tooltip'>
                        <a href='" . $wiki_link[1] . "' class='wiki_link' rel='noreferrer' target='_blank'></a>
                        <span class='right'>" . $calendar_tooltip[1] . "</span>
                    </span>
                </td>;
            ";
            
        }

        $table_structure .= "</tr>";
    }

    return "
        <section class='calendar-$player_id panel calendar-panel modal-window'>
            <span class='calendar-block'>
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-calendar-$player_id' alt='Exit'/>
                <img src='$images_path/content/calendar_$season.png' class='calendar-bg' alt='Calendar background'/>
                <table>
                    <tbody>
                        $table_structure
                    </tbody>
                </table>
            </span>
        </section>
    ";
}

function display_farm_animals_panel(): string {
	$player_id = get_current_player_id();
    $animals_friendship = get_farm_animals_data();
    $images_path = get_images_folder();
    $farm_animals_structure = "";

    if(empty($animals_friendship)) {
        return "
            <section class='all-animals-$player_id panel all-animals-panel modal-window'>
                <div class='panel-header'>
                    <h2 class='section-title panel-title'>Farm animals friendships</h2>
                    <img src='$images_path/icons/exit.png' class='exit-all-animals-$player_id exit' alt='Exit'/>
                </div>
                <span class='friendlist'>
			        <h3>" . no_items_placeholder() . "</h3>
                </span>
            </section>
        ";
    }

    foreach($animals_friendship as $animal_friendship) {
        extract($animal_friendship);

        foreach($animals_data as $animal_data) {
            extract($animal_data);

            $formatted_name = formate_usernames($name);
            $formatted_type = formate_text_for_file($type);
            $wiki_url = get_wiki_link($id);
            $animal_icon = "$images_path/farm_animals/$formatted_type.png";
            $pet_class = ($was_pet) ? "pet" : "not-petted";
            $pet_tooltip = ($was_pet) ? "Caressed by the auto-petter" : "No auto-petter in this building";
            $status = ($happiness > 200) ? "happy" : (($happiness > 30) ? "fine" : "sad");
            $status_icon = "$images_path/icons/{$status}_emote.png";


            $hearts_html = "";
            $max_heart = 5;
            for($i = 1; $i <= $max_heart; $i++) {
                $heart_icon = 
                (($friendship_level >= $i) ?
                    "heart.png" :
                        (($friendship_level === ($i - 0.5)) ?
                            "half_heart.png" : "empty_heart.png"));
                $hearts_html .= "<img src='$images_path/icons/$heart_icon' class='hearts' alt=''/>";
            }

            $farm_animals_structure .= "
                <span>
                    <a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$animal_icon' class='animal-icon' alt='$type icon'/>
                    </a>
                    <span class='animal-name'>$formatted_name</span>
                    <span class='hearts-level'>$hearts_html</span>
                    <span class='interactions'>
                        <span class='tooltip'>
                            <img src='$images_path/icons/pet.png' class='interaction $pet_class' alt=''/>
                            <span>$pet_tooltip</span>
                        </span>
                        <span class='tooltip'>
                            <img src='$status_icon' class='status' alt='$status'/>
                            <span>" . get_animal_status_tooltip($status, $formatted_name) . "</span>
                        </span>
                    </span>
                </span>
            ";
        }
    }

    return "
        <section class='all-animals-$player_id panel all-animals-panel modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Farm animals friendships</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-animals-$player_id exit' alt='Exit'/>
            </div>
            <span class='friendlist'>
                $farm_animals_structure
            </span>
        </section>
    ";
}

function display_junimo_kart_panel(): string {
    $untreated_data = $GLOBALS["untreated_all_players_data"];
    $player_id = get_current_player_id();
    $images_path = get_images_folder();
    $junimo_structure = "";

    $untreated_scores = get_junimo_leaderboard($untreated_data->junimoKartLeaderboards->entries);
    $counter = 1;

    foreach($untreated_scores->NetLeaderboardsEntry as $untreated_score) {
        if($counter > 5) {
            break;
        }

        $name = (string) $untreated_score->name->string;
        $score = (int) $untreated_score->score->int;
        $leader_class = ($counter === 1) ? "leader" : "";
        $junimo_structure .= "
            <span class='record-holder $leader_class'>
                <span class='record-holder-details'>
                    <span class='record-holder-counter'>#$counter</span>
                    <span class='record-holder-name'>$name</span>
                </span>
                <span class='record-holder-score'>$score</span>
            </span>
        ";
        $counter++;
    }

    return "
        <section class='junimo-kart-leaderboard-$player_id panel junimo-kart-leaderboard-panel modal-window'>
            <span class='leaderboard'>
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-junimo-kart-leaderboard-$player_id' alt='Exit'/>
                <img src='$images_path/content/junimo_kart.png' class='image-title' alt='Junimo Kart Background'/>
                <span class='scores'>
                    $junimo_structure
                </span>
            </span>
        </section>
    ";
}

function display_museum_panel(): string {
	$player_id = get_current_player_id();
    $museum_data = $GLOBALS["shared_players_data"]["museum_coords"];
    $images_path = get_images_folder();
    $column_start = 26;
    $column_end = 49;
    $column_breakpoints = [
        27,
        38
    ];

    $row_start = 5;
    $row_end = 17;
    $row_breakpoints = [
        12
    ];

    $table_structure = "";

    for($row_count = $row_start; $row_count < $row_end; $row_count++) {
        $table_structure .= "<tr>";

        for($column_count = $column_start; $column_count < $column_end; $column_count++) {
            if(in_array($row_count, $row_breakpoints) || in_array($column_count, $column_breakpoints)) {
                $table_structure .= "<td class='non-fillable-space'></td>";
                continue;
            }

            $current_col = ($column_count - $column_start) + 1;
            $current_row = ($row_count - $row_start) + 1;

            $museum_tooltip = "";
            foreach($museum_data as $piece_index => $piece_details) {
                if($piece_details["coords"]["X"] === $column_count && $piece_details["coords"]["Y"] === $row_count) {
                    $piece_name = ucfirst(get_item_name_by_id($piece_details["id"]));
                    $piece_filename = formate_text_for_file($piece_name);
                    $piece_type = $piece_details["type"];
                    $museum_tooltip = "
                        <span class='museum-tooltip tooltip'>
                            <img src='$images_path/$piece_type/$piece_filename.png' class='museum-piece' alt='$piece_name'/>
                            <span>$piece_name</span>
                        </span>
                    ";

                    unset($museum_data[$piece_index]);
                }
            }

            $table_structure .= "
                <td class='fillable-space col{$current_col} row{$current_row}'>
                    $museum_tooltip
                </td>
            ";
        }

        $table_structure .= "</tr>";
    }

    return "
        <section class='museum-$player_id panel museum-panel modal-window'>
            <span class='museum-block'>
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-museum-$player_id' alt='Exit'/>
                <table>
                    <tbody>
                        $table_structure
                    </tbody>
                </table>
            </span>
        </section>
    ";
}

function display_community_center_panel(): string {
    $player_id = get_current_player_id();
    $player_bundles = $GLOBALS["shared_players_data"]["cc_bundles"];
    $bundles_json = sanitize_json_with_version("bundles", true);
    $images_path = get_images_folder();
	$cc_binary = get_cc_binary_hash($player_bundles);
    $cc_structure = "";
    
    foreach($bundles_json as $room_name => $room_details) {
        if($room_name === "Bulletin Board" && has_element_in_mail("JojaMember")) {
            continue;
        }

        $cc_structure .= "
            <span class='room'>
                <h2>$room_name</h2>
                <span class='bundles'>
        ";

        foreach($room_details["bundle_ids"] as $bundle_id) {
            $bundle_details = $player_bundles[$bundle_id];
            $bundle_name = $bundle_details["bundle_name"];
            $formatted_bundle_name = formate_text_for_file($bundle_name);

            if($bundle_details["is_complete"]) {
                $is_complete_class = "complete";
                $bundle_tooltip_class = "";
                $bundle_tooltip = "";
            } else {
                $is_complete_class = "incomplete";
                $bundle_tooltip_class = "bundle-tooltip tooltip";

                $required_items = display_bundle_requirements($bundle_details["requirements"], $bundle_details["items_added"]);
                $slots = ($bundle_details["room_name"] === "Vault") ? display_bundle_purchase() : display_bundle_added_items($bundle_details["items_added"], $bundle_details["limit"]);

                $bundle_tooltip = "
                    <span>
                        <img src='$images_path/content/bundle_bg.png' class='bundle-bg' alt='Bundle background'/>
                        <img src='$images_path/bundles/{$formatted_bundle_name}_bundle.png' class='bundle-icon' alt='$bundle_name Bundle'/>
                        <span class='required-items'>
                            $required_items
                        </span>
                        <span class='slots'>
                            $slots
                        </span>
                    </span>
                ";
            }
            
            
            $cc_structure .= "
                <span class='bundle $bundle_tooltip_class'>
                    <img src='$images_path/bundles/{$formatted_bundle_name}_bundle.png' class='$is_complete_class' alt='$bundle_name Bundle'/>
                    $bundle_tooltip
                </span>
            ";
        }

        $cc_structure .= "
                </span>
            </span>
        ";
    }

    return "
        <section class='community-center-$player_id panel community-center-panel modal-window'>
            <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-community-center-$player_id' alt='Exit'/>
            <div class='community-center-background-container'>
                <img src='$images_path/bundles/CC_$cc_binary.png' class='background-image' alt='Community center background'/>
                <img src='$images_path/icons/chevron_down.png' class='chevron-down' alt='Scroll indicator'/>
            </div>
            <span class='rooms'>
                $cc_structure
            </span>
        </section>
    ";
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "display_feedback_panel") {
    require "utility_functions.php";
	echo display_feedback_panel();
}
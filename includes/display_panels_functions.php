<?php









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



if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "display_feedback_panel") {
    require "utility_functions.php";
	echo display_feedback_panel();
}
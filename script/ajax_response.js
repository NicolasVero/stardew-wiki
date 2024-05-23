function AJAX_send() {
    const xml_upload = document.getElementById("save-upload");
    const file       = xml_upload.files[0];
    const max_size   = "35Mo";

    const is_file_too_big = (file.size > in_bytes_conversion(max_size));

    document.getElementById("display").innerHTML = "";
    document.getElementById("landing-page").innerHTML = "";

    
    if(file && !is_file_too_big) {
        let form_data = new FormData();
        let xhr       = new XMLHttpRequest();
        const url     = "./includes/get_xml_data.php";
        
        form_data.append("save-upload", file);

        xhr.open("POST", url, true);

        xhr.onreadystatechange = function() {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

                const data = JSON.parse(xhr.responseText);
                const html = data.html;

                const page_display = document.getElementById("display");
                
                if(data.code == "success") {

                    const players_count = Object.keys(html).length;;

                    for(let i = 0; i < players_count; i++) {
                        page_display.innerHTML += html['player_' + i];
                    }

                    initialize_player_swapper(players_count);
					load_elements();
					swap_displayed_player(0);
                    
                } else {
                    page_display.innerHTML = html;
                }

				toggle_visibility_and_scroll(current_section, false, false);
                toggle_loading(false);
            }
        };

        xhr.send(form_data);
    } else {
        alert("error, the file is too large");
    }
}

function initialize_player_swapper(players_count) {
    const players_selection = document.getElementsByClassName("player_selection");

    for(let i = 0; i < players_selection.length; i++) {
        players_selection[i].addEventListener("click", () => {
            swap_displayed_player(i % players_count);
        });
    }
}

function swap_displayed_player(player_id) {

    const players_display = document.getElementsByClassName("player_container");

    for(let i = 0; i < players_display.length; i++) 
        players_display[i].style.display = (player_id != i) ? "none" : "block"; 
}

// Load html elements
function load_elements() {

    deactivate_landing_inputs();

    toggle_checkboxes_actions();

    // Buttons & panels
	let buttons =
	[
		{
			"open_button"    : ".main-settings",
			"exit_button"    : ".exit-settings",
			"modal_panel"    : ".settings",
			"disable_scroll" : false
		},
		{
			"open_button"    : ".file-upload",
			"exit_button"    : ".exit-upload",
			"modal_panel"    : ".upload-panel",
			"disable_scroll" : false
		}
	];

	//& Remplacer 8 par le nombre de joueur de la save ?
	for(let i = 0; i < 8; i++){
		buttons.push({
			"open_button"    : ".view-all-friendships-" + i,
			"exit_button"    : ".exit-all-friendships-" + i,
			"modal_panel"    : ".all-friends-" + i,
			"disable_scroll" : false
		});

		buttons.push({
			"open_button"    : ".view-all-quests-" + i,
			"exit_button"    : ".exit-all-quests-" + i,
			"modal_panel"    : ".all-quests-" + i,
			"disable_scroll" : false
		});
	}

	buttons.forEach(button => {
		activate_buttons(button.open_button, button.exit_button, button.modal_panel, button.disable_scroll);
	});

    // Tooltips
    const tooltips = document.querySelectorAll(".tooltip");
    tooltips.forEach(tooltip => {
        const rect = tooltip.getBoundingClientRect();
        const span = tooltip.querySelector("span");
        
        if (!span.classList.contains("left") && !span.classList.contains("right")){
            if (rect.left > window.innerWidth / 2)
                tooltip.querySelector("span").classList.add("left");
            else
                tooltip.querySelector("span").classList.add("right");
        }
    });
}
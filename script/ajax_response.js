function AJAX_send() {
    const xml_upload = document.getElementById("save-upload");
    const file = xml_upload.files[0];
    document.getElementById("display").innerHTML = "";
    document.getElementById("landing-page").innerHTML = "";

    
    if(file) {
        let form_data = new FormData();
        form_data.append("save-upload", file);
        let xhr = new XMLHttpRequest();
        const url = "./includes/get_xml_data.php";

        xhr.open("POST", url, true);

        xhr.onreadystatechange = function() {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

                const data = JSON.parse(xhr.responseText);
                const html = data.html;

                const page_display = document.getElementById("display");

                //& remplacer par nombre attribut object
                for(let i = 0; i < 2; i++) {
                    page_display.innerHTML += html['player_' + i];
                }

                load_elements();
                toggle_loading(false);



                initializePlayerSwapper(2);
            }
        };

        xhr.send(form_data);
    }
}

function initializePlayerSwapper(players_count) {
    const players_selection = document.getElementsByClassName("player_selection");
    console.log(players_selection);

    for(let i = 0; i < players_selection.length; i++) {
        players_selection[i].addEventListener("click", () => {
            console.log(i)
            swapDisplayedPlayer(i % players_count);
        });
    }
}

function swapDisplayedPlayer(player_id) {

    const players_display = document.getElementsByClassName("player_container");
    
    for(let i = 0; i < players_display.length; i++) {
        players_display[i].style.display = (player_id != i) ? "none" : "block"; 
    }

    players_display[player_id].style.display = "block";
}

// Load html elements
function load_elements() {

    toggle_visibility_and_scroll(current_section, false, false);
    deactivate_landing_inputs();

    toggle_checkboxes_actions();

    // Buttons & panels
    activate_buttons(".view-all-friendships", ".exit-all-friendships", ".all-friends", false);
    activate_buttons(".view-all-quests", ".exit-all-quests", ".all-quests", false);
    activate_buttons(".main-settings", ".exit-settings", ".settings", false);
    activate_buttons(".file-upload", ".exit-upload", ".upload-panel", false);

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
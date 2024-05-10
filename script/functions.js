// Panneaux d'infos
let current_section = null;
function toggle_visibility_and_scroll(element, should_display, should_disable_scroll) {
    element.style.display = (should_display) ? 'block' : 'none';
    document.body.style.overflow = (should_disable_scroll) ? 'hidden' : 'auto';
}

function activate_buttons(show, hide, sections_to_show, disable_scroll) {
    const show_button = document.querySelectorAll(show);
    const hide_button = document.querySelectorAll(hide);
    const sections = document.querySelector(sections_to_show);

    show_button.forEach(function(button) {
        button.addEventListener('click', function() {
            if (current_section !== null) {
                toggle_visibility_and_scroll(current_section, false, disable_scroll);
            }
            current_section = sections;
            toggle_visibility_and_scroll(sections, true, disable_scroll);
        });
    });

    hide_button.forEach(function(button) {
        button.addEventListener('click', function() {
            toggle_visibility_and_scroll(sections, false, disable_scroll);
            current_section = null;
        });
    });
}
activate_buttons('.upload-file', '.exit-upload', '.upload-panel', false);

function deactivate_landing_upload() {
    document.getElementById("landing_upload").style.display = "none";
}

// Mode no spoil
function no_spoil_mode() {
    const elements = document.getElementsByClassName("not-found");
    for(let i = 0; i < elements.length; i++) {
        const parent_element = elements[i].parentElement;
        if(parent_element && !elements[i].classList.contains("not-hide"))
            parent_element.style.display = (parent_element.style.display === "none") ?  "block" : "none";
    }
}

// Custom checkboxes
function activate_custom_checkboxes(checkmark_class) {
    let checkmarks = document.querySelectorAll(checkmark_class);
    checkmarks.forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            let adjacent_checkbox = checkbox.previousElementSibling;
            if (adjacent_checkbox && adjacent_checkbox.type === 'checkbox') {
                adjacent_checkbox.checked = (!adjacent_checkbox.checked) ? true : false;
                adjacent_checkbox.dispatchEvent(new Event('change'));
            }
        });
    });   
}

// Style input type file
document.getElementById('save-upload').addEventListener('change', change_label_name);
function change_label_name(event) {
    const new_filename = event.target.files[0].name.substring(0, 12);
    document.getElementById('new-filename').innerHTML = new_filename;
    AJAX_send();
}

function AJAX_send() {
    const xml_upload = document.getElementById('save-upload');
    let file = xml_upload.files[0];
    document.getElementById("display").innerHTML = '';
    document.getElementById("landing-page").innerHTML = '';

    
    if(file) {
        let form_data = new FormData();
        form_data.append('save-upload', file);
        let xhr = new XMLHttpRequest();
        let url = './includes/get_xml_data.php';

        xhr.open('POST', url, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                document.getElementById("display").innerHTML = xhr.responseText;
                load_elements();
            }
        };

        xhr.send(form_data);
    } else {
        console.error('not in');
    }
}


// Load html elements
function load_elements() {

    toggle_visibility_and_scroll(current_section, false, false);
    deactivate_landing_upload();

    // Buttons & panels
    activate_buttons('.view-all-friendships', '.exit-all-friendships', '.all-friends', false);
    activate_buttons('.view-all-quests', '.exit-all-quests', '.all-quests', false);
    activate_buttons('.view-settings', '.exit-settings', '.settings', false);
    activate_buttons('#secondary-upload', '.exit-upload', '.upload-panel', false);

    // Checkboxes
    activate_custom_checkboxes(".checkmark");

    // Settings
    document.getElementById('no-spoil-mode').addEventListener('change', no_spoil_mode);

    // Tooltips
    const tooltips = document.querySelectorAll('.tooltip');
    tooltips.forEach(tooltip => {
        const rect = tooltip.getBoundingClientRect();
        const span = tooltip.querySelector('span');
        
        if (!span.classList.contains('left') && !span.classList.contains('right')){
            if (rect.left > window.innerWidth / 2)
                tooltip.querySelector('span').classList.add('left');
            else
                tooltip.querySelector('span').classList.add('right');
        }
    });
}
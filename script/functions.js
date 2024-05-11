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

function deactivate_landing_inputs() {
    document.getElementById("landing").style.display = "none";
    document.getElementById("landing-page").style.display = "none";
}

// Mode no spoil
function no_spoil_mode(event) {
    event = event || {};

    const elements = document.getElementsByClassName("not-found");
    for(let i = 0; i < elements.length; i++) {
        const parent_element = elements[i].parentElement;
        if(parent_element && !elements[i].classList.contains("not-hide")) {
            const isChecked = (event.target) ? event.target.checked : true;
            parent_element.style.display = isChecked ? "none" : "block";
        }
    }
}

function toggle_versions_items_mode(event) {
    event = event || {};

    const elements = document.getElementsByClassName("newer-version");
    // Check elements à l'unité
    for(let i = 0; i < elements.length; i++) {
        const parent_element = elements[i].parentElement;
        const isChecked = (event.target) ? event.target.checked : true;
        parent_element.style.display = isChecked ? "none" : "block";
    }

    // Check section entière
    const sections = document.getElementsByClassName("gallery");
    for(let i = 0; i < sections.length; i++) {
        allChildrenNewerVersion = true;
        const section = sections[i];
        const spans = section.querySelectorAll(".tooltip");
        
        spans.forEach(span => {
            const img = span.children[0].classList.contains('newer-version');
            if (!img)
                allChildrenNewerVersion = false;
        });
        const title = section.querySelector("h2");
        title.style.display = allChildrenNewerVersion ? "none" : "block";
    }
}



// Custom checkboxes
function toggle_custom_checkboxes(checkmark_class) {
    const checkmarks = document.querySelectorAll(checkmark_class);
    checkmarks.forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            const adjacent_checkbox = checkbox.previousElementSibling;
            if (adjacent_checkbox && adjacent_checkbox.type === 'checkbox') {
                adjacent_checkbox.checked = (!adjacent_checkbox.checked) ? true : false;
                adjacent_checkbox.dispatchEvent(new Event('change'));
            }
        });
    });   
}

// Toggle when uploaded file
function toggle_checkboxes_actions() {
    const checkboxes = document.querySelectorAll(".checkbox");
    checkboxes.forEach(function(checkbox) {
        const checkboxInput = checkbox.querySelector("input[type='checkbox']");
        if (checkboxInput) {
            const functionName = checkboxInput.id;
            const isChecked = checkboxInput.checked;

            if (isChecked && typeof window[functionName] === 'function') {
                window[functionName]();
            }
        }
    });
}

// Style input type file
function file_choice(event) {
    const new_filename = event.target.files[0].name.substring(0, 12);
    document.getElementById('new-filename').innerHTML = new_filename;
    toggle_loading(true);
    AJAX_send();
}

function toggle_loading(shown) {
    document.getElementById('loading-strip').style.display = (shown) ? "block" : "none";
}

function AJAX_send() {
    const xml_upload = document.getElementById('save-upload');
    const file = xml_upload.files[0];
    document.getElementById("display").innerHTML = '';
    document.getElementById("landing-page").innerHTML = '';

    
    if(file) {
        let form_data = new FormData();
        form_data.append('save-upload', file);
        let xhr = new XMLHttpRequest();
        const url = './includes/get_xml_data.php';

        xhr.open('POST', url, true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

                const data = JSON.parse(xhr.responseText);
                const html = data.html;

                document.getElementById("display").innerHTML = html;
                load_elements();
                toggle_loading(false);
            }
        };

        xhr.send(form_data);
    }
}


document.getElementById('no_spoil_mode').addEventListener('change', no_spoil_mode);
document.getElementById('toggle_versions_items_mode').addEventListener('change', toggle_versions_items_mode);
document.getElementById('save-upload').addEventListener('change', file_choice);
activate_buttons('.landing-upload', '.exit-upload', '.upload-panel', false);
activate_buttons('.landing-settings', '.exit-settings', '.settings', false);
toggle_custom_checkboxes(".checkmark");

// Load html elements
function load_elements() {

    toggle_visibility_and_scroll(current_section, false, false);
    deactivate_landing_inputs();

    toggle_checkboxes_actions();

    // Buttons & panels
    activate_buttons('.view-all-friendships', '.exit-all-friendships', '.all-friends', false);
    activate_buttons('.view-all-quests', '.exit-all-quests', '.all-quests', false);
    activate_buttons('.main-settings', '.exit-settings', '.settings', false);
    activate_buttons('.file-upload', '.exit-upload', '.upload-panel', false);

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
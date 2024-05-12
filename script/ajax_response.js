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
// Upload File AJAX
async function AJAX_send() {
    const xml_upload = document.getElementById("save-upload");
    const file       = xml_upload.files[0];

	const max_upload_size = await get_max_upload_size();

    const is_file_too_big = (file.size > max_upload_size);

	const page_display = document.getElementById("display");
	const landing_menu = document.getElementById("landing_menu");
	const landing_page = document.getElementById("landing_page").outerHTML;

	if(landing_menu)
		landing_menu.outerHTML = "";

	page_display.innerHTML = "";

    if(file) {

        let form_data = new FormData();
        let xhr       = new XMLHttpRequest();
        const url     = "./includes/get_xml_data.php";
        
		if(is_file_too_big)
			form_data.append("save-upload", new File(["SizeException"], "Error_SizeException.xml"));
		else
			form_data.append("save-upload", file);

        xhr.open("POST", url, true);

        xhr.onreadystatechange = function() {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

                const data = JSON.parse(xhr.responseText);
                const html = data.html;
                
                if(data.code == "success") {

                    const players_count = data.players.length;

					page_display.innerHTML = html['sur_header'];
					page_display.innerHTML += landing_page;

                    for(let i = 0; i < players_count; i++)
                        page_display.innerHTML += html['player_' + i];

                    initialize_player_swapper(players_count);

					load_elements();
                    
                } else {
					page_display.innerHTML = html['sur_header'];
					page_display.innerHTML += landing_page;
					toggle_landing_page(false);
                    page_display.innerHTML += html['error_message'];
					loard_error_page_items();
                }

				toggle_visibility_and_scroll(current_section, false, false);
                toggle_loading(false);
            }
        };

        xhr.send(form_data);
    } else {
        alert("An error occured while uploading the file. Please Try again");
    }
}

// Feedback Form AJAX
document.getElementById('feedback_form').addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);

    fetch('./includes/sendmail.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert(data.message);
        } else {
            alert('Error submitting form: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the form.');
    });
});
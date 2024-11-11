function get_site_root() {
	const protocol = window.location.protocol;
	const host = window.location.host;

	return (host === 'localhost') ? `${protocol}//localhost/travail/stardew_dashboard/` : `${protocol}//stardew-dashboard.42web.io/`;
}

function detect_os() {
	const user_agent = window.navigator.userAgent.toLowerCase();

	if(user_agent.includes("mac"))   return "Mac";
	if(user_agent.includes("linux")) return "Linux";
	
	return "Windows";
}

function get_os_path(os = "Windows") {
	switch(os) {
		case "Windows" : return "(%AppData%/StardewValley/Saves/SaveName).";
		case "Mac"     : return "(~/.config/StardewValley/Saves/).";
		case "Linux"   : return "(~/.steam/debian-installation/steamapps/compatdata/413150/pfx/drive_c/users/steamuser/AppData/Roaming/StardewValley/Saves/).";
	}
}

function file_choice(event) {
	const new_filename = event.target.files[0].name.substring(0, 12);
	document.getElementById("new-filename").innerHTML = new_filename;
	toggle_loading(true);
	AJAX_send();
}

function toggle_landing_page(display) {
	const landing_page = document.getElementById("landing_page");

	if(landing_page)
		landing_page.style.display = (display) ? "block" : "none";
}

function on_images_loaded(callback) {
	toggle_scroll(false);
	var images = document.querySelectorAll('img');
	var images_loaded = 0;
	var total_images = images.length;

	if (total_images === 0) {
		callback();
		return;
	}

	images.forEach(function(image) {
		if (image.complete) {
			images_loaded++;
		} else {
			image.addEventListener('load', function() {
				images_loaded++;
				if (images_loaded === total_images)
					callback();
			});
			image.addEventListener('error', function() {
				images_loaded++;
				if (images_loaded === total_images)
					callback();
			});
		}
	});

	if (images_loaded === total_images)
		callback();
}

function update_tooltips_after_ajax() {
	on_images_loaded(function() {
		initialize_tooltips();
		swap_displayed_player(0);
		toggle_scroll(true);
	});
}

function initialize_tooltips() {
	const tooltips = document.querySelectorAll('.tooltip');
	
	tooltips.forEach((tooltip) => {

		const window_width = window.innerWidth;
		const rect = tooltip.getBoundingClientRect();
		const span = tooltip.querySelector("span");
		
		if (!span.classList.contains("left") && !span.classList.contains("right")){
			if (rect.left < window_width / 2)
				span.classList.add("right");
			else
				span.classList.add("left");
		}

	});
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

async function get_max_upload_size() {
	return fetch('./includes/functions/utility_functions.php?action=get_max_upload_size')
	.then(response => response.json())
	.then(data => {
		return data.post_max_size;
	});
}

function toggle_scroll(can_scroll) {
	document.body.style.overflow = (can_scroll) ? "auto" : "hidden";
}

function toggle_loading(shown) {
	document.getElementById("loading-strip").style.display = (shown) ? "block" : "none";
}

function in_bytes_conversion(size) {
	const unit_to_power = { 'o': 0, 'Ko': 1, 'Mo': 2, 'Go': 3 };

	const matches = size.match(/(\d+)([a-zA-Z]+)/);
	const value = parseInt(matches[1], 10);
	const unit = matches[2];

	return value * Math.pow(1024, unit_to_power[unit]);
}

function loard_error_page_items() {
	const buttons = [
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
	
	buttons.forEach(button => {
		activate_buttons(button.open_button, button.exit_button, button.modal_panel, button.disable_scroll);
	});
}

function load_elements() {

	toggle_landing_page(false);
	toggle_checkboxes_actions();

	// Buttons & panels
	const buttons = [
		{
			"open_button"    : ".landing-settings",
			"exit_button"    : ".exit-settings",
			"modal_panel"    : ".settings",
			"disable_scroll" : false
		},
		{
			"open_button"    : ".landing-upload",
			"exit_button"    : ".exit-upload",
			"modal_panel"    : ".upload-panel",
			"disable_scroll" : false
		},
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

		buttons.push({
			"open_button"    : ".view-monster-eradication-goals-" + i,
			"exit_button"    : ".exit-monster-eradication-goals-" + i,
			"modal_panel"    : ".monster-eradication-goals-" + i,
			"disable_scroll" : false
		});

		buttons.push({
			"open_button"    : ".view-calendar-" + i,
			"exit_button"    : ".exit-calendar-" + i,
			"modal_panel"    : ".calendar-" + i,
			"disable_scroll" : false
		});
	}

	buttons.forEach(button => {
		activate_buttons(button.open_button, button.exit_button, button.modal_panel, button.disable_scroll);
	});

	document.getElementById('home-icon').addEventListener('click', () => {
		const display = (document.getElementById('landing_page').style.display == "none") ? true : false;
		toggle_landing_page(display);
	});

	update_tooltips_after_ajax();
}

function feedback_custom_radio() {
	const feedback_fake_radios = document.querySelectorAll('.feedback_custom_radio');
	const feedback_real_radios = document.querySelectorAll('.feedback_real_radio');

	feedback_fake_radios.forEach(fake_radio => {
		span_topic = fake_radio.parentElement;
		span_topic.addEventListener('click', () => {
			const real_radio = fake_radio.previousElementSibling;
			if(real_radio && real_radio.type === "radio") {
				real_radio.checked = true;
				real_radio.dispatchEvent(new Event("change"));
			}
		});
	});

	feedback_real_radios.forEach(real_radio => {
		real_radio.addEventListener('change', () => {
			feedback_fake_radios.forEach(fake_radio => {
				fake_radio.classList.add('topic_not_selected');
			});

			const fake_radio = real_radio.nextElementSibling;
			if (fake_radio && fake_radio.tagName === "IMG") {
				if (real_radio.checked)
					fake_radio.classList.remove('topic_not_selected');
				else
					fake_radio.classList.add('topic_not_selected');
			}
		});
	});
}

function save_landing_surheader() {
	const landing_menu = document.getElementById('landing_menu');
	surheader = landing_menu.innerHTML;
}

function hide_all_sections(section_destroy = false) {
	const sections = document.querySelectorAll('.modal-window');
	sections.forEach(section => {
		if(section.classList.contains('to-destroy') && section_destroy)
			section.remove();
		
		section.style.display = 'none';
	});
}

const get_parent_element = (element) => {
    if (!element) return null;
    const parent = element.parentElement;
    return parent?.classList.contains('wiki_link') ? parent.parentElement : parent;
};

const set_element_display = (element, show) => {
    if (element)
        element.style.display = show ? "flex" : "none";
};

const has_class = (element, class_name) => element.classList.contains(class_name);
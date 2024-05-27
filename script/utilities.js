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

function toggle_loading(shown) {
    document.getElementById("loading-strip").style.display = (shown) ? "block" : "none";
}

function deactivate_landing_inputs() {
    document.getElementById("landing").style.display = "none";
    document.getElementById("landing-page").style.display = "none";
}

function in_bytes_conversion(size) {
    const unit_to_power = { 'o': 0, 'Ko': 1, 'Mo': 2, 'Go': 3 };

    const matches = size.match(/(\d+)([a-zA-Z]+)/);
    const value = parseInt(matches[1], 10);
    const unit = matches[2];

    return value * Math.pow(1024, unit_to_power[unit]);
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

function toggle_scroll(can_scroll) {
	console.log(document.body.style.overflow);
    document.body.style.overflow = (can_scroll) ? "auto" : "hidden";
	console.log(document.body.style.overflow);
}
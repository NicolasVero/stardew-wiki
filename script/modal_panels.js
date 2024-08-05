let current_section = null;

function activate_buttons(show, hide, sections_to_show, disable_scroll) {
    const show_button = document.querySelectorAll(show);
    const hide_button = document.querySelectorAll(hide);
    const sections = document.querySelector(sections_to_show);

    show_button.forEach(function(button) {
        button.addEventListener("click", function() {
			hide_all_sections(true);

            current_section = sections;
            toggle_visibility_and_scroll(sections, true, disable_scroll);
        });
    });

    hide_button.forEach(function(button) {
        button.addEventListener("click", function() {
			hide_all_sections(true);
            current_section = null;
        });
    });
}

function activate_close_buttons(hide, sections_to_hide) {
    const hide_button = document.querySelectorAll(hide);
    const sections = document.querySelector(sections_to_hide);

    hide_button.forEach(function(button) {
        button.addEventListener("click", function() {
            sections.remove();
            current_section = null;
        });
    });
}

function toggle_visibility_and_scroll(element, should_display, should_disable_scroll) {
    element.style.display = (should_display) ? "block" : "none";
    // document.body.style.overflow = (should_disable_scroll) ? "hidden" : "auto";
}

function hide_panels(event) {
    event = event || {};
	if(current_section && event.target !== current_section && !current_section.contains(event.target) && !event.target.classList.contains("modal-opener")) {
		if(current_section.classList.contains('feedback-panel')) {
			current_section.remove();
			return;
		}

		if(!current_section.classList.contains('to-keep-open'))
			current_section.style.display = "none";
		
	}

}
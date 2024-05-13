let current_section = null;

function activate_buttons(show, hide, sections_to_show, disable_scroll) {
    const show_button = document.querySelectorAll(show);
    const hide_button = document.querySelectorAll(hide);
    const sections = document.querySelector(sections_to_show);

    show_button.forEach(function(button) {
        button.addEventListener("click", function() {
            if (current_section !== null)
                toggle_visibility_and_scroll(current_section, false, disable_scroll);
            current_section = sections;
            toggle_visibility_and_scroll(sections, true, disable_scroll);
        });
    });

    hide_button.forEach(function(button) {
        button.addEventListener("click", function() {
            toggle_visibility_and_scroll(sections, false, disable_scroll);
            current_section = null;
        });
    });
}

function toggle_visibility_and_scroll(element, should_display, should_disable_scroll) {
    element.style.display = (should_display) ? "block" : "none";
    document.body.style.overflow = (should_disable_scroll) ? "hidden" : "auto";
}

function hide_panels(event) {
	if (current_section && event.target !== current_section && !current_section.contains(event.target) && !event.target.classList.contains("modal-opener"))
		current_section.style.display = "none";
}
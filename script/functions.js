import utilities from "./utilities.js";


window.addEventListener("load", () => {

    const os_path = utilities.get_os_path(utilities.detect_os());
    const tag     = document.getElementById("save_os_path");
    
    tag.innerHTML = os_path;

	document.addEventListener("keyup", (event) => {
		if(event.key === 'Escape')
			hide_panels(event);
	});

	document.addEventListener("click", hide_panels);
    document.getElementById("no_spoil_mode").addEventListener("change", no_spoil_mode);
    document.getElementById("toggle_versions_items_mode").addEventListener("change", toggle_versions_items_mode);
    document.getElementById("save-upload").addEventListener("change", utilities.file_choice);
    activate_buttons(".landing-upload", ".exit-upload", ".upload-panel", false);
    activate_buttons(".landing-settings", ".exit-settings", ".settings", false);
    toggle_custom_checkboxes(".checkmark");
});
let surheader;

window.addEventListener("load", () => {

	const os_path = get_os_path(detect_os());
	const tag     = document.getElementById("save_os_path");
	
	tag.innerHTML = os_path;

	document.addEventListener("keyup", (event) => {
		if(event.key === "Escape")
			hide_panels(event);
	});

	document.addEventListener("click", hide_panels);
	
	document.getElementById("toggle_versions_items_mode").addEventListener("change", handle_toggle_versions_mode);
	document.getElementById("no_spoil_mode").addEventListener("change", handle_no_spoil_mode);
	document.getElementById("spoil_mode").addEventListener("change", handle_spoil_mode);

	document.getElementById("save-upload").addEventListener("change", file_choice);

	save_landing_surheader();
	
	activate_buttons(".landing-upload", ".exit-upload", ".upload-panel");
	activate_buttons(".landing-settings", ".exit-settings", ".settings");
	toggle_custom_checkboxes(".checkmark");

	activate_feedback_ajax_trigger();
});
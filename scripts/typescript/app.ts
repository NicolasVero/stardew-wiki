let surheader: HTMLElement | null;

window.addEventListener("load", () => {

    const os_path: string = get_os_path(detect_os());
    const tag: HTMLElement | null = document.getElementById("save_os_path");
    
    if(tag) {
        tag.innerHTML = os_path;
    }
    
    const toggle_versions_items_mode = document.getElementById("toggle_versions_items_mode");
    const no_spoil_mode = document.getElementById("no_spoil_mode");
    const spoil_mode = document.getElementById("spoil_mode");
    const steam_achievements = document.getElementById("steam_achievements");
    
    if(toggle_versions_items_mode) {
        toggle_versions_items_mode.addEventListener("change", handle_toggle_versions_mode);
    }
    
    if(no_spoil_mode) {
        no_spoil_mode.addEventListener("change", handle_no_spoil_mode);
    }

    if(spoil_mode) {
        spoil_mode.addEventListener("change", handle_spoil_mode);
    }

    if(steam_achievements) {
        steam_achievements.addEventListener("change", handle_steam_mode);
    }

    const save_upload = document.getElementById("save-upload");
    if(save_upload) {
        save_upload.addEventListener("change", file_choice);
    }

    save_landing_surheader();
    activate_buttons(".landing-upload", ".exit-upload", ".upload-panel");
    activate_buttons(".landing-settings", ".exit-settings", ".settings");
    toggle_custom_checkboxes(".checkmark");
    activate_feedback_ajax_trigger();
});
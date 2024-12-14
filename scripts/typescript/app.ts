let surheader: HTMLElement | null;

window.addEventListener("load", () => {

    const os_path: string = get_os_path(detect_os());
    const tag: HTMLElement | null = document.getElementById("save_os_path");
    
    if(tag) {
        tag.innerHTML = os_path;
    }

    // document.addEventListener("click", hide_panels);
    
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

function handle_no_spoil_mode():void {
    const spoil_checkbox = document.getElementById("spoil_mode") as HTMLInputElement;
    const no_spoil_checkbox = document.getElementById("no_spoil_mode") as HTMLInputElement;

    if(no_spoil_checkbox && spoil_checkbox && no_spoil_checkbox.checked && spoil_checkbox.checked) {
        spoil_checkbox.checked = false;
    }

    update_display(["not-found", "found"]);
};

function handle_toggle_versions_mode():void {
    update_display("newer-version");
};

function handle_spoil_mode():void {
    const no_spoil_checkbox = document.getElementById("no_spoil_mode") as HTMLInputElement;
    const spoil_checkbox = document.getElementById("spoil_mode") as HTMLInputElement;

    if(!no_spoil_checkbox || !spoil_checkbox) {
        return;
    }

    if(spoil_checkbox.checked && no_spoil_checkbox.checked) {
        no_spoil_checkbox.checked = false;
        update_display(["not-found", "found"]);
    } else {
        update_display(["found"]);
    }
};

function handle_steam_mode():void {
    const images_folder = ["steam_achievements", "achievements"];
    const images = document.querySelectorAll(".achievements-section img");

    images.forEach((image) => {
        const src = image.getAttribute("src");
        const [old_folder, new_folder] = (src.includes('steam')) ? images_folder : [...images_folder].reverse();
        image.setAttribute("src", src.replace(old_folder, new_folder));
    });
}

function initialize_settings():void {
    handle_toggle_versions_mode();
    handle_no_spoil_mode();
    handle_spoil_mode();
};
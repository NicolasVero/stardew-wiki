let surheader: HTMLElement | null;

window.addEventListener("load", () => {

    const os_path: string = get_os_path(detect_os());
    const tag: HTMLElement | null = document.getElementById("save_os_path");
    
    if(tag) {
        tag.innerHTML = os_path;
    }

    document.addEventListener("keyup", (event: KeyboardEvent) => {
        if(event.key === "Escape") {
            hide_panels({} as MouseEvent);
        }
    });

    document.addEventListener("click", hide_panels);
    
    const toggleVersionsItemsMode = document.getElementById("toggle_versions_items_mode");
    const noSpoilMode = document.getElementById("no_spoil_mode");
    const spoilMode = document.getElementById("spoil_mode");
    
    if(toggleVersionsItemsMode) {
        toggleVersionsItemsMode.addEventListener("change", handle_toggle_versions_mode);
    }
    
    if(noSpoilMode) {
        noSpoilMode.addEventListener("change", handle_no_spoil_mode);
    }

    if(spoilMode) {
        spoilMode.addEventListener("change", handle_spoil_mode);
    }

    const saveUpload = document.getElementById("save-upload");
    if(saveUpload) {
        saveUpload.addEventListener("change", file_choice);
    }

    save_landing_surheader();
    
    activate_buttons(".landing-upload", ".exit-upload", ".upload-panel");
    activate_buttons(".landing-settings", ".exit-settings", ".settings");
    toggle_custom_checkboxes(".checkmark");

    activate_feedback_ajax_trigger();
});




const handle_no_spoil_mode = () => {
    const spoil_checkbox = document.getElementById("spoil_mode") as HTMLInputElement;
    const no_spoil_checkbox = document.getElementById("no_spoil_mode") as HTMLInputElement;

    if(no_spoil_checkbox && spoil_checkbox && no_spoil_checkbox.checked && spoil_checkbox.checked) {
        spoil_checkbox.checked = false;
    }

    update_display(["not-found", "found"]);
};

const handle_toggle_versions_mode = () => {
    update_display("newer-version");
};

const handle_spoil_mode = () => {
    const no_spoil_checkbox = document.getElementById("no_spoil_mode") as HTMLInputElement;
    const spoil_checkbox = document.getElementById("spoil_mode") as HTMLInputElement;

    if(no_spoil_checkbox && spoil_checkbox) {
        if(spoil_checkbox.checked && no_spoil_checkbox.checked) {
            no_spoil_checkbox.checked = false;
            update_display(["not-found", "found"]);
        } else {
            update_display(["found"]);
        }
    }
};

const initialize_settings = () => {
    handle_toggle_versions_mode();
    handle_no_spoil_mode();
    handle_spoil_mode();
};

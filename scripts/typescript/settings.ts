function get_settings(): { no_spoil: boolean, toggle_versions: boolean, spoil: boolean } {
    return {
        no_spoil: (document.getElementById("no_spoil_mode") as HTMLInputElement).checked,
        toggle_versions: (document.getElementById("toggle_versions_items_mode") as HTMLInputElement).checked,
        spoil: (document.getElementById("spoil_mode") as HTMLInputElement).checked
    };
}

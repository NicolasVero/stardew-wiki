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


export default {
    detect_os,
    get_os_path,
    file_choice,
    toggle_loading,
    deactivate_landing_inputs
};
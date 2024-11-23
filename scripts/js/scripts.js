var OS;
(function (OS) {
    OS["mac"] = "mac";
    OS["linux"] = "linux";
    OS["windows"] = "windows";
})(OS || (OS = {}));
const os_paths = new Map([
    [OS.mac, "(~/.config/StardewValley/Saves/)."],
    [OS.linux, "(~/.steam/debian-installation/steamapps/compatdata/413150/pfx/drive_c/users/steamuser/AppData/Roaming/StardewValley/Saves/)."],
    [OS.windows, "(%AppData%/StardewValley/Saves/SaveName)."]
]);
function detect_os() {
    const user_agent = window.navigator.userAgent.toLowerCase();
    if (user_agent.includes("mac")) {
        return OS.mac;
    }
    if (user_agent.includes("linux")) {
        return OS.linux;
    }
    return OS.windows;
}
function get_os_path(os = OS.windows) {
    return os_paths.get(os) || "";
}
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
function get_site_root() {
    const protocol = window.location.protocol;
    const host = window.location.host;
    return (host === "localhost")
        ? `${protocol}//localhost/travail/stardew_dashboard/`
        : `${protocol}//stardew-dashboard.42web.io/`;
}
function get_max_upload_size() {
    return __awaiter(this, void 0, void 0, function* () {
        return fetch("./../includes/functions/utility_functions.php?action=get_max_upload_size")
            .then(response => response.json())
            .then((data) => {
            return data.post_max_size;
        });
    });
}

// async function AJAX_send(): Promise<void> {
// 	const xml_upload: HTMLInputElement | null = document.getElementById("save-upload") as HTMLInputElement;
// 	const file: File | undefined = xml_upload?.files?.[0];
// 	const max_upload_size: number = await get_max_upload_size();
// 	if (!file) {
// 		alert("An error occurred while uploading the file. Please try again.");
// 		return;
// 	}
// 	const is_file_too_big: boolean = file.size > max_upload_size;
// 	const page_display: HTMLElement | null = document.getElementById("display");
// 	const landing_menu: HTMLElement | null = document.getElementById("landing_menu");
// 	const landing_page: string = document.getElementById("landing_page")?.outerHTML || '';
// 	if (landing_menu) {
// 		landing_menu.outerHTML = "";
// 	}
// 	if (page_display) {
// 		page_display.innerHTML = "";
// 	}
// 	let form_data = new FormData();
// 	let xhr = new XMLHttpRequest();
// 	const url: string = get_site_root() + "includes/get_xml_data.php";
// 	if (is_file_too_big) {
// 		form_data.append("save-upload", new File(["SizeException"], "Error_SizeException.xml"));
// 	} else {
// 		form_data.append("save-upload", file);
// 	}
// 	xhr.open("POST", url, true);
// 	xhr.onreadystatechange = function () {
// 		if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
// 			const data: { code: string; html: Record<string, string>; players: Array<any>; } = JSON.parse(xhr.responseText);
// 			const html: Record<string, string> = data.html;
// 			if (data.code === "success") {
// 				const players_count: number = data.players.length;
// 				if (page_display) {
// 					page_display.innerHTML = html["sur_header"];
// 					page_display.innerHTML += landing_page;
// 					for (let i = 0; i < players_count; i++) {
// 						page_display.innerHTML += html["player_" + i];
// 					}
// 					initialize_player_swapper(players_count);
// 					initialize_settings();
// 					load_elements();
// 				}
// 			} else {
// 				if (page_display) {
// 					page_display.innerHTML = html["sur_header"];
// 					page_display.innerHTML += landing_page;
// 					toggle_landing_page(false);
// 					page_display.innerHTML += html["error_message"];
// 					loard_error_page_items();
// 				}
// 			}
// 			activate_feedback_ajax_trigger();
// 			toggle_visibility(current_section, false);
// 			toggle_loading(false);
// 		}
// 	};
// 	xhr.send(form_data);
// }
function load_easter_eggs() {
    easter_egg_characters();
    easter_egg_kaaris();
}
function easter_egg_characters() {
    const characters = [
        "abigail", "alex", "caroline", "clint", "demetrius", "elliott", "emily",
        "evelyn", "george", "gus", "haley", "harvey", "jas", "jodi", "kent", "leah",
        "lewis", "linus", "marnie", "maru", "pam", "penny", "pierre", "robin",
        "sam", "sandy", "sebastian", "shane", "vincent", "willy", "wizard"
    ];
    const date = new Date();
    const index_picker = [
        new Date(date.getFullYear(), 0, 1).getTime(),
        date.getUTCMonth(),
        date.getUTCDate()
    ].reduce((acc, val) => acc * val, 1) % characters.length;
    const character = characters[index_picker];
    const elements = document.querySelectorAll(".character-name." + character);
    if (!elements.length)
        return;
    const audio = new Audio(get_site_root() + "medias/audio/trigger.mp3");
    let is_playing = false;
    const play_once = () => {
        if (!is_playing) {
            is_playing = true;
            const full_screen_image = document.createElement("img");
            full_screen_image.src = `https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/medias/images/characters/${character}.png`;
            full_screen_image.classList.add("fullscreen-image");
            document.body.appendChild(full_screen_image);
            full_screen_image.classList.add("show");
            audio.play().finally(() => {
                is_playing = false;
            });
            setTimeout(() => {
                full_screen_image.classList.remove("show");
                full_screen_image.addEventListener("transitionend", () => {
                    full_screen_image.remove();
                });
            }, 1000);
        }
    };
    elements.forEach(element => {
        element.addEventListener("dblclick", play_once);
    });
}
function easter_egg_kaaris() {
    var _a;
    let element = document.querySelector(".house");
    if (!element)
        return;
    element = Array.from(((_a = element.previousElementSibling) === null || _a === void 0 ? void 0 : _a.children) || []).find((child) => child.tagName === "IMG");
    if (!element)
        return;
    element.classList.add("easter_egg_kaaris");
    const audio = new Audio(get_site_root() + "medias/audio/kaaris_maison-citrouille.mp3");
    let is_playing = false;
    const play_once = () => {
        if (!is_playing) {
            is_playing = true;
            audio.play().finally(() => {
                is_playing = false;
            });
        }
    };
    element.addEventListener("dblclick", play_once);
}
let current_section = null;
function activate_buttons(show, hide, sections_to_show) {
    const show_button = document.querySelectorAll(show);
    const hide_button = document.querySelectorAll(hide);
    const sections = document.querySelector(sections_to_show);
    show_button.forEach((button) => {
        button.addEventListener("click", () => {
            hide_all_sections(true);
            if (sections) {
                current_section = sections;
                toggle_visibility(sections, true);
            }
        });
    });
    hide_button.forEach((button) => {
        button.addEventListener("click", () => {
            hide_all_sections(true);
            current_section = null;
        });
    });
}
function activate_close_buttons(hide, sections_to_hide) {
    const hide_button = document.querySelectorAll(hide);
    const sections = document.querySelector(sections_to_hide);
    hide_button.forEach((button) => {
        button.addEventListener("click", () => {
            if (sections) {
                sections.remove();
                current_section = null;
            }
        });
    });
}
function toggle_visibility(element, should_display) {
    element.style.display = should_display ? "block" : "none";
}
function hide_panels(event = {}) {
    if (current_section && event.target instanceof HTMLElement && event.target !== current_section && !current_section.contains(event.target) && !event.target.classList.contains("modal-opener")) {
        if (current_section.classList.contains("feedback-panel")) {
            current_section.remove();
            return;
        }
        if (!current_section.classList.contains("to-keep-open")) {
            current_section.style.display = "none";
        }
    }
}
function hide_all_sections(section_destroy = false) {
    const sections = document.querySelectorAll(".modal-window");
    sections.forEach((section) => {
        if (section.classList.contains("to-destroy") && section_destroy) {
            section.remove();
        }
        section.style.display = "none";
    });
}
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
function initialize_player_swapper(players_count) {
    const players_selection = document.getElementsByClassName("player_selection");
    for (let i = 0; i < players_selection.length; i++) {
        players_selection[i].addEventListener("click", () => {
            swap_displayed_player(i % players_count);
        });
    }
}
function swap_displayed_player(player_id) {
    const players_display = document.getElementsByClassName("player_container");
    for (let i = 0; i < players_display.length; i++) {
        players_display[i].style.display = (player_id !== i) ? "none" : "block";
    }
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
        return fetch("./includes/functions/utility_functions.php?action=get_max_upload_size")
            .then(response => response.json())
            .then((data) => {
            return data.post_max_size;
        });
    });
}

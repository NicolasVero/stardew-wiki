var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
function file_choice(event) {
    const input = event.target;
    const new_filename = input.files ? input.files[0].name.substring(0, 12) : '';
    const filenameElement = document.getElementById("new-filename");
    if (filenameElement) {
        filenameElement.innerHTML = new_filename;
    }
    toggle_loading(true);
    AJAX_send();
}
// Upload File AJAX
function AJAX_send() {
    return __awaiter(this, void 0, void 0, function* () {
        var _a, _b, _c;
        const xml_upload = document.getElementById("save-upload");
        const file = (_a = xml_upload === null || xml_upload === void 0 ? void 0 : xml_upload.files) === null || _a === void 0 ? void 0 : _a[0];
        if (!file) {
            alert("An error occurred while uploading the file. Please try again.");
            return;
        }
        const max_upload_size = yield get_max_upload_size();
        const is_file_too_big = file.size > max_upload_size;
        const page_display = document.getElementById("display");
        const landing_menu = document.getElementById("landing_menu");
        const landing_page = (_c = (_b = document.getElementById("landing_page")) === null || _b === void 0 ? void 0 : _b.outerHTML) !== null && _c !== void 0 ? _c : '';
        if (landing_menu) {
            landing_menu.outerHTML = "";
        }
        page_display.innerHTML = "";
        const form_data = new FormData();
        const xhr = new XMLHttpRequest();
        const url = get_site_root() + "includes/get_xml_data.php";
        if (is_file_too_big) {
            form_data.append("save-upload", new File(["SizeException"], "Error_SizeException.xml"));
        }
        else {
            form_data.append("save-upload", file);
        }
        xhr.open("POST", url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                const html = data.html;
                page_display.innerHTML = html["sur_header"];
                page_display.innerHTML += landing_page;
                if (data.code === "success") {
                    const players_count = data.players.length;
                    for (let i = 0; i < players_count; i++) {
                        page_display.innerHTML += html["player_" + i];
                    }
                    initialize_player_swapper(players_count);
                    initialize_settings();
                    load_elements();
                }
                else {
                    page_display.innerHTML += html["error_message"];
                    load_error_page_items();
                }
                activate_feedback_ajax_trigger();
                toggle_visibility(current_section, false);
                toggle_loading(false);
            }
        };
        xhr.send(form_data);
    });
}
let surheader;
window.addEventListener("load", () => {
    const os_path = get_os_path(detect_os());
    const tag = document.getElementById("save_os_path");
    if (tag) {
        tag.innerHTML = os_path;
    }
    document.addEventListener("keyup", (event) => {
        if (event.key === "Escape") {
            hide_panels({});
        }
    });
    document.addEventListener("click", hide_panels);
    const toggleVersionsItemsMode = document.getElementById("toggle_versions_items_mode");
    const noSpoilMode = document.getElementById("no_spoil_mode");
    const spoilMode = document.getElementById("spoil_mode");
    if (toggleVersionsItemsMode) {
        toggleVersionsItemsMode.addEventListener("change", handle_toggle_versions_mode);
    }
    if (noSpoilMode) {
        noSpoilMode.addEventListener("change", handle_no_spoil_mode);
    }
    if (spoilMode) {
        spoilMode.addEventListener("change", handle_spoil_mode);
    }
    const saveUpload = document.getElementById("save-upload");
    if (saveUpload) {
        saveUpload.addEventListener("change", file_choice);
    }
    save_landing_surheader();
    activate_buttons(".landing-upload", ".exit-upload", ".upload-panel");
    activate_buttons(".landing-settings", ".exit-settings", ".settings");
    toggle_custom_checkboxes(".checkmark");
    activate_feedback_ajax_trigger();
});
const handle_no_spoil_mode = () => {
    const spoil_checkbox = document.getElementById("spoil_mode");
    const no_spoil_checkbox = document.getElementById("no_spoil_mode");
    if (no_spoil_checkbox && spoil_checkbox && no_spoil_checkbox.checked && spoil_checkbox.checked) {
        spoil_checkbox.checked = false;
    }
    update_display(["not-found", "found"]);
};
const handle_toggle_versions_mode = () => {
    update_display("newer-version");
};
const handle_spoil_mode = () => {
    const no_spoil_checkbox = document.getElementById("no_spoil_mode");
    const spoil_checkbox = document.getElementById("spoil_mode");
    if (no_spoil_checkbox && spoil_checkbox) {
        if (spoil_checkbox.checked && no_spoil_checkbox.checked) {
            no_spoil_checkbox.checked = false;
            update_display(["not-found", "found"]);
        }
        else {
            update_display(["found"]);
        }
    }
};
const initialize_settings = () => {
    handle_toggle_versions_mode();
    handle_no_spoil_mode();
    handle_spoil_mode();
};
function toggle_custom_checkboxes(checkmark_class) {
    const checkmarks = document.querySelectorAll(checkmark_class);
    checkmarks.forEach((checkbox) => {
        checkbox.addEventListener("click", () => {
            const adjacent_checkbox = checkbox.previousElementSibling;
            if (adjacent_checkbox && adjacent_checkbox.type === "checkbox") {
                adjacent_checkbox.checked = !adjacent_checkbox.checked;
                adjacent_checkbox.dispatchEvent(new Event("change"));
            }
        });
    });
}
function toggle_checkboxes_actions() {
    const checkboxes = document.querySelectorAll(".checkbox");
    checkboxes.forEach((checkbox) => {
        const checkbox_input = checkbox.querySelector("input[type='checkbox']");
        if (checkbox_input) {
            const function_name = checkbox_input.id;
            const is_checked = checkbox_input.checked;
            if (is_checked && typeof window[function_name] === "function") {
                window[function_name]();
            }
        }
    });
}
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
    if (!elements.length) {
        return;
    }
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
    if (!element) {
        return;
    }
    element = Array.from(((_a = element.previousElementSibling) === null || _a === void 0 ? void 0 : _a.children) || []).find((child) => child.tagName === "IMG");
    if (!element) {
        return;
    }
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
// Create feedback form
function activate_feedback_ajax_trigger() {
    const triggers = document.querySelectorAll(".feedback-opener");
    triggers.forEach(trigger => {
        trigger.addEventListener("click", () => {
            hide_all_sections();
            const existing_window = document.querySelector(".feedback-panel");
            if (existing_window) {
                toggle_visibility(existing_window, true);
            }
            else {
                feedback_form_creation();
            }
        });
    });
}
// Create feedback form
function feedback_form_creation() {
    const xml_upload = document.querySelector("body");
    fetch("./includes/functions/display_panels_functions.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            "action": "display_feedback_panel"
        })
    })
        .then(response => response.text())
        .then(data => {
        const temp_container = document.createElement("div");
        temp_container.innerHTML = data;
        while (temp_container.firstChild) {
            xml_upload === null || xml_upload === void 0 ? void 0 : xml_upload.appendChild(temp_container.firstChild);
        }
        current_section = document.querySelector(".feedback-panel");
        feedback_custom_radio();
        activate_feedback_form();
        activate_close_buttons(".exit-feedback", ".feedback-panel");
    })
        .catch(error => console.error("Error:", error));
}
// Feedback Form AJAX
function activate_feedback_form() {
    const form = document.getElementById("feedback_form");
    form === null || form === void 0 ? void 0 : form.addEventListener("submit", (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        fetch("./includes/sendmail.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then((data) => {
            const alert_message = data.success ? data.message : "Error submitting form: " + data.message;
            alert(alert_message);
        })
            .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while submitting the form.");
        });
    });
}
function feedback_custom_radio() {
    const feedback_fake_radios = document.querySelectorAll(".feedback_custom_radio");
    const feedback_real_radios = document.querySelectorAll(".feedback_real_radio");
    feedback_fake_radios.forEach(fake_radio => {
        const span_topic = fake_radio.parentElement;
        span_topic.addEventListener("click", () => {
            const real_radio = fake_radio.previousElementSibling;
            if (real_radio && real_radio.type === "radio") {
                real_radio.checked = true;
                real_radio.dispatchEvent(new Event("change"));
            }
        });
    });
    feedback_real_radios.forEach(real_radio => {
        real_radio.addEventListener("change", () => {
            feedback_fake_radios.forEach(fake_radio => {
                fake_radio.classList.add("topic_not_selected");
            });
            const fake_radio = real_radio.nextElementSibling;
            if (fake_radio && fake_radio.tagName === "IMG") {
                if (real_radio.checked) {
                    fake_radio.classList.remove("topic_not_selected");
                }
                else {
                    fake_radio.classList.add("topic_not_selected");
                }
            }
        });
    });
}
function load_error_page_items() {
    const buttons = [
        {
            open_button: ".main-settings",
            exit_button: ".exit-settings",
            modal_panel: ".settings"
        },
        {
            open_button: ".file-upload",
            exit_button: ".exit-upload",
            modal_panel: ".upload-panel"
        }
    ];
    buttons.forEach(button => {
        activate_buttons(button.open_button, button.exit_button, button.modal_panel);
    });
}
function load_elements() {
    var _a;
    toggle_landing_page(false);
    toggle_checkboxes_actions();
    const buttons = [
        {
            open_button: ".landing-settings",
            exit_button: ".exit-settings",
            modal_panel: ".settings"
        },
        {
            open_button: ".landing-upload",
            exit_button: ".exit-upload",
            modal_panel: ".upload-panel"
        },
        {
            open_button: ".main-settings",
            exit_button: ".exit-settings",
            modal_panel: ".settings"
        },
        {
            open_button: ".file-upload",
            exit_button: ".exit-upload",
            modal_panel: ".upload-panel"
        }
    ];
    const max_players_in_a_save = 8;
    for (let i = 0; i < max_players_in_a_save; i++) {
        buttons.push({
            open_button: `.view-all-friendships-${i}`,
            exit_button: `.exit-all-friendships-${i}`,
            modal_panel: `.all-friends-${i}`
        });
        buttons.push({
            open_button: `.view-all-quests-${i}`,
            exit_button: `.exit-all-quests-${i}`,
            modal_panel: `.all-quests-${i}`
        });
        buttons.push({
            open_button: `.view-monster-eradication-goals-${i}`,
            exit_button: `.exit-monster-eradication-goals-${i}`,
            modal_panel: `.monster-eradication-goals-${i}`
        });
        buttons.push({
            open_button: `.view-calendar-${i}`,
            exit_button: `.exit-calendar-${i}`,
            modal_panel: `.calendar-${i}`
        });
        buttons.push({
            open_button: `.view-all-animals-${i}`,
            exit_button: `.exit-all-animals-${i}`,
            modal_panel: `.all-animals-${i}`
        });
        buttons.push({
            open_button: `.view-junimo-kart-leaderboard-${i}`,
            exit_button: `.exit-junimo-kart-leaderboard-${i}`,
            modal_panel: `.junimo-kart-leaderboard-${i}`
        });
    }
    buttons.forEach(button => {
        activate_buttons(button.open_button, button.exit_button, button.modal_panel);
    });
    (_a = document.getElementById("home-icon")) === null || _a === void 0 ? void 0 : _a.addEventListener("click", () => {
        var _a;
        const display = (((_a = document.getElementById("landing_page")) === null || _a === void 0 ? void 0 : _a.style.display) === "none") ? true : false;
        toggle_landing_page(display);
    });
    load_easter_eggs();
    update_tooltips_after_ajax();
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
const get_settings = () => ({
    no_spoil: document.getElementById("no_spoil_mode").checked,
    toggle_versions: document.getElementById("toggle_versions_items_mode").checked,
    spoil: document.getElementById("spoil_mode").checked
});
function update_tooltips_after_ajax() {
    on_images_loaded(() => {
        initialize_tooltips();
        swap_displayed_player(0);
        toggle_scroll(true);
    });
}
function initialize_tooltips() {
    const tooltips = document.querySelectorAll(".tooltip");
    tooltips.forEach((tooltip) => {
        const window_width = window.innerWidth;
        const rect = tooltip.getBoundingClientRect();
        const span = tooltip.querySelector("span");
        if (span && !span.classList.contains("left") && !span.classList.contains("right")) {
            if (rect.left < window_width / 2) {
                span.classList.add("right");
            }
            else {
                span.classList.add("left");
            }
        }
    });
}
function on_images_loaded(callback) {
    toggle_scroll(false);
    const images = document.querySelectorAll("img");
    let images_loaded = 0;
    const total_images = images.length;
    if (total_images === 0) {
        callback();
        return;
    }
    images.forEach((image) => {
        if (image.complete) {
            images_loaded++;
        }
        else {
            image.addEventListener("load", () => {
                images_loaded++;
                if (images_loaded === total_images) {
                    callback();
                }
            });
            image.addEventListener("error", () => {
                images_loaded++;
                if (images_loaded === total_images) {
                    callback();
                }
            });
        }
    });
    if (images_loaded === total_images) {
        callback();
    }
}
const update_section_visibility = (section, settings) => {
    var _a;
    const title = section.querySelector("h2");
    const smaller_title = (_a = section.children[1]) === null || _a === void 0 ? void 0 : _a.querySelector("span .no-spoil-title");
    const is_empty = is_section_empty(section);
    const has_older_items = has_section_older_version_items(section);
    if (settings.toggle_versions && is_empty && !has_older_items) {
        if (title)
            title.style.display = "none";
        if (smaller_title)
            smaller_title.style.display = "none";
        return;
    }
    if (title)
        title.style.display = "block";
    if (smaller_title) {
        const should_show_smaller_title = settings.no_spoil ?
            is_empty :
            (settings.toggle_versions && is_empty && has_older_items);
        smaller_title.style.display = should_show_smaller_title ? "block" : "none";
    }
};
const update_display = (target_classes) => {
    const settings = get_settings();
    const update_elements = (class_name) => {
        const elements = document.getElementsByClassName(class_name);
        Array.from(elements).forEach((element) => {
            const parent = get_parent_element(element);
            if (parent) {
                set_element_display(parent, should_show_element(element, settings));
            }
        });
    };
    if (Array.isArray(target_classes)) {
        target_classes.forEach(update_elements);
    }
    else {
        update_elements(target_classes);
    }
    const sections = document.getElementsByClassName("gallery");
    Array.from(sections).forEach((section) => update_section_visibility(section, settings));
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
function in_bytes_conversion(size) {
    const unit_to_power = { "o": 0, "Ko": 1, "Mo": 2, "Go": 3 };
    const matches = size.match(/(\d+)([a-zA-Z]+)/);
    if (!matches) {
        throw new Error("Invalid size format");
    }
    const value = parseInt(matches[1], 10);
    const unit = matches[2];
    return value * Math.pow(1024, unit_to_power[unit]);
}
function toggle_scroll(can_scroll) {
    document.body.style.overflow = can_scroll ? "auto" : "hidden";
}
function toggle_loading(shown) {
    const loadingStrip = document.getElementById("loading-strip");
    if (loadingStrip) {
        loadingStrip.style.display = shown ? "block" : "none";
    }
}
const get_parent_element = (element) => {
    if (!element) {
        return null;
    }
    const parent = element.parentElement;
    return (parent === null || parent === void 0 ? void 0 : parent.classList.contains("wiki_link")) ? parent.parentElement : parent;
};
const set_element_display = (element, show) => {
    if (element) {
        element.style.display = show ? "flex" : "none";
    }
};
const has_class = (element, class_name) => {
    return element.classList.contains(class_name);
};
const is_section_empty = (section) => {
    const spans = section.querySelectorAll(".tooltip");
    return Array.from(spans).every(span => span.style.display === "none");
};
const has_section_older_version_items = (section) => {
    return Array.from(section.querySelectorAll("img")).some((img) => has_class(img, "older-version"));
};
const should_show_element = (element, settings) => {
    const is_newer = has_class(element, "newer-version");
    const is_not_found = has_class(element, "not-found");
    const should_keep_on_display = has_class(element, "always-on-display");
    const is_found = has_class(element, "found");
    const is_not_hide = has_class(element, "not-hide");
    if (is_not_hide)
        return true;
    if (settings.toggle_versions && is_newer)
        return false;
    if (settings.no_spoil && is_not_found && !should_keep_on_display)
        return false;
    if (settings.spoil && is_found)
        return false;
    return true;
};
function toggle_landing_page(display) {
    const landing_page = document.getElementById("landing_page");
    if (landing_page) {
        landing_page.style.display = display ? "block" : "none";
    }
}
function save_landing_surheader() {
    const landing_menu = document.getElementById("landing_menu");
    if (landing_menu) {
        const surheader = landing_menu.innerHTML;
    }
}
function wiki_redirections() {
    const links = document.querySelectorAll("a");
    links.forEach((link) => {
        link.addEventListener("click", (event) => {
            const wikiRedirectionsCheckbox = document.getElementById("wiki_redirections");
            if (!wikiRedirectionsCheckbox.checked) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        });
    });
}

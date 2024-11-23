// Types explicites pour les réponses
interface AjaxResponse {
    code: string;
    html: Record<string, string>;
    players: { length: number }[];
}

interface FeedbackResponse {
    success: boolean;
    message: string;
}

// Upload File AJAX
async function AJAX_send(): Promise<void> {
    const xml_upload = document.getElementById("save-upload") as HTMLInputElement;
    const file = xml_upload?.files?.[0];

    if (!file) {
        alert("An error occurred while uploading the file. Please try again.");
        return;
    }

    const max_upload_size = await get_max_upload_size();
    const is_file_too_big = file.size > max_upload_size;
    const page_display = document.getElementById("display") as HTMLElement;
    const landing_menu = document.getElementById("landing_menu");
    const landing_page = document.getElementById("landing_page")?.outerHTML ?? '';

    if (landing_menu) {
        landing_menu.outerHTML = "";
    }

    page_display.innerHTML = "";

    const form_data = new FormData();
    const xhr = new XMLHttpRequest();
    const url = get_site_root() + "includes/get_xml_data.php";

    if (is_file_too_big) {
        form_data.append("save-upload", new File(["SizeException"], "Error_SizeException.xml"));
    } else {
        form_data.append("save-upload", file);
    }

    xhr.open("POST", url, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const data: AjaxResponse = JSON.parse(xhr.responseText);
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

            } else {
                page_display.innerHTML += html["error_message"];
                loard_error_page_items();
            }

            activate_feedback_ajax_trigger();
            toggle_visibility(current_section, false);
            toggle_loading(false);
        }
    };

    xhr.send(form_data);
}

// Create feedback form
function activate_feedback_ajax_trigger(): void {
    const triggers = document.querySelectorAll(".feedback-opener");

    triggers.forEach(trigger => {
        trigger.addEventListener("click", () => {
            hide_all_sections();

            const existing_window = document.querySelector(".feedback-panel");
            if (existing_window) {
                toggle_visibility(existing_window as HTMLElement, true);
            } else {
                feedback_form_creation();
            }
        });
    });
}

// Create feedback form
function feedback_form_creation(): void {
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
            // Eviter de reparser le body entièrement
            const tempContainer = document.createElement("div");
            tempContainer.innerHTML = data;

            while (tempContainer.firstChild) {
                xml_upload?.appendChild(tempContainer.firstChild);
            }

            current_section = document.querySelector(".feedback-panel");

            feedback_custom_radio();
            activate_feedback_form();
            activate_close_buttons(".exit-feedback", ".feedback-panel");
        })
        .catch(error => console.error("Error:", error));
}

// Feedback Form AJAX
function activate_feedback_form(): void {
    const form = document.getElementById("feedback_form") as HTMLFormElement;
    form?.addEventListener("submit", (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        fetch("./includes/sendmail.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then((data: FeedbackResponse) => {
                const alert_message = data.success ? data.message : "Error submitting form: " + data.message;
                alert(alert_message);
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while submitting the form.");
            });
    });
}

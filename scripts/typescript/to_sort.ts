const get_settings = (): { no_spoil: boolean, toggle_versions: boolean, spoil: boolean } => ({
    no_spoil: (document.getElementById("no_spoil_mode") as HTMLInputElement).checked,
    toggle_versions: (document.getElementById("toggle_versions_items_mode") as HTMLInputElement).checked,
    spoil: (document.getElementById("spoil_mode") as HTMLInputElement).checked
});

const should_show_element = (element, settings) => {
    const is_newer = has_class(element, "newer-version");
    const is_not_found = has_class(element, "not-found");
    const should_keep_on_display = has_class(element, "always-on-display");
    const is_found = has_class(element, "found");
    const is_not_hide = has_class(element, "not-hide");

    if(is_not_hide) return true;
    if(settings.toggle_versions && is_newer) return false;
    if(settings.no_spoil && is_not_found && !should_keep_on_display) return false;
    if(settings.spoil && is_found) return false;
    
    return true;
};

const is_section_empty = (section: HTMLElement): boolean => {
    const spans = section.querySelectorAll(".tooltip") as NodeListOf<HTMLElement>;
    return Array.from(spans).every(span => span.style.display === "none");
};

const has_section_older_version_items = (section: HTMLElement): boolean => {
    return Array.from(section.querySelectorAll("img")).some((img: HTMLImageElement) => 
        has_class(img, "older-version")
    );
};


function wiki_redirections(): void {
    const links = document.querySelectorAll("a");

    links.forEach((link) => {
        link.addEventListener("click", (event: MouseEvent) => {
            const wikiRedirectionsCheckbox = document.getElementById("wiki_redirections") as HTMLInputElement;

            if (!wikiRedirectionsCheckbox.checked) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        });
    });
}



function file_choice(event) {
	const new_filename = event.target.files[0].name.substring(0, 12);
	document.getElementById("new-filename").innerHTML = new_filename;
	toggle_loading(true);
	AJAX_send();
}

function toggle_landing_page(display) {
	const landing_page = document.getElementById("landing_page");

	if(landing_page)
		landing_page.style.display = (display) ? "block" : "none";
}


function loard_error_page_items(): void {
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

function load_elements(): void {
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

	document.getElementById("home-icon")?.addEventListener("click", () => {
		const display = (document.getElementById("landing_page")?.style.display === "none") ? true : false;
		toggle_landing_page(display);
	});

	load_easter_eggs();
	update_tooltips_after_ajax();
}

function feedback_custom_radio(): void {
    const feedback_fake_radios = document.querySelectorAll(".feedback_custom_radio");
    const feedback_real_radios = document.querySelectorAll(".feedback_real_radio");

    feedback_fake_radios.forEach(fake_radio => {
        const span_topic = fake_radio.parentElement!;
        span_topic.addEventListener("click", () => {
            const real_radio = fake_radio.previousElementSibling as HTMLInputElement;
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

            const fake_radio = real_radio.nextElementSibling as HTMLElement;
            if (fake_radio && fake_radio.tagName === "IMG") {
                if ((real_radio as HTMLInputElement).checked) {
                    fake_radio.classList.remove("topic_not_selected");
                } else {
                    fake_radio.classList.add("topic_not_selected");
                }
            }
        });
    });
}


function save_landing_surheader(): void {
	const landing_menu = document.getElementById("landing_menu");
	if (landing_menu) {
		const surheader = landing_menu.innerHTML;
	}
}

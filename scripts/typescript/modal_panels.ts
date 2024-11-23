let current_section: HTMLElement | null = null;

function activate_buttons(show: string, hide: string, sections_to_show: string):void {
    const show_button: NodeListOf<HTMLElement> = document.querySelectorAll(show);
    const hide_button: NodeListOf<HTMLElement> = document.querySelectorAll(hide);
    const sections: HTMLElement | null = document.querySelector(sections_to_show);

    show_button.forEach((button) => {
        button.addEventListener("click", () => {
            hide_all_sections(true);
            if(sections) {
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

function activate_close_buttons(hide: string, sections_to_hide: string):void {
    const hide_button: NodeListOf<HTMLElement> = document.querySelectorAll(hide);
    const sections: HTMLElement | null = document.querySelector(sections_to_hide);

    hide_button.forEach((button) => {
        button.addEventListener("click", () => {
            if(sections) {
                sections.remove();
                current_section = null;
            }
        });
    });
}

function toggle_visibility(element: HTMLElement, should_display: boolean):void {
    element.style.display = should_display ? "block" : "none";
}

function hide_panels(event: MouseEvent = {} as MouseEvent):void {
    if(current_section && event.target instanceof HTMLElement && event.target !== current_section && !current_section.contains(event.target) && !event.target.classList.contains("modal-opener")) {
        if(current_section.classList.contains("feedback-panel")) {
            current_section.remove();
            return;
        }

        if(!current_section.classList.contains("to-keep-open")) {
            current_section.style.display = "none";
        }
    }
}

function hide_all_sections(section_destroy: boolean = false):void {
	const sections: NodeListOf<HTMLElement> = document.querySelectorAll(".modal-window");

	sections.forEach((section) => {
		if(section.classList.contains("to-destroy") && section_destroy) {
			section.remove();
		}

		section.style.display = "none";
	});
}

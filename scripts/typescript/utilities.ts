function get_site_root():string { 
    const protocol: string = window.location.protocol;
    const host: string = window.location.host;

    return (host === "localhost") 
        ? `${protocol}//localhost/travail/stardew_dashboard` 
        : `${protocol}//stardew-dashboard.42web.io`;
}

async function get_max_upload_size():Promise<number> {
	return fetch("./includes/functions/utility_functions.php?action=get_max_upload_size")
		.then(response => response.json())
		.then((data: { post_max_size: number }) => {
			return data.post_max_size;
		}
    );
}

function in_bytes_conversion(size: string):number {
    const unit_to_power: { [key: string]: number } = { "o": 0, "Ko": 1, "Mo": 2, "Go": 3 };

    const matches = size.match(/(\d+)([a-zA-Z]+)/);
    if(!matches) {
        throw new Error("Invalid size format");
    }

    const value: number = parseInt(matches[1], 10);
    const unit: string = matches[2];

    return value * Math.pow(1024, unit_to_power[unit]);
}

function toggle_scroll(can_scroll: boolean):void {
    document.body.style.overflow = (can_scroll) ? "auto" : "hidden";
}

function toggle_loading(shown: boolean):void {
    const loadingStrip = document.getElementById("loading-strip");
    if(loadingStrip) {
        loadingStrip.style.display = (shown) ? "block" : "none";
    }
}

function get_parent_element(element: HTMLElement | null):HTMLElement | null {
    if(!element) {
        return null;
    }

    const parent = element.parentElement;
    return parent?.classList.contains("wiki_link") ? parent.parentElement : parent;
};

function set_element_display(element: HTMLElement | null, show: boolean):void {
    if(element && element.className !== "locations") {
        element.style.display = show ? "flex" : "none";
    }
};

function has_class(element: HTMLElement, class_name: string):boolean {
    return element.classList.contains(class_name);
};

function is_section_empty(section: HTMLElement):boolean {
    const spans = section.querySelectorAll(".tooltip") as NodeListOf<HTMLElement>;
    return Array.from(spans).every(span => span.style.display === "none");
};

function has_section_older_version_items (section: HTMLElement):boolean {
    return Array.from(section.querySelectorAll("img")).some((img: HTMLImageElement) => 
        has_class(img, "older-version")
    );
};

function should_show_element(element, settings) {
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

function toggle_landing_page(display: boolean):void {
    const landing_page = document.getElementById("landing_page");

    if (landing_page) {
        landing_page.style.display = display ? "block" : "none";
    }
}

function save_landing_surheader():void {
	const landing_menu = document.getElementById("landing_menu");
	if(landing_menu) {
		const surheader = landing_menu.innerHTML;
	}
}
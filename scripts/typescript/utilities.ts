function get_site_root():string { 
    const protocol: string = window.location.protocol;
    const host: string = window.location.host;

    return (host === "localhost") 
        ? `${protocol}//localhost/travail/stardew_dashboard/` 
        : `${protocol}//stardew-dashboard.42web.io/`;
}

async function get_max_upload_size():Promise<number> {
	return fetch("./includes/functions/utility_functions.php?action=get_max_upload_size")
		.then(response => response.json())
		.then((data: { post_max_size: number }) => {
			return data.post_max_size;
		}
    );
}

function in_bytes_conversion(size: string): number {
    const unit_to_power: { [key: string]: number } = { "o": 0, "Ko": 1, "Mo": 2, "Go": 3 };

    const matches = size.match(/(\d+)([a-zA-Z]+)/);
    if(!matches) {
        throw new Error("Invalid size format");
    }

    const value: number = parseInt(matches[1], 10);
    const unit: string = matches[2];

    return value * Math.pow(1024, unit_to_power[unit]);
}

function toggle_scroll(can_scroll: boolean): void {
    document.body.style.overflow = can_scroll ? "auto" : "hidden";
}

function toggle_loading(shown: boolean): void {
    const loadingStrip = document.getElementById("loading-strip");
    if (loadingStrip) {
        loadingStrip.style.display = shown ? "block" : "none";
    }
}


const get_parent_element = (element: HTMLElement | null): HTMLElement | null => {
    if (!element) return null;
    const parent = element.parentElement;
    // Vérifie si le parent a la classe "wiki_link", dans ce cas on retourne le grand-parent
    return parent?.classList.contains("wiki_link") ? parent.parentElement : parent;
};

const set_element_display = (element: HTMLElement | null, show: boolean): void => {
    if (element) {
        element.style.display = show ? "flex" : "none";
    }
};

const has_class = (element: HTMLElement, class_name: string): boolean => {
    return element.classList.contains(class_name);
};

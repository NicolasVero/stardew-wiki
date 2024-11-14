function toggle_custom_checkboxes(checkmark_class) {
	const checkmarks = document.querySelectorAll(checkmark_class);
	checkmarks.forEach(function(checkbox) {
		checkbox.addEventListener("click", function() {
			const adjacent_checkbox = checkbox.previousElementSibling;
			if(adjacent_checkbox && adjacent_checkbox.type === "checkbox") {
				adjacent_checkbox.checked = (!adjacent_checkbox.checked) ? true : false;
				adjacent_checkbox.dispatchEvent(new Event("change"));
			}
		});
	});   
}

function toggle_checkboxes_actions() {
	const checkboxes = document.querySelectorAll(".checkbox");
	checkboxes.forEach(function(checkbox) {
		const checkbox_input = checkbox.querySelector("input[type='checkbox']");
		if(checkbox_input) {
			const function_name = checkbox_input.id;
			const is_checked = checkbox_input.checked;

			if(is_checked && typeof window[function_name] === "function")
				window[function_name]();
		}
	});
}

const get_settings = () => ({
    no_spoil: document.getElementById("no_spoil_mode").checked,
    toggle_versions: document.getElementById("toggle_versions_items_mode").checked,
    spoil: document.getElementById("spoil_mode").checked
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

const is_section_empty = (section) => {
    const spans = section.querySelectorAll(".tooltip");
    return Array.from(spans).every(span => span.style.display === "none");
};

const has_section_older_version_items = (section) => {
    return Array.from(section.querySelectorAll("img")).some(img => 
        has_class(img, "older-version")
    );
};

const update_section_visibility = (section, settings) => {
    const title = section.querySelector("h2");
    const smaller_title = section.children[1]?.querySelector("span .no-spoil-title");
    const is_empty = is_section_empty(section);
    const has_older_items = has_section_older_version_items(section);

    if(settings.toggle_versions && is_empty && !has_older_items) {
        if(title) title.style.display = "none";
        if(smaller_title) smaller_title.style.display = "none";
        return;
    }

    if(title)
        title.style.display = "block";

    if(smaller_title) {
        const should_show_smaller_title = 
            settings.no_spoil ? 
                is_empty :
                (settings.toggle_versions && is_empty && has_older_items);

        smaller_title.style.display = should_show_smaller_title ? "block" : "none";
    }
};

const update_display = (target_classes) => {
    const settings = get_settings();
    if(Array.isArray(target_classes)) {
        target_classes.forEach(class_name => {
            const elements = document.getElementsByClassName(class_name);
            Array.from(elements).forEach(element => {
                const parent = get_parent_element(element);
                if(parent)
                    set_element_display(parent, should_show_element(element, settings));
            });
        });
    } else {
        const elements = document.getElementsByClassName(target_classes);
        Array.from(elements).forEach(element => {
            const parent = get_parent_element(element);
            if(parent)
                set_element_display(parent, should_show_element(element, settings));
        });
    }

    const sections = document.getElementsByClassName("gallery");
    Array.from(sections).forEach(section => 
        update_section_visibility(section, settings)
    );
};

const handle_no_spoil_mode = () => {
    const spoil_checkbox = document.getElementById("spoil_mode");
    if(document.getElementById("no_spoil_mode").checked && spoil_checkbox.checked)
        spoil_checkbox.checked = false;
    update_display(["not-found", "found"]);
};

const handle_toggle_versions_mode = () => {
    update_display("newer-version");
};

const handle_spoil_mode = () => {
    const no_spoil_checkbox = document.getElementById("no_spoil_mode");
    if(document.getElementById("spoil_mode").checked && no_spoil_checkbox.checked) {
        no_spoil_checkbox.checked = false;
        update_display(["not-found", "found"]);
    } else {
        update_display(["found"]);
    }
};

const initialize_settings = () => {
    handle_toggle_versions_mode();
    handle_no_spoil_mode();
    handle_spoil_mode();
};

function wiki_redirections() {
	const links = document.querySelectorAll("a");
	
	links.forEach((link) => {
		link.addEventListener("click", (event) => {
			if(!document.getElementById("wiki_redirections").checked) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}
		});
	});
}
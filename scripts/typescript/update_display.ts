interface Settings {
    toggle_versions: boolean;
    no_spoil: boolean;
}

function update_section_visibility(section: HTMLElement, settings: Settings):void {
    const title = section.querySelector("h2");
    const smaller_title = section.children[1]?.querySelector("span .no-spoil-title") as HTMLElement;
    const is_empty = is_section_empty(section);
    const has_older_items = has_section_older_version_items(section);

    if(settings.toggle_versions && is_empty && !has_older_items) {
        if(title) title.style.display = "none";
        if(smaller_title) smaller_title.style.display = "none";
        return;
    }

    if(title) {   
        title.style.display = "block";
    }

    if(smaller_title) {
        const should_show_smaller_title = (settings.no_spoil) 
            ? is_empty 
            : (settings.toggle_versions && is_empty && has_older_items);

        smaller_title.style.display = should_show_smaller_title ? "block" : "none"; 
    }
};

function update_display(target_classes: string | string[]):void {
    const settings = get_settings();

    const update_elements = (class_name: string) => {
        const elements = document.getElementsByClassName(class_name);
        Array.from(elements).forEach((element: HTMLElement) => {
            const parent = get_parent_element(element);
            if(parent) {
                set_element_display(parent, should_show_element(element, settings));
            }
        });
    };

    if(Array.isArray(target_classes)) {
        target_classes.forEach(update_elements);
    } else {
        update_elements(target_classes);
    }

    const sections = document.getElementsByClassName("gallery");
    Array.from(sections).forEach((section: HTMLElement) => 
        update_section_visibility(section, settings)
    );
};

function toggle_custom_checkboxes(checkmark_class) {
    const checkmarks = document.querySelectorAll(checkmark_class);
    checkmarks.forEach(function(checkbox) {
        checkbox.addEventListener("click", function() {
            const adjacent_checkbox = checkbox.previousElementSibling;
            if (adjacent_checkbox && adjacent_checkbox.type === "checkbox") {
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
        if (checkbox_input) {
            const function_name = checkbox_input.id;
            const is_checked = checkbox_input.checked;

            if (is_checked && typeof window[function_name] === "function")
                window[function_name]();
        }
    });
}

// SETTINGS
function no_spoil_mode(event) {
    event = event || {};
    const is_checked = (event.target) ? event.target.checked : true;
    const elements = document.getElementsByClassName("not-found");

    const tvim_checked = document.getElementById("toggle_versions_items_mode").checked;

    for(let i = 0; i < elements.length; i++) {
        let parent_element = elements[i].parentElement;

		if(parent_element.classList.contains('wiki_link'))
			parent_element = parent_element.parentElement;

        if(parent_element && !elements[i].classList.contains("not-hide")) {
            is_element_newer_version = elements[i].classList.contains("newer-version");
            if(!tvim_checked)
                parent_element.style.display = (is_checked) ? "none" : "flex";
            else
                parent_element.style.display = (is_element_newer_version) ? "none" : (is_checked) ? "none" : "flex";
        }
    }

    check_if_all_elements_hidden(1);
}

function toggle_versions_items_mode(event) {
    event = event || {};
    const is_checked = (event.target) ? event.target.checked : true;

    const elements = document.getElementsByClassName("newer-version");
    const nsm_checked = document.getElementById("no_spoil_mode").checked;

    for(let i = 0; i < elements.length; i++) {
        let parent_element = elements[i].parentElement;
        is_element_not_found = elements[i].classList.contains("not-found");

		if(parent_element.classList.contains('wiki_link'))
			parent_element = parent_element.parentElement;

        if(!nsm_checked)
            parent_element.style.display = (is_checked) ? "none" : "flex";
        else
            parent_element.style.display = (is_element_not_found) ? "none" : (is_checked) ? "none" : "flex";
    }

    check_if_all_elements_hidden(0);
}


function check_if_all_elements_hidden(id_command) {
    const sections = document.getElementsByClassName("gallery");

    const nsm_checked = document.getElementById("no_spoil_mode").checked;
    const tvim_checked = document.getElementById("toggle_versions_items_mode").checked;

    for(let i = 0; i < sections.length; i++) {
        no_items_to_show = true;

        const section = sections[i];
        const title = section.querySelector("h2");
        const smaller_title = section.children[1].querySelector("span .no-spoil-title");
        const spans = section.querySelectorAll(".tooltip");

        spans.forEach(span => {
            if (span.style.display != "none")
                no_items_to_show = false;
        });
        
		switch(id_command) {
			case 0:
				title.style.display = tvim_checked ? (no_items_to_show ? "none" : title.style.display) : "block";
				smaller_title.style.display = tvim_checked ? (no_items_to_show ? "none" : smaller_title.style.display) : (no_items_to_show && nsm_checked ? "block" : smaller_title.style.display);				
				break;
			case 1:
				smaller_title.style.display = nsm_checked ? (no_items_to_show ? (!tvim_checked ? "block" : smaller_title.style.display) : smaller_title.style.display) : "none";
				break;
		}
    }
}

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
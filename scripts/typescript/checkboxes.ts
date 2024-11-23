function toggle_custom_checkboxes(checkmark_class: string):void {
    const checkmarks: NodeListOf<HTMLElement> = document.querySelectorAll(checkmark_class);

    checkmarks.forEach((checkbox) => {
        checkbox.addEventListener("click", () => {
            const adjacent_checkbox = checkbox.previousElementSibling as HTMLInputElement;
            if(adjacent_checkbox && adjacent_checkbox.type === "checkbox") {
                adjacent_checkbox.checked = !adjacent_checkbox.checked;
                adjacent_checkbox.dispatchEvent(new Event("change"));
            }
        });
    });
}

function toggle_checkboxes_actions():void {
    const checkboxes: NodeListOf<HTMLElement> = document.querySelectorAll(".checkbox");

    checkboxes.forEach((checkbox) => {
        const checkbox_input = checkbox.querySelector("input[type='checkbox']") as HTMLInputElement;
        if(checkbox_input) {
            const function_name = checkbox_input.id;
            const is_checked = checkbox_input.checked;

            if(is_checked && typeof window[function_name] === "function") {
                (window[function_name] as Function)();
            }
        }
    });
}

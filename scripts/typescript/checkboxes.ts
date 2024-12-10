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

function toggle_checkboxes_actions(): void {
    document.querySelectorAll(".checkbox input[type='checkbox']").forEach((checkbox_input) => {
        const input = checkbox_input as HTMLInputElement;
        const function_name = input.id;
        const is_checked = input.checked;

        if(is_checked && typeof window[function_name] === "function") {
            (window[function_name] as Function)();
        }
    });
}

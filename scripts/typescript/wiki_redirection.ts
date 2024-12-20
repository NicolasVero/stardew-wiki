function wiki_redirections(): void {
    const links: NodeListOf<HTMLAnchorElement> = document.querySelectorAll("a");

    links.forEach((link: HTMLAnchorElement) => {
        link.addEventListener("click", (event: MouseEvent) => {
            const wiki_redirections_checkbox = document.getElementById("wiki_redirections") as HTMLInputElement;

            if(!wiki_redirections_checkbox.checked) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        });
    });
}
function wiki_redirections():void {
    const links = document.querySelectorAll("a");

    links.forEach((link) => {
        link.addEventListener("click", (event: MouseEvent) => {
            const wikiRedirectionsCheckbox = document.getElementById("wiki_redirections") as HTMLInputElement;

            if(!wikiRedirectionsCheckbox.checked) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        });
    });
}
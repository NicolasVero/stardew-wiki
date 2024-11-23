function update_tooltips_after_ajax():void {
    on_images_loaded(() => {
        initialize_tooltips();
        swap_displayed_player(0);
        toggle_scroll(true);
    });
}

function initialize_tooltips():void {
    const tooltips: NodeListOf<HTMLElement> = document.querySelectorAll(".tooltip");
    
    tooltips.forEach((tooltip: HTMLElement) => {
        const window_width: number = window.innerWidth;
        const rect: DOMRect = tooltip.getBoundingClientRect();
        const span: HTMLElement | null = tooltip.querySelector("span");

        if(span && !span.classList.contains("left") && !span.classList.contains("right")) {
            if(rect.left < window_width / 2) {
                span.classList.add("right");
            } else {
                span.classList.add("left");
            }
        }
    });
}

function on_images_loaded(callback: () => void):void {
    toggle_scroll(false);
    const images: NodeListOf<HTMLImageElement> = document.querySelectorAll("img");
    let images_loaded: number = 0;
    const total_images: number = images.length;

    if(total_images === 0) {
        callback();
        return;
    }

    images.forEach((image: HTMLImageElement) => {
        if(image.complete) {
            images_loaded++;
        } else {
            image.addEventListener("load", () => {
                images_loaded++;
                if(images_loaded === total_images) {
                    callback();
                }
            });
            image.addEventListener("error", () => {
                images_loaded++;
                if(images_loaded === total_images) {
                    callback();
                }
            });
        }
    });

    if(images_loaded === total_images) {
        callback();
    }
}

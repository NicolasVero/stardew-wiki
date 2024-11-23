function load_easter_eggs():void {
    easter_egg_characters();
    easter_egg_kaaris();
}

function easter_egg_characters():void {
	const characters: string[] = [
		"abigail", "alex", "caroline", "clint", "demetrius", "elliott", "emily",
		"evelyn", "george", "gus", "haley", "harvey", "jas", "jodi", "kent", "leah",
		"lewis", "linus", "marnie", "maru", "pam", "penny", "pierre", "robin",
		"sam", "sandy", "sebastian", "shane", "vincent", "willy", "wizard"
	];

	const date: Date = new Date(); 
	const index_picker: number = [
		new Date(date.getFullYear(), 0, 1).getTime(),
		date.getUTCMonth(),
		date.getUTCDate()
	].reduce((acc, val) => acc * val, 1) % characters.length;

	const character: string = characters[index_picker];
	const elements: NodeListOf<Element> = document.querySelectorAll(".character-name." + character);

	if(!elements.length) {
		return;
	}

	const audio: HTMLAudioElement = new Audio(get_site_root() + "medias/audio/trigger.mp3");
	let is_playing: boolean = false;

	const play_once = ():void => {
		if(!is_playing) {
			is_playing = true;

			const full_screen_image: HTMLImageElement = document.createElement("img");
			full_screen_image.src = `https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/medias/images/characters/${character}.png`;
			full_screen_image.classList.add("fullscreen-image");
			document.body.appendChild(full_screen_image);

			full_screen_image.classList.add("show");

			audio.play().finally(() => {
				is_playing = false;
			});

			setTimeout(() => {
				full_screen_image.classList.remove("show");

				full_screen_image.addEventListener("transitionend", () => {
					full_screen_image.remove();
				});
			}, 1000);
		}
	};

	elements.forEach(element => {
		element.addEventListener("dblclick", play_once);
	});
}

function easter_egg_kaaris():void {
    let element: HTMLElement | null = document.querySelector(".house");

    if(!element) {
		return;
	}

    element = Array.from(element.previousElementSibling?.children || []).find(
        (child) => child.tagName === "IMG"
    ) as HTMLElement | null;

    if(!element) {
		return;
	}

    element.classList.add("easter_egg_kaaris");

    const audio: HTMLAudioElement = new Audio(get_site_root() + "medias/audio/kaaris_maison-citrouille.mp3");
    let is_playing: boolean = false;

    const play_once = (): void => {
        if(!is_playing) {
            is_playing = true;
            audio.play().finally(() => {
                is_playing = false;
            });
        }
    };

    element.addEventListener("dblclick", play_once);
}

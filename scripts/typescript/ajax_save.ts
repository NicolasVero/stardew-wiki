// async function AJAX_send(): Promise<void> {
// 	const xml_upload: HTMLInputElement | null = document.getElementById("save-upload") as HTMLInputElement;
// 	const file: File | undefined = xml_upload?.files?.[0];

// 	const max_upload_size: number = await get_max_upload_size();

// 	if (!file) {
// 		alert("An error occurred while uploading the file. Please try again.");
// 		return;
// 	}

// 	const is_file_too_big: boolean = file.size > max_upload_size;

// 	const page_display: HTMLElement | null = document.getElementById("display");
// 	const landing_menu: HTMLElement | null = document.getElementById("landing_menu");
// 	const landing_page: string = document.getElementById("landing_page")?.outerHTML || '';

// 	if (landing_menu) {
// 		landing_menu.outerHTML = "";
// 	}

// 	if (page_display) {
// 		page_display.innerHTML = "";
// 	}

// 	let form_data = new FormData();
// 	let xhr = new XMLHttpRequest();
// 	const url: string = get_site_root() + "includes/get_xml_data.php";

// 	if (is_file_too_big) {
// 		form_data.append("save-upload", new File(["SizeException"], "Error_SizeException.xml"));
// 	} else {
// 		form_data.append("save-upload", file);
// 	}

// 	xhr.open("POST", url, true);

// 	xhr.onreadystatechange = function () {
// 		if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

// 			const data: { code: string; html: Record<string, string>; players: Array<any>; } = JSON.parse(xhr.responseText);
// 			const html: Record<string, string> = data.html;

// 			if (data.code === "success") {

// 				const players_count: number = data.players.length;

// 				if (page_display) {
// 					page_display.innerHTML = html["sur_header"];
// 					page_display.innerHTML += landing_page;

// 					for (let i = 0; i < players_count; i++) {
// 						page_display.innerHTML += html["player_" + i];
// 					}

// 					initialize_player_swapper(players_count);
// 					initialize_settings();
// 					load_elements();
// 				}

// 			} else {
// 				if (page_display) {
// 					page_display.innerHTML = html["sur_header"];
// 					page_display.innerHTML += landing_page;
// 					toggle_landing_page(false);
// 					page_display.innerHTML += html["error_message"];
// 					loard_error_page_items();
// 				}
// 			}

// 			activate_feedback_ajax_trigger();
// 			toggle_visibility(current_section, false);
// 			toggle_loading(false);
// 		}
// 	};

// 	xhr.send(form_data);
// }

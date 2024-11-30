function load_error_page_items():void {
	const buttons = [
		{
			open_button: ".main-settings",
			exit_button: ".exit-settings",
			modal_panel: ".settings"
		},
		{
			open_button: ".file-upload",
			exit_button: ".exit-upload",
			modal_panel: ".upload-panel"
		}
	];
	
	buttons.forEach(button => {
		activate_buttons(button.open_button, button.exit_button, button.modal_panel);
	});
}

function load_elements():void {
	toggle_landing_page(false);
	toggle_checkboxes_actions();
	const buttons = [
		{
			open_button: ".landing-settings",
			exit_button: ".exit-settings",
			modal_panel: ".settings"
		},
		{
			open_button: ".landing-upload",
			exit_button: ".exit-upload",
			modal_panel: ".upload-panel"
		},
		{
			open_button: ".main-settings",
			exit_button: ".exit-settings",
			modal_panel: ".settings"
		},
		{
			open_button: ".file-upload",
			exit_button: ".exit-upload",
			modal_panel: ".upload-panel"
		}
	];

	const max_players_in_a_save = 8;
	for(let i = 0; i < max_players_in_a_save; i++) {
		buttons.push({
			open_button: `.view-all-friendships-${i}`,
			exit_button: `.exit-all-friendships-${i}`,
			modal_panel: `.all-friends-${i}`
		});

		buttons.push({
			open_button: `.view-all-quests-${i}`,
			exit_button: `.exit-all-quests-${i}`,
			modal_panel: `.all-quests-${i}`
		});

		buttons.push({
			open_button: `.view-monster-eradication-goals-${i}`,
			exit_button: `.exit-monster-eradication-goals-${i}`,
			modal_panel: `.monster-eradication-goals-${i}`
		});

		buttons.push({
			open_button: `.view-calendar-${i}`,
			exit_button: `.exit-calendar-${i}`,
			modal_panel: `.calendar-${i}`
		});

		buttons.push({
			open_button: `.view-all-animals-${i}`,
			exit_button: `.exit-all-animals-${i}`,
			modal_panel: `.all-animals-${i}`
		});

		buttons.push({
			open_button: `.view-junimo-kart-leaderboard-${i}`,
			exit_button: `.exit-junimo-kart-leaderboard-${i}`,
			modal_panel: `.junimo-kart-leaderboard-${i}`
		});

		buttons.push({
            open_button: `.view-museum-${i}`,
            exit_button: `.exit-museum-${i}`,
            modal_panel: `.museum-${i}`
        });

		buttons.push({
            open_button: `.view-community-center-${i}`,
            exit_button: `.exit-community-center-${i}`,
            modal_panel: `.community-center-${i}`
        });
	}

	buttons.forEach(button => {
		activate_buttons(button.open_button, button.exit_button, button.modal_panel);
	});

	document.getElementById("home-icon")?.addEventListener("click", () => {
		const display = (document.getElementById("landing_page")?.style.display === "none") ? true : false;
		toggle_landing_page(display);
	});

	load_easter_eggs();
	update_tooltips_after_ajax();
}
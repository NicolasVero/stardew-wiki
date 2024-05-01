function toggleVisibilityAndScroll(element, shouldDisplay, shouldDisableScroll) {
    element.style.display = (shouldDisplay) ? 'block' : 'none';
    // document.body.style.overflow = (shouldDisableScroll) ? 'hidden' : 'auto';
}

function activateButtons(show, hide, sections_to_show, unable_scroll) {
    let show_button = document.querySelectorAll(show);
    let hide_button = document.querySelectorAll(hide);
	let sections = document.querySelector(sections_to_show);

    show_button.forEach(function(button) {
        button.addEventListener('click', function() {
            toggleVisibilityAndScroll(sections, true, unable_scroll);
        });
    });

    hide_button.forEach(function(button) {
        button.addEventListener('click', function() {
            toggleVisibilityAndScroll(sections, false, unable_scroll);
        });
    });
}

activateButtons('.view-all-friendships', '.exit-all-friendships', '.all-friends', true);
activateButtons('.view-all-quests', '.exit-all-quests', '.all-quests', true);
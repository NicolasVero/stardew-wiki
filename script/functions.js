// Panneaux d'infos
let currentSection = null;

function toggleVisibilityAndScroll(element, shouldDisplay, shouldDisableScroll) {
    element.style.display = (shouldDisplay) ? 'block' : 'none';
    document.body.style.overflow = (shouldDisableScroll) ? 'hidden' : 'auto';
}

function activateButtons(show, hide, sections_to_show, unable_scroll) {
    let show_button = document.querySelectorAll(show);
    let hide_button = document.querySelectorAll(hide);
    let sections = document.querySelector(sections_to_show);

    show_button.forEach(function(button) {
        button.addEventListener('click', function() {
            if (currentSection !== null) {
                toggleVisibilityAndScroll(currentSection, false, unable_scroll);
            }
            currentSection = sections;
            toggleVisibilityAndScroll(sections, true, unable_scroll);
        });
    });

    hide_button.forEach(function(button) {
        button.addEventListener('click', function() {
            toggleVisibilityAndScroll(sections, false, unable_scroll);
            currentSection = null;
        });
    });
}

activateButtons('.view-all-friendships', '.exit-all-friendships', '.all-friends', false);
activateButtons('.view-all-quests', '.exit-all-quests', '.all-quests', false);
activateButtons('.upload-file', '.exit-upload', '.upload-panel', false);



// Tooltips
const tooltips = document.querySelectorAll('.tooltip');

tooltips.forEach(tooltip => {
    const rect = tooltip.getBoundingClientRect();
    
    if (rect.left > window.innerWidth / 2)
        tooltip.querySelector('span').classList.add('left');
    else
        tooltip.querySelector('span').classList.add('right');
});

// Style input type file
document.getElementById('save-upload').addEventListener('change', changeLabelName);
function changeLabelName(event) {
    const newFilename = event.target.files[0].name.substring(0, 12);
    document.getElementById('newFilename').innerHTML = newFilename;
}

// Mode no spoil
document.getElementById('no-spoil-mode').addEventListener('change', noSpoilMode);
function noSpoilMode(event) {
    const checkbox = event.target;
    const label = document.getElementById("no-spoil-label");
    
    label.textContent = (checkbox.checked) ? "Click here to deactivate the no spoil mode." : "Click here to activate the no spoil mode.";
    var elements = document.getElementsByClassName("not-found");
    for(let i = 0; i < elements.length; i++) {
        const parentElement = elements[i].parentElement;
        if(parentElement && !elements[i].classList.contains("not-hide"))
            parentElement.style.display = (parentElement.style.display === "none") ?  "block" : "none";
    }
}
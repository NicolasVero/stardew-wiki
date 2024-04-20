<?php

function get_site_root():string {
    return 'http://localhost/travail/stardew_wiki/';
}

include 'components/header.php';

require_once 'functions.php';
require_once 'includes/get_xml_data.php';



$player = $players_data[0];

// log_($player);
//! a enlever 
if(false) {

    foreach($player['levels'] as $key => $level) {
        
        $image_source = './medias/images/icons/' . explode('_', $key)[0] . '.png';
        ?>
        <img src="<?= $image_source; ?>" alt="<?= $key; ?>"></img> 
        <p><?= $level; ?></p>
        <?php
    }
}


include 'components/footer.php';
<?php

require_once 'functions.php';
require_once 'includes/get_xml_data.php';

include 'components/header.php';


$player = $players_data[0];

// log_($player);

foreach($player['levels'] as $key => $level) {

    $image_source = './medias/images/icons' . explode('_', $key)[0] . '.png';
    ?>
        <img src="<?= $image_source; ?>" alt="<?= $key; ?>"></img> 
        <p><?= $level; ?></p>
    <?php
}


include 'components/footer.php';
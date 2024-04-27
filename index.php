<?php

function get_site_root():string {
    return 'http://localhost/travail/stardew_wiki/';
}

include 'components/header.php';

require_once 'functions.php';
require_once 'includes/get_xml_data.php';



$player = $players_data[0];
echo display_page($player);
// log_($player);

include 'components/footer.php';
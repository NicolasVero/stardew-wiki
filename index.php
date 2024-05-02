<?php

require_once 'functions.php';

include 'components/header.php';

require_once 'includes/get_xml_data.php';



// log_($players);

$player_data = $players_data[0];
echo display_page($player_data, $players);
// log_($player);

include 'components/footer.php';
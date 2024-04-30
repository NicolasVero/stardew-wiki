<?php


require_once 'functions.php';

include 'components/header.php';

require_once 'includes/get_xml_data.php';



$player = $players_data[0];
echo display_page($player);
log_($player);

include 'components/footer.php';
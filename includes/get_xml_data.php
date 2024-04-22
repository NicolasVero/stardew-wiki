<?php

// $data = simplexml_load_file('./data/saves/gameInfos_better.xml');
$data = simplexml_load_file('./data/saves/Daffodils_372154186');

$general_data = get_general_datas($data);
$players_data = get_all_players_datas($data);

log_($players_data);
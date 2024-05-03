<?php

// $data = simplexml_load_file('./data/saves/gameInfos_better');
$data = simplexml_load_file('./data/saves/sevran2');

// $general_data = get_general_datas($data);
$players_data = get_all_players_datas($data);
$players = get_all_players($data);

// log_($players_data);

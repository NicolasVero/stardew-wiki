<?php

// $data = simplexml_load_file('./data/saves/gameInfos_better');
$data = simplexml_load_file('./data/saves/nico');
// $data = simplexml_load_file('./data/saves/romain-1.5.6');

// $general_data = get_general_datas($data);
$players_data = get_all_players_datas($data);
$players = get_all_players($data);


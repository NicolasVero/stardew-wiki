<?php

//     deepestMineLevel	:	30
//     millisecondsPlayed	:	23437552

//     -- (mineralsFound) ??
//     -- (fishCaught) ??
//     -- (archaeologyFound) ??
//     -- (experiencePoints) ??
//     --(achievements) ??


$data = simplexml_load_file('./data/saves/Daffodils_372154186');

$general_data = get_general_datas($data);
$players_data = get_all_players_datas($data);

log_($players_data);

// log_($data);
// log_($general_data);

// log_($players_data[0]);


<?php

// PLAYER : 
//     stats -> specificMonstersKilled
//     favoriteThing
//     farmingLevel	:	1
//     miningLevel	:	3
//     combatLevel	:	2
//     foragingLevel	:	2
//     fishingLevel	:	4
//     luckLevel	:	0
//     maxStamina	:	270
//     maxItems	:	24
//     maxHealth	:	110
//     gender	:	Male
//     friendshipData

//     dayOfMonthForSaveGame OU dayOfMonth	:	4
//     seasonForSaveGame	:	1
//     yearForSaveGame	:	1
//     deepestMineLevel	:	30
//     totalMoneyEarned	:	25468
//     millisecondsPlayed	:	23437552
//     money	:	6445
//     -- (mineralsFound) ??
//     -- (fishCaught) ??
//     -- (archaeologyFound) ??
//     quetes en cours (questLog)
//     -- (experiencePoints) ??
//     --(achievements) ??
//     farmName


$data = simplexml_load_file('./data/Daffodils_372154186');

$players = get_all_players($data);

echo "<pre>".print_r($players, true)."</pre>";
// echo "<pre>".print_r($data->farmhands->Farmer->name, true)."</pre>";


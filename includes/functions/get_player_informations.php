<?php 

function get_current_player_id():int {
    return $GLOBALS["player_id"];
}

function get_data(string $data_key, int $player_id = null):array {
    $player_id = $player_id ?? get_current_player_id();
    return $GLOBALS["all_players_data"][$player_id][$data_key] ?? [];
}

function get_friendships_data(int $player_id = null):array {
    return get_data("friendship", $player_id);
}

function get_unlockables_data(int $player_id = null):array {
    return get_data("unlockables", $player_id);
}

function get_books_data(int $player_id = null):array {
    return get_data("books", $player_id);
}

function get_fish_data(int $player_id = null):array {
    return get_data("fish_caught", $player_id);
}

function get_minerals_data(int $player_id = null):array {
    return get_data("minerals_found", $player_id);
}

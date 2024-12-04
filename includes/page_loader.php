<?php

if(is_on_localhost() && isset($_GET["dev"])) {
    $file = (does_save_exists($_GET["dev"])) ? $_GET["dev"] : "default";
    load_save(get_saves_folder() . "/$file", false);
} else {
    echo display_landing_page();
}
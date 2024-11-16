<?php
require_once "functions.php";
include "components/header.php";

if(isset($_GET["dev"])) {
    $file = ($_GET["dev"] == "") ? "default" : $_GET["dev"];
    load_save(get_site_root() . "data/saves/$file", false);
} else {
    echo display_landing_page();
}

include "components/footer.php";
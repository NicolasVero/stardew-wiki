<?php 

function log_($element) {
    echo "<pre>" . print_r($element, true) . "</pre>";
} 

function get_images_folder_root():string {
    return get_site_root() . "medias/images/";
}

function get_site_root():string {
    return 'http://localhost/travail/stardew_wiki/';
}
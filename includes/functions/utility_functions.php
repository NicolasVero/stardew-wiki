<?php

function log_($element) {
    echo "<pre>" . print_r($element, true) . "</pre>";
} 

function get_images_folder():string {
    return get_site_root() . "medias/images/";
}

function get_json_folder():string {
    return get_site_root() . "data/json/";
}

function get_site_root():string {
    return 'http://localhost/travail/stardew_wiki/';
}

function formate_text_for_file(string $string):string {

    $search =  [' ', '\'', '(', ')'];
    $replace = ['_', ''  , '' , '' ];

    $string = str_replace($search, $replace, $string);

    $string = strtolower($string);

    if(substr($string, -1) === '_') {
        $string = substr($string, 0, -1);
    }

    return $string;
}

function format_original_data_string(string $data):string {
    return str_replace('(O)', '', $data);
}
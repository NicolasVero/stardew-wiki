<?php

function log_(mixed $element, string $title = '') {
    if($title != '') echo "<h2>$title</h2>";
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

    $search =  [' ', '\'', '(', ')', ',', '.', ':'];
    $replace = ['_', ''  , '' , '', '', '', ''    ];

    $string = str_replace($search, $replace, $string);

    $string = strtolower($string);

    if(substr($string, -1) === '_') {
        $string = substr($string, 0, -1);
    }

    return $string;
}

function formate_original_data_string(string $data):string {
    return str_replace('(O)', '', $data);
}

function has_element(string $element, object $data):int {
    return (in_array($element, (array) $data->mailReceived->string)) ? 1 : 0;
}
/* ### Get unlockables < 1.6 ###
function has_element(object $element):int {
    return !empty((array) $element);
}
*/

function get_custom_id(string $item):int {
    $custom_ids = json_decode(file_get_contents(get_json_folder() . 'custom_ids.json'), true);
    return array_search($item, $custom_ids);
}
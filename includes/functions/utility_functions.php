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

function formate_usernames(string $username):string {
	$regex = array(
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
		'ç' => 'c',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
		'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'ñ' => 'n',
		'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
		'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
		'ý' => 'y', 'ÿ' => 'y',
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
		'Ç' => 'C',
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
		'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
		'Ñ' => 'N',
		'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
		'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
		'Ý' => 'Y'
	);
	return strtr($username, $regex);
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
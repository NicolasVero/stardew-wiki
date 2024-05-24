<?php

function log_(mixed $element, string $title = ''):void {
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

function formate_number(int $number, string $lang = 'en'):string {
	if($lang == 'fr') 
		return number_format($number, 0, ',', ' ');

	return number_format($number);
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

function does_host_has_element(string $element):int {
	return ($GLOBALS['host_data']['has_element'][$element]['is_found']);
}

function has_element(string $element, object $data):int {
    return (in_array($element, (array) $data->mailReceived->string)) ? 1 : 0;
}

function has_element_ov(object $element):int {
    return !empty((array) $element);
}

function get_custom_id(string $item):int {
    $custom_ids = json_decode(file_get_contents(get_json_folder() . 'custom_ids.json'), true);
    return array_search($item, $custom_ids);
}


function get_game_version_score(string $version):int {
	$version_numbers = explode('.', $version);

	while(count($version_numbers) < 3)
		$version_numbers[] = 0;

	$version_numbers = array_reverse($version_numbers);
	$score = 0;

	for($i = 0; $i < count($version_numbers); $i++)
		$score += $version_numbers[$i] * pow(1000, $i); 

	return (int) $score;
}


function in_bytes_conversion(string $size):int {

    $unit_to_power = array('o'  => 0, 'Ko' => 1, 'Mo' => 2, 'Go' => 3);

    preg_match('/(\d+)([a-zA-Z]+)/', $size, $matches);
    
    $value = (int) $matches[1];
    $unite = $matches[2];
    
    return $value * pow(1024, $unit_to_power[$unite]);
}


function array_keys_exists(array $keys, array $array):bool {
    return count(array_diff_key(array_flip($keys), $array)) === 0;
}


function sanitize_json_with_version(string $json_name):array {

	$original_json = json_decode(file_get_contents(get_json_folder() . "$json_name.json"), true);
	$sanitize_json = array();

	foreach($original_json as $key => $json_version) 
		$sanitize_json += $json_version;
	
	return $sanitize_json;
}

function load_all_items():void {
	$GLOBALS['all_items'] = json_decode(file_get_contents(get_json_folder() . "all_items.json"), true);
}

function get_item_id_by_name(string $name):int {
	return array_search($name, $GLOBALS['all_items']);
}

function load_wiki_links():void {
	$GLOBALS['wiki_links'] = json_decode(file_get_contents(get_json_folder() . "wiki_links.json"), true);
}

function get_wiki_link(int $id):string {
	return $GLOBALS['wiki_links'][$id];
}
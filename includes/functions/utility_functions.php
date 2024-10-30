<?php
// require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
// require __DIR__ . '/../PHPMailer/src/SMTP.php';

// use PHPMailer\PHPMailer\PHPMailer;

function log_(mixed $element, string $title = ''):void {
    if($title != '') echo "<h2>$title</h2>";
    echo "<pre>" . print_r($element, true) . "</pre>";
} 

function get_images_folder(bool $is_external = true):string {
	return ($is_external) ? get_github_medias_url() : get_site_root() . 'medias/images/';
}

function get_github_medias_url():string {
	return 'https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/medias/images/';
}

function get_json_folder():string {
    return get_site_root() . "data/json/";
}

function get_site_root():string {
	if($_SERVER['HTTP_HOST'] == "localhost")
    	return 'http://localhost/travail/stardew_dashboard/';
	
	return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://stardew-dashboard.42web.io/' : 'http://stardew-dashboard.42web.io/';
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

function send_feedback_mail(array $user_details):bool {
	require_once 'load_environment.php';
	extract($user_details);
	
	$date_time = new DateTime("now", new DateTimeZone("Europe/Paris"));
	$date = $date_time->format("d/m/Y");
	$time = $date_time->format("H:i");

	$mail = new PHPMailer(true);

	$mail->isSMTP();
	$mail->Host = $_ENV['SMTP_HOST'];
	$mail->SMTPAuth = true;
	$mail->Username = $_ENV['SMTP_USERNAME'];
	$mail->Password = $_ENV['SMTP_PASSWORD'];
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;
	
	$mail->setFrom('stardewvalley.dashboard@gmail.com', 'Feedback Notification');
	$mail->addAddress('stardewvalley.dashboard@gmail.com');
	
	$mail->isHTML(false);
	$mail->Subject = "A new feedback just came in: $feedback_type";
	$mail->Body = "From: $email_adress - $date at $time" . PHP_EOL . "$message";
	
	return $mail->send();
}

function does_host_has_element(string $element):int {
	return ($GLOBALS['host_player_data']['has_element'][$element]['is_found']);
}

function has_element(string $element, object $data):int {
    return (in_array($element, (array) $data->mailReceived->string)) ? 1 : 0;
}

function has_element_ov(object $element):int {
    return !empty((array) $element);
}

function get_custom_id(string $item):int {
	$custom_ids = decode('custom_ids');
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


function in_bytes_conversion(string $size, string $use = 'local'):int {

    $unit_to_power = ($use == 'local') ? 
		array('o'  => 0, 'Ko' => 1, 'Mo' => 2, 'Go' => 3)
		:
		array('K' => 1, 'M' => 2, 'G' => 3);

    preg_match('/(\d+)([a-zA-Z]+)/', $size, $matches);
    
    $value = (int) $matches[1];
    $unite = $matches[2];
    
    return $value * pow(1024, $unit_to_power[$unite]);
}


function array_keys_exists(array $keys, array $array):bool {
    return count(array_diff_key(array_flip($keys), $array)) === 0;
}

function sanitize_json_with_version(string $json_name, bool $version_controler = false):array {

	$original_json = decode($json_name);
	$game_version_score = (isset($GLOBALS['game_version_score'])) ? $GLOBALS['game_version_score'] : "";

	$sanitize_json = array();

	foreach($original_json as $key => $json_version)
		if($game_version_score > get_game_version_score($key) || !$version_controler)
			$sanitize_json += $json_version;
	
	return $sanitize_json;
}

function find_reference_in_json(mixed $id, string $file):mixed {
    $json_file = sanitize_json_with_version($file);

    return isset($json_file[$id]) ? $json_file[$id] : null;
}

function load_all_items():void {
	$GLOBALS['all_items'] = decode('all_items');
}

function get_item_id_by_name(string $name):int {
	return array_search($name, $GLOBALS['all_items']);
}

function load_wiki_links():void {
	$GLOBALS['wiki_links'] = decode('wiki_links');
}

function get_wiki_link(int $id):string {
	return $GLOBALS['wiki_links'][$id];
}

function decode(string $filename): array {
    $url = get_json_folder() . "$filename.json";
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response, true);
}

function get_formatted_date(bool $display_date = true):mixed {

	$data = $GLOBALS['untreated_player_data'];

    $day    = $data->dayOfMonthForSaveGame;
    $season = array('spring', 'summer', 'fall', 'winter')[$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    if($display_date)
        return "Day $day of $season, Year $year";

    return array(
        'day' => $day,
        'season' => $season,
        'year' => $year
    );
}

function is_this_the_same_day(string $date):bool {
    extract(get_formatted_date(false));
    return $date == "$day/$season";
}

function get_game_duration(int $duration):string {

    $totalSeconds = intdiv($duration, 1000);
    $seconds      = $totalSeconds % 60;
    $totalMinutes = intdiv($totalSeconds, 60);
    $minutes      = $totalMinutes % 60;
    $hours        = intdiv($totalMinutes, 60);

    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

function get_number_of_days_ingame():int {
	$data = $GLOBALS['untreated_player_data'];
    return ((($data->dayOfMonthForSaveGame - 1)) + ($data->seasonForSaveGame * 28) + (($data->yearForSaveGame - 1) * 112));
}

function get_php_max_upload_size():string {
	$post_max_size_bytes = in_bytes_conversion(ini_get('post_max_size'), 'server');
	return json_encode([
        'post_max_size' => $post_max_size_bytes
    ]);
}

function is_a_mobile_device():bool {
	return (
		stristr($_SERVER['HTTP_USER_AGENT'], "Android") ||
		strpos($_SERVER['HTTP_USER_AGENT'], "iPod") != false ||
		strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") != false 
	);
}

if(isset($_GET['action']) && $_GET['action'] == 'get_max_upload_size')
    echo get_php_max_upload_size();
<?php 

function get_weather_tooltip(string $weather): string {
	return [
		"sun"        => "It's going to be clear and sunny all day",
		"rain"       => "It's going to rain all day tomorrow",
		"green_rain" => "Um... There appears to be some kind of... anomalous reading... I... don't know what this means...",
		"wind"       => "It's going to be cloudy, with gusts of wind throughout the day",
		"storm"      => "Looks like a storm is approaching. Thunder and lightning is expected",
		"snow"       => "Expect a few inches of snow tomorrow"
	][$weather] ?? "";
}
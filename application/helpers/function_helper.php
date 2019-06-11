<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

function pre($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

function e($data) {
	echo $data;
}

function px2pt($varPx) {
	return $varPx * 25.4 / 72;
}

function addX($array, $index) {
	$x = 0;
	for ($i = 0; $i <= $index; $i++) {
		$x += $array[$i];
	}
	return $x;
}

function clearsinonim($sentence) {
	$words = explode(',', $sentence);
	for ($i = 0; $i < sizeof($words); $i++) {
		$words[$i] = strtolower(trim($words[$i]));
	}
	return implode(", ", $words);
}

?>
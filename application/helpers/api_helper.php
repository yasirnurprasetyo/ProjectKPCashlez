<?php

use \Firebase\JWT\JWT;

// API Request
function apiRequest($endPoint, $method, $body = null)
{
	$ci = &get_instance();
	$sessiontoken = ifNull($ci->session->userdata("session_token"), "xx");
	$accesstoken = ifNull($ci->session->userdata("access_token"), "xx");
	$apiUrl = $ci->config->item("api_url");
	$ci->curl->create($apiUrl . $endPoint);
	$options = array(
		CURLOPT_FAILONERROR => false,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_POSTFIELDS => $body,
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"Accept: application/json",
			"Authorization: Bearer $sessiontoken",
			"token: $accesstoken"
		),
	);
	$ci->curl->options($options);
	$data = json_decode($ci->curl->execute());
	$info = $ci->curl->info;
	return array(
		"kode" => $info["http_code"],
		"body" => $data
	);
}

// API Login
function apiLogin($endPoint, $method, $body = null)
{
	$ci = &get_instance();
	$apiUrl = $ci->config->item("api_url");
	$ci->curl->create($apiUrl . $endPoint);
	$options = array(
		CURLOPT_FAILONERROR => true,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_POSTFIELDS => $body,
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"Accept: application/json"
		),
	);
	$ci->curl->options($options);
	$data = json_decode($ci->curl->execute());
	$info = $ci->curl->info;
	return array(
		"kode" => $info["http_code"],
		"body" => $data
	);
}

function ifNull($data, $return)
{
	if ($data == null) {
		$data = $return;
	}
	return $data;
}

// Cek Expired Access Token
function cekExpiredAccessToken()
{
	$CI = &get_instance();
	try {
		$tokenLama = ifNull($CI->session->userdata("access_token"), "xx");
		$decoded = JWT::decode($tokenLama, 'TheSecretKey', array('HS256'));
		// $getExp = $decoded->exp;
		// date_default_timezone_set("UTC");
		// $wkt = strtotime("now");
		// d($getExp);
		// d($wkt);
	} catch (Exception $e) {
		refresh();
		return true;
	}
	return true;
}

// Access Session Refresh
function refresh()
{
	$CI = &get_instance();
	$params = array("username" => "D001IP");
	$data = json_encode($params);
	$results = apiRequest("session/refresh", "POST", $data);
	$listData = $results["body"][0];
	$kode = $results["kode"];
	if ($kode == 200) {
		$refreshToken = array(
			"access_token" => $listData->access_token,
		);
		$CI->session->set_userdata($refreshToken);
		return true;
	}
	return true;
}

// Cek Expired Session Token
function cekExpiredSessionToken()
{
	$CI = &get_instance();
	try {
		$sessionToken = ifNull($CI->session->userdata("session_token"), "xx");
		$decoded = JWT::decode($sessionToken, 'TheSecretKey', array('HS256'));
	} catch (Exception $e) {
		$CI->session->sess_destroy();
		redirect('login');
		return true;
	}
	return true;
}

//Fungsi Cek User sudah login
function checkOnLogin()
{
	$CI = &get_instance();
	if (!$CI->session->userdata("is_logged_in")) {
		redirect("login");
	}
}

// Formatter Rupiah
function formatRupiah($angka)
{
	return "Rp." . number_format($angka, 0, ",", ".");
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		cekExpiredSessionToken();
		cekExpiredAccessToken();
		checkOnLogin();
	}

	public function index()
	{
		$date = date('Y-m');
		$params = array(
			"group_code" => $this->session->userdata("group_code"),
			"sales_period" => $date
		);
		$data = json_encode($params);
		$results = apiRequest("sales-group/monthly", "GET", $data, TRUE);
		$listData = $results["body"];
		$data = array(
			"page" => 'content/dashboard',
			"datas" => $listData,
		);
		$this->load->view('layout/dashboard', $data);
	}
}

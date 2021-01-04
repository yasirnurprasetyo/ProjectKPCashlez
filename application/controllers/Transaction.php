<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE);

class Transaction extends CI_Controller
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
		$results = apiRequest("sales-group/monthly-details", "GET", $data);
		$listData = $results["body"]->sales_performance;
		$sum = array();
		foreach ($listData as $f)
			foreach ($f->performance as $d) {
				$sum[$f->merchant_name]['name'] = $f->merchant_name;
				$sum[$f->merchant_name]['sales_volume'] += $d->sales_volume;
				$sum[$f->merchant_name]['sales_numbers'] += $d->sales_numbers;
				$sum[$f->merchant_name]['sales_incentive'] += $d->sales_incentive;
				$sum[$f->merchant_name]['data'][$d->trx_method]['payment_method'] = $d->trx_method;
				$sum[$f->merchant_name]['data'][$d->trx_method]['amount'] = $d->sales_volume;
			}
		$data = array(
			"page" => 'content/transaction',
			"datas" => $listData,
			"hasil" => $sum
		);
		$this->load->view('layout/dashboard', $data);
	}

	public function getDetail()
	{
		$date = date('Y-m');
		$dat = $this->input->post("name", true);
		$dats = json_decode($dat);
		$merchant_id = array("name" => $dats);
		$params = array(
			"group_code" => $this->session->userdata("group_code"),
			"sales_period" => $date
		);
		$data = json_encode($params);
		$results = apiRequest("sales-group/monthly-details", "GET", $data);
		$listData = $results["body"]->sales_performance;
		$ddetails = array();
		foreach ($listData as $f)
			foreach ($f->performance as $d) {
				$ddetails[$f->merchant_name == $merchant_id['name']]['data'][$d->trx_method]['payment_method'] = $d->trx_method;
				$ddetails[$f->merchant_name == $merchant_id['name']]['data'][$d->trx_method]['amount'] = $d->sales_volume;
			}
		echo json_encode($ddetails[1]);
	}
}

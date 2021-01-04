<?php
class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// checkLogin();
	}

	public function index()
	{
		$this->load->view('layout/login');
	}

	public function prosesLogin()
	{
		$data = $this->input->post();
		$body = json_encode($data);
		$result = apiLogin("session/login", "POST", $body);
		$kode = $result["kode"];
		if ($kode == 200) {
			$dataUser = $result["body"][0];
			$dataSession = array(
				"session_token" => $dataUser->session_token,
				"access_token" => $dataUser->access_token,
				"is_logged_in" => true
			);
			$this->session->set_userdata($dataSession);
			echo "1";
		} else {
			echo "Username dan  Password Tidak ditemukan!";
		}
	}

	public function salesLogin(){
		$data = $this->input->post();
		$body = json_encode($data);
		$result = apiRequest("sales-group/login", "GET", $body);
		$kode = $result["kode"];
		if ($kode == 200) {
			$dataUser = $result["body"];
			$dataSession = array(
				"group_code" => $dataUser->group_code,
				"group_name" => $dataUser->group_name,
				"group_type" => $dataUser->group_type,
			);
			$this->session->set_userdata($dataSession);
			echo "200";
		} else {
			echo "Username dan  Password Tidak ditemukan!";
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}

<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index() {
		$this->load->view('beranda');
	// 	redirect(base_url() . 'administrator/video/daftar/', 'refresh');
	// }

}
}
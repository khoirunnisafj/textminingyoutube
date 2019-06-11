<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Aturan extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('aturan_model', '', TRUE);
	}

	public function index() {
		$this->ubah();
	}

	public function ubah() {
		$viewdata = array();
		$viewdata['records'] = $this->aturan_model->daftar();

		$simpan = $this->input->post('simpan');
		if ($simpan) {
			foreach ($viewdata['records']->result() as $records) {
				$recordsdata['nilaiaturan'] = $this->input->post('aturan-' . $records->idaturan);
				$this->aturan_model->ubah($records->idaturan, $recordsdata);
				$this->session->set_userdata('aturan-' . $records->idaturan, $this->input->post('aturan-' . $records->idaturan));
			}

			$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
			$url = base_url() . 'administrator/aturan/ubah';
		}

		if (isset($url)) {
			redirect($url);
		}

		$this->load->view('administrator/aturan_tambah', $viewdata);
	}

}

<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Maskapai extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('maskapai_model', '', TRUE);
	}

	public function index() {
		$this->daftar();
	}

	public function daftar($nomor = 0) {
		$viewdata = array();
		$viewdata['nomor'] = $nomor;

		$katakunci = $this->input->post('katakunci');
		if (isset($katakunci)) {
			$url = base_url() . 'administrator/maskapai/daftar?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/maskapai/daftar/';
		$config['total_rows'] = $this->maskapai_model->hitung($katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['maskapai'] = $this->maskapai_model->daftar($katakunci, $config['per_page'], $nomor);
		$this->load->view('administrator/maskapai_daftar', $viewdata);
	}

	public function lihat($id) {
		$viewdata = array();

		$viewdata['record'] = $this->maskapai_model->lihat($id)->row();
		$this->load->view('administrator/maskapai_lihat', $viewdata);
	}

	public function tambah() {
		$viewdata = array();

		$id = 0;
		$simpan = $this->input->post('simpan');
		if ($simpan) {
			$recorddata['namamaskapai'] = $this->input->post('namamaskapai');
			$id = $this->maskapai_model->tambah($recorddata);
			$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
			$url = base_url() . 'administrator/video/ubah/' . $id;
		}

		if (isset($url)) {
			redirect($url);
		}

		$this->load->view('administrator/maskapai_tambah', $viewdata);
	}

	public function ubah($id = '') {
		$viewdata = array();

		if ($id != '') {
			$viewdata['record'] = $this->maskapai_model->lihat($id)->row();
			$simpan = $this->input->post('simpan');
			if ($simpan) {
				$recorddata['namamaskapai'] = $this->input->post('namamaskapai');
				$this->maskapai_model->ubah($id, $recorddata);
				$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
				// $url = base_url() . 'administrator/maskapai/daftar';
				$url = base_url() . 'administrator/video/ubah/' . $id;
			}

			if (isset($url)) {
				redirect($url);
			}

			$this->load->view('administrator/maskapai_tambah', $viewdata);
		}
	}

	public function hapus($id) {
		$this->maskapai_model->hapus($id);
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/maskapai/daftar';
		redirect($url);
	}

}

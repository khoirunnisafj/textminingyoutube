<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Kategori extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('kategori_model', '', TRUE);
		$this->load->model('subkategori_model', '', TRUE);
	}

	public function index() {
		$this->daftar();
	}

	public function daftar($nomor = 0) {
		$viewdata = array();
		$viewdata['nomor'] = $nomor;

		$katakunci = $this->input->post('katakunci');
		if (isset($katakunci)) {
			$url = base_url() . 'administrator/kategori/daftar?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/kategori/daftar/';
		$config['total_rows'] = $this->kategori_model->hitung($katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['kategori'] = $this->kategori_model->daftar($katakunci, $config['per_page'], $nomor);
		$this->load->view('administrator/kategori_daftar', $viewdata);
	}

	public function lihat($id) {
		$viewdata = array();

		$viewdata['record'] = $this->kategori_model->lihat($id)->row();
		$viewdata['records'] = $this->subkategori_model->daftaritem($id);
		$this->load->view('administrator/kategori_lihat', $viewdata);
	}

	public function tambah() {
		$viewdata = array();

		$id = 0;
		$simpan = $this->input->post('simpan');
		if ($simpan) {
			$recorddata['namakategori'] = $this->input->post('namakategori');
			$this->kategori_model->tambah($recorddata);
			$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
			$url = base_url() . 'administrator/kategori/daftar';
		}

		if (isset($url)) {
			redirect($url);
		}

		$this->load->view('administrator/kategori_tambah', $viewdata);
	}

	public function ubah($id = '') {
		$viewdata = array();

		if ($id != '') {
			$viewdata['record'] = $this->kategori_model->lihat($id)->row();
			$simpan = $this->input->post('simpan');
			if ($simpan) {
				$recorddata['namakategori'] = $this->input->post('namakategori');
				$this->kategori_model->ubah($id, $recorddata);
				$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
				$url = base_url() . 'administrator/kategori/daftar';
			}

			if (isset($url)) {
				redirect($url);
			}

			$this->load->view('administrator/kategori_tambah', $viewdata);
		}
	}

	public function hapus($id) {
		$this->kategori_model->hapus($id);
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/kategori/daftar';
		redirect($url);
	}

	public function lihatitem($idkategori, $idsubkategori) {
		if ($idkategori == 0) {
			redirect(base_url() . 'administrator/kategori/daftar');
		}
		$viewdata = array();
		$viewdata['idkategori'] = $idkategori;
		$viewdata['option'] = $this->kategori_model->larik();

		if ($idsubkategori != '') {
			$viewdata['record'] = $this->subkategori_model->lihat($idsubkategori)->row();
			$this->load->view('administrator/kategori_lihatitem', $viewdata);
		}
	}

	public function tambahitem($idkategori) {
		if ($idkategori == 0) {
			redirect(base_url() . 'administrator/kategori/daftar');
		}
		$viewdata = array();
		$viewdata['idkategori'] = $idkategori;
		$viewdata['option'] = $this->kategori_model->larik();

		$simpan = $this->input->post('simpan');
		if ($simpan) {
			$recorddata['idkategori'] = $idkategori;
			$recorddata['namasubkategori'] = $this->input->post('namasubkategori');
			$recorddata['sinonimsubkategori'] = clearsinonim($this->input->post('sinonimsubkategori'));
			$recorddata['antonimsubkategori'] = clearsinonim($this->input->post('antonimsubkategori'));
			$this->subkategori_model->tambah($recorddata);
			$this->session->set_flashdata('pesan', 'Data berhasil disimpan. ');
			$url = base_url() . 'administrator/kategori/lihat/' . $idkategori;
		}

		if (isset($url)) {
			redirect($url);
		}

		$this->load->view('administrator/kategori_tambahitem', $viewdata);
	}

	public function ubahitem($idkategori, $idsubkategori) {
		if ($idkategori == 0) {
			redirect(base_url() . 'administrator/kategori/daftar');
		}
		$viewdata = array();
		$viewdata['idkategori'] = $idkategori;
		$viewdata['option'] = $this->kategori_model->larik();

		if ($idsubkategori != '') {
			$viewdata['record'] = $this->subkategori_model->lihat($idsubkategori)->row();
			$simpan = $this->input->post('simpan');
			if ($simpan) {
				$recorddata['idkategori'] = $idkategori;
				$recorddata['namasubkategori'] = $this->input->post('namasubkategori');
				$recorddata['sinonimsubkategori'] = clearsinonim($this->input->post('sinonimsubkategori'));
				$recorddata['antonimsubkategori'] = clearsinonim($this->input->post('antonimsubkategori'));
				$this->subkategori_model->ubah($idsubkategori, $recorddata);
				$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
				$url = base_url() . 'administrator/kategori/lihat/' . $idkategori;
			}

			if (isset($url)) {
				redirect($url);
			}

			$this->load->view('administrator/kategori_tambahitem', $viewdata);
		}
	}

	public function hapusitem($idkategori, $idsubkategori) {
		if ($idkategori == 0) {
			redirect(base_url() . 'administrator/kategori/daftar');
		}

		$this->subkategori_model->hapus($idsubkategori);
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/kategori/lihat/' . $idkategori;
		redirect($url);
	}

	public function searchnym($kata) {
		$kata = trim(urldecode($kata));
		if ($kata != '') {
			$url = "http://kateglo.com/api.php?format=json&phrase=" . $kata;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			// $data = file_get_contents('nym.txt');
			// echo $data;

			if (curl_errno($ch)) {
				print "Error: " . curl_error($ch);
			} else {
				$transaction = json_decode($data, TRUE);
				curl_close($ch);
			}

			$kateglo = $transaction['kateglo'];

			$relation = $kateglo['relation'];

			$ss = $relation['s'];
			$as = $relation['a'];

			$sinonim = array();
			array_push($sinonim, strtolower($kata));
			foreach ($ss as $s) {
				if (isset($s['lex_class'])) {
					if ($s['lex_class'] == 'adj') {
						if (isset($s['related_phrase'])) {
							array_push($sinonim, $s['related_phrase']);
						}
					}
				}
			}

			$antonim = array();
			foreach ($as as $a) {
				if (isset($a['lex_class'])) {
					if ($a['lex_class'] == 'adj') {
						if (isset($a['related_phrase'])) {
							array_push($antonim, $a['related_phrase']);
						}
					}
				}
			}

			$sinonim = implode(', ', $sinonim);
			$antonim = implode(', ', $antonim);

			$response = array();

			$response['success'] = 1;
			$response['sinonim'] = $sinonim;
			$response['antonim'] = $antonim;
			echo json_encode($response, TRUE);
		} else {
			$response = array();
			$response['success'] = 0;
			$response['sinonim'] = '';
			$response['antonim'] = '';
			echo json_encode($response, TRUE);
		}
	}

}

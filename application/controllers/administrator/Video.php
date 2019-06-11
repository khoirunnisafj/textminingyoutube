<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Video extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('aturan_model', '', TRUE);
		$this->load->model('maskapai_model', '', TRUE);
		$this->load->model('video_model', '', TRUE);
		$this->load->helper('url');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
	}

	public function index() {
		$this->daftar();
	}

	public function daftar($nomor = 0) {
		$viewdata = array();
		$viewdata['nomor'] = $nomor;

		$katakunci = $this->input->post('katakunci');
		if (isset($katakunci)) {
			$url = base_url() . 'administrator/video/daftar?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/video/daftar/';
		$config['total_rows'] = $this->maskapai_model->hitung($katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['maskapai'] = $this->maskapai_model->daftar($katakunci, $config['per_page'], $nomor);
		$this->load->view('administrator/video_daftar', $viewdata);
	}

	public function lihat($id, $nomor = 0) {
		$viewdata = array();

		$viewdata['video'] = $this->maskapai_model->lihat($id)->row();
		$viewdata['nomor'] = $nomor;

		$katakunci = $this->input->post('katakunci');
		if (isset($katakunci)) {
			$url = base_url() . 'administrator/video/lihat/' . $id . '?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/video/lihat/' . $id . '/';
		$config['total_rows'] = $this->video_model->hitungpermaskapai($id, $katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['videos'] = $this->video_model->daftar($id, $katakunci, $config['per_page'], $nomor);

		$this->load->view('administrator/video_lihat', $viewdata);
	}

	public function tambah() {
		$viewdata = array();

		$id = 0;
		$simpan = $this->input->post('simpan');
		if ($simpan) {
			$recorddata['namamaskapai'] = $this->input->post('namamaskapai');
			$this->maskapai_model->tambah($recorddata);
			$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
			$url = base_url() . 'administrator/maskapai/daftar';
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
				$url = base_url() . 'administrator/video/daftar';
			}

			if (isset($url)) {
				redirect($url);
			}

			$this->load->view('administrator/video_tambah', $viewdata);
		}
	}

	public function hapus($id) {
		$this->video_model->hapuspermaskapai($id);

		$recorddata = array();
		$recorddata['videotoken'] = '';
		$this->maskapai_model->ubah($id, $recorddata);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/video/daftar';
		redirect($url);
	}

	public function lihatitem($idmaskapai, $idvideo) {
		$viewdata = array();
		$viewdata['idmaskapai'] = $idmaskapai;

		if ($idvideo != '') {
			$viewdata['video'] = $this->video_model->lihat($idvideo)->row();
			$this->load->view('administrator/video_lihatitem', $viewdata);
		}
	}

	public function hapusitem($idmaskapai, $idvideo) {
		if ($idmaskapai == 0) {
			redirect(base_url() . 'administrator/video/daftar');
		}

		$this->video_model->hapus($idvideo);
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/video/lihat/' . $idmaskapai;
		redirect($url);
	}

	public function hapussemua() {
		$this->video_model->hapussemua();

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/video/daftar';
		redirect($url);
	}

	public function searchvideo($idmaskapai) {
		$settingsrecords = $this->aturan_model->daftar();
		$setting = array();
		foreach ($settingsrecords->result() as $row) {
			switch ($row->idaturan) {
				case 1: {
						$setting['apikey'] = $row->nilaiaturan;
					}
					break;
				case 2: {
						$setting['maxvideo'] = $row->nilaiaturan;
					}
					break;
				case 3: {
						$setting['maxthread'] = $row->nilaiaturan;
					}
					break;
				case 4: {
						$setting['maxrating'] = $row->nilaiaturan;
					}
					break;
			}
		}


		$maskapai = $this->maskapai_model->lihat($idmaskapai)->row();
		$videotoken = $maskapai->videotoken;
		$videocount = $this->video_model->hitung($idmaskapai);

		$url = "https://www.googleapis.com/youtube/v3/search?part=snippet&relevanceLanguage=id&maxResults=1&q=" . rawurlencode($maskapai->namamaskapai) . "&key=" . $setting['apikey'];
		if ($videotoken != '') {
			$url .= '&pageToken=' . $videotoken;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			print "Error: " . curl_error($ch);
		} else {
			$transaction = json_decode($data, TRUE);
			curl_close($ch);
		}

		if (isset($transaction['error'])) {
			$response = array();
			$response['success'] = 0;
			$response['message'] = $transaction['error']['message'];

			echo json_encode($response, TRUE);
			return;
		}

		$videotoken = '';
		if (isset($transaction['nextPageToken'])) {
			$videotoken = $transaction['nextPageToken'];
		}

		$items = $transaction['items'];
		$videodatas = array();
		foreach ($items as $item) {
			$videodata = array();
			$videodata['idmaskapai'] = $idmaskapai;
			$videodata['videoid'] = '';
			$videodata['judul'] = '';
			$videodata['keterangan'] = '';
			$videodata['status'] = 0;

			if (isset($item['id']['videoId'])) {
				$videodata['videoid'] = $item['id']['videoId'];
			}
			if (isset($item['snippet']['title'])) {
				$videodata['judul'] = $item['snippet']['title'];
			}
			if (isset($item['snippet']['description'])) {
				$videodata['keterangan'] = $item['snippet']['description'];
			}

			if ($this->video_model->cari($videodata['videoid']) == 0 && $videodata['videoid'] != '' && $videocount < $setting['maxvideo']) {
				array_push($videodatas, $videodata);
				$videocount ++;
			}
		}

		if (sizeof($videodatas) > 0) {
			$this->video_model->tambahjamak($videodatas);
		}

		$recorddata = array();
		$recorddata['videotoken'] = $videotoken;
		$this->maskapai_model->ubah($idmaskapai, $recorddata);

		$response = array();
		$response['success'] = 1;
		$response['maxvideo'] = $setting['maxvideo'];
		$response['videocount'] = $this->video_model->hitungpermaskapai($idmaskapai);
		$response['nextpagetoken'] = $videotoken;

		echo json_encode($response, TRUE);
	}

}

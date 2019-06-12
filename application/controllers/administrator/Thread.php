<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Thread extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('aturan_model', '', TRUE);
		$this->load->model('subkategori_model', '', TRUE);
		$this->load->model('maskapai_model', '', TRUE);
		$this->load->model('video_model', '', TRUE);
		$this->load->model('thread_model', '', TRUE);
		$this->load->model('lemma_model', '', TRUE);
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
			$url = base_url() . 'administrator/thread/daftar?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/thread/daftar/';
		$config['total_rows'] = $this->maskapai_model->hitung($katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['maskapai'] = $this->maskapai_model->daftar($katakunci, $config['per_page'], $nomor);
		$this->load->view('administrator/thread_daftar', $viewdata);
	}

	public function lihat($id, $nomor = 0) {
		$viewdata = array();

		$viewdata['thread'] = $this->maskapai_model->lihat($id)->row();
		$viewdata['nomor'] = $nomor;

		$katakunci = $this->input->post('katakunci');
		if (isset($katakunci)) {
			$url = base_url() . 'administrator/thread/lihat/' . $id . '?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/thread/lihat/' . $id . '/';
		$config['total_rows'] = $this->thread_model->hitungpermaskapai($id, $katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['threads'] = $this->thread_model->daftar($id, $katakunci, $config['per_page'], $nomor);

		$this->load->view('administrator/thread_lihat', $viewdata);
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
			$viewdata['dataThread'] = $this->thread_model->lihatByMaskapai($id);
			$simpan = $this->input->post('simpan');
			if ($simpan) {
				$recorddata['namamaskapai'] = $this->input->post('namamaskapai');
				
				$this->maskapai_model->ubah($id, $recorddata);
				$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
				$url = base_url() . 'administrator/thread/daftar';
			}

			if (isset($url)) {
				redirect($url);
			}

			$this->load->view('administrator/thread_tambah', $viewdata);
		}
	}

	public function hapus($id) {
		$this->thread_model->hapuspermaskapai($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/thread/daftar';
		redirect($url);
	}

	public function lihatitem($idmaskapai, $idthread) {
		$viewdata = array();
		$viewdata['idmaskapai'] = $idmaskapai;

		if ($idthread != '') {
			$viewdata['thread'] = $this->thread_model->lihat($idthread)->row();
			$this->load->view('administrator/thread_lihatitem', $viewdata);
		}
	}

	public function hapusitem($idmaskapai, $idthread) {
		if ($idmaskapai == 0) {
			redirect(base_url() . 'administrator/thread/daftar');
		}

		$this->thread_model->hapus($idthread);
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/thread/lihat/' . $idmaskapai;
		redirect($url);
	}

	public function hapussemua() {
		$this->thread_model->hapussemua();

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/thread/daftar';
		redirect($url);
	}

	public function searchthread($idmaskapai) {

		// loading sinonim dan antonim
		$result = $this->subkategori_model->daftarsemua();
		$subkategori = array();
		if ($result->num_rows() == 0) {
			
		} else {
			foreach ($result->result() as $row) {
				$sinonimsubkategori = explode(', ', $row->sinonimsubkategori);
				$antonimsubkategori = explode(', ', $row->antonimsubkategori);
				$subkategori = array_merge($subkategori, $sinonimsubkategori);
				$subkategori = array_merge($subkategori, $antonimsubkategori);
			}
		}
		$subkategori = array_unique($subkategori);
		foreach ($subkategori as $key => $value)
			if (empty($value))
				unset($subkategori[$key]);
		sort($subkategori);

		// threading

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
		$video = $this->video_model->pertama($idmaskapai)->row();
		if (isset($video)) {
			$idvideo = $video->idvideo;
			$videoid = $video->videoid;
			$threadtoken = $video->threadtoken;
			$threadcount = $this->thread_model->hitung($idmaskapai);

			$url = "https://www.googleapis.com/youtube/v3/commentThreads?part=snippet%2Creplies&videoId=" . $videoid . "&maxResults=1&key=" . $setting['apikey'];
			if ($threadtoken != '') {
				$url .= '&pageToken=' . $threadtoken;
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			// $data = file_get_contents('thread.txt');
			// $transaction = json_decode($data, TRUE);

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

			$threadtoken = '';
			if (isset($transaction['nextPageToken'])) {
				$threadtoken = $transaction['nextPageToken'];
			}

			// Komentar

			$items = $transaction['items'];
			$threaddatas = array();
			foreach ($items as $item) {
				$threaddata = array();
				$threaddata['idmaskapai'] = $idmaskapai;
				$threaddata['idvideo'] = $idvideo;
				$threaddata['threadid'] = '';
				$threaddata['commentid'] = '';
				$threaddata['isi'] = '';
				$threaddata['token'] = '';
				$threaddata['filter'] = '';
				$threaddata['status'] = 0;

				if (isset($item['id'])) {
					$threaddata['threadid'] = $item['id'];
				}
				if (isset($item['snippet']['topLevelComment']['snippet']['textOriginal'])) {
					$threaddata['isi'] = $item['snippet']['topLevelComment']['snippet']['textOriginal'];

					// tokenizing

					$isibersih = preg_replace("/[^a-z]/", " ", $threaddata['isi']);
					$isibersih = trim(preg_replace('!\s+!', ' ', strip_tags($isibersih)));

					$katas = explode(' ', $isibersih);

					$token = array();
					foreach ($katas as $kata) {
						if ($this->lemma_model->cari($kata) > 0) {
							array_push($token, $kata);
						}
					}
					$token = array_unique($token);
					$threaddata['token'] = implode(' ', $token);

					// filtering 

					$katas = $token;

					$filter = array();
					foreach ($katas as $kata) {
						if (in_array($kata, $subkategori)) {
							array_push($filter, $kata);
						}
					}
					$filter = array_unique($filter);
					$threaddata['filter'] = implode(' ', $filter);
				}

				if ($this->thread_model->carithread($threaddata['threadid']) == 0 && $threaddata['threadid'] != '' && $threadcount < $setting['maxthread']) {
					array_push($threaddatas, $threaddata);
					$threadcount ++;
				}

				// Balasan

				if (isset($item['replies']['comments'])) {
					$comments = $item['replies']['comments'];
					foreach ($comments as $comment) {
						$threaddata = array();
						$threaddata['idmaskapai'] = $idmaskapai;
						$threaddata['idvideo'] = $idvideo;
						$threaddata['threadid'] = '';
						$threaddata['commentid'] = '';
						$threaddata['isi'] = '';
						$threaddata['token'] = '';
						$threaddata['filter'] = '';
						$threaddata['status'] = 0;


						if (isset($comment['snippet']['parentId'])) {
							$threaddata['threadid'] = $comment['snippet']['parentId'];
						}
						if (isset($comment['id'])) {
							$threaddata['commentid'] = $comment['id'];
						}

						if (isset($comment['snippet']['textOriginal'])) {
							$threaddata['isi'] = $comment['snippet']['textOriginal'];

							// tokenizing

							$isibersih = preg_replace("/[^a-z]/", " ", $threaddata['isi']);
							$isibersih = trim(preg_replace('!\s+!', ' ', strip_tags($isibersih)));

							$katas = explode(' ', $isibersih);

							$token = array();
							foreach ($katas as $kata) {
								if ($this->lemma_model->cari($kata) > 0) {
									array_push($token, $kata);
								}
							}
							$token = array_unique($token);
							$threaddata['token'] = implode(' ', $token);

							// filtering 

							$katas = $token;

							$filter = array();
							foreach ($katas as $kata) {
								if (in_array($kata, $subkategori)) {
									array_push($filter, $kata);
								}
							}
							$filter = array_unique($filter);
							$threaddata['filter'] = implode(' ', $filter);
						}

						if ($this->thread_model->caricomment($threaddata['commentid']) == 0 && $threaddata['commentid'] != '' && $threadcount < $setting['maxthread']) {
							array_push($threaddatas, $threaddata);
							$threadcount ++;
						}
					}
				}
			}

			if (sizeof($threaddatas) > 0) {
				$this->thread_model->tambahjamak($threaddatas);
			}

			$response = array();

			$recorddata = array();
			$recorddata['threadtoken'] = $threadtoken;
			if ($threadtoken == '') {
				$recorddata['status'] = "1";
			}
			$threadcount = $this->thread_model->hitung($idmaskapai);
			if ($threadcount >= $setting['maxthread']) {
				$response['finish'] = 1;
			} else {
				$response['finish'] = 0;
			}
			$this->video_model->ubah($idvideo, $recorddata);

			$response['success'] = 1;
			$response['maxthread'] = $setting['maxthread'];
			$response['threadcount'] = $this->thread_model->hitungpermaskapai($idmaskapai);
			$response['nextpagetoken'] = $threadtoken;
			$response['dataThread'] = $threaddatas;
			echo json_encode($response, TRUE);
		} else {
			$response = array();
			$response['finish'] = 1;
			$response['success'] = 1;
			$response['maxthread'] = $setting['maxthread'];
			$response['threadcount'] = $this->thread_model->hitungpermaskapai($idmaskapai);
			$response['message'] = 'Sudah tidak ada video lagi untuk dipindai';

			echo json_encode($response, TRUE);
		}
	}

}

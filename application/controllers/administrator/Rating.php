<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rating extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('aturan_model', '', TRUE);
		$this->load->model('maskapai_model', '', TRUE);
		$this->load->model('kategori_model', '', TRUE);
		$this->load->model('subkategori_model', '', TRUE);
		$this->load->model('nilaisubkategori_model', '', TRUE);
		$this->load->model('nilaikategori_model', '', TRUE);
		$this->load->model('rating_model', '', TRUE);
	}

	public function index() {
		$this->daftar();
	}

	public function daftar($nomor = 0) {
		$viewdata = array();
		$viewdata['nomor'] = $nomor;

		$katakunci = $this->input->post('katakunci');
		if (isset($katakunci)) {
			$url = base_url() . 'administrator/rating/daftar?katakunci=' . $katakunci;
			redirect($url);
		}
		$katakunci = $this->input->get('katakunci');

		$config['base_url'] = base_url() . 'administrator/rating/daftar/';
		$config['total_rows'] = $this->maskapai_model->hitung($katakunci);
		$config['per_page'] = 50;
		$this->pagination->initialize($config);

		$viewdata['katakunci'] = $katakunci;
		$viewdata['maskapai'] = $this->maskapai_model->daftar($katakunci, $config['per_page'], $nomor);
		$this->load->view('administrator/rating_daftar', $viewdata);
	}

	public function lihat($id) {
		$viewdata = array();

		$viewdata['maskapai'] = $this->maskapai_model->lihat($id)->row();

		$viewdata['subkategoris'] = $this->rating_model->daftarsubkategori($id);
		$viewdata['kategoris'] = $this->rating_model->daftarkategori($id);

		$this->load->view('administrator/rating_lihat', $viewdata);
	}

	public function ubah($id = '') {
		$viewdata = array();

		if ($id != '') {
			$viewdata['maskapai'] = $this->maskapai_model->lihat($id)->row();
			$simpan = $this->input->post('simpan');
			if ($simpan) {
				$recorddata['namamaskapai'] = $this->input->post('namamaskapai');
				$this->maskapai_model->ubah($id, $recorddata);
				$this->session->set_flashdata('pesan', 'Data berhasil disimpan');
				$url = base_url() . 'administrator/rating/daftar';
			}

			if (isset($url)) {
				redirect($url);
			}

			$this->load->view('administrator/rating_tambah', $viewdata);
		}
	}

	public function hapus($id) {
		$this->rating_model->hapus($id);
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/rating/daftar';
		redirect($url);
	}

	public function hapussemua() {
		$this->rating_model->hapussemua();
		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/rating/daftar';
		redirect($url);
	}

	public function lihatitem($idmaskapai, $idnilaisubkategori) {
		$viewdata = array();
		$viewdata['idmaskapai'] = $idmaskapai;

		if ($idnilaisubkategori != '') {
			$viewdata['nilaisubkategori'] = $this->nilaisubkategori_model->lihat($idnilaisubkategori)->row();
			$viewdata['kategori'] = $this->kategori_model->lihat($viewdata['nilaisubkategori']->idkategori)->row();
			$viewdata['subkategori'] = $this->subkategori_model->lihat($viewdata['nilaisubkategori']->idsubkategori)->row();
			$this->load->view('administrator/rating_lihatitem', $viewdata);
		}
	}

	public function hapusitemsubkategori($idmaskapai, $idnilaisubkategori) {
		if ($idmaskapai == 0) {
			redirect(base_url() . 'administrator/rating/daftar');
		}

		$recorddata = array();
		$recorddata['banyaksinonim'] = 0;
		$recorddata['banyakantonim'] = 0;
		$recorddata['jumlah'] = 0;
		$recorddata['threshold'] = 0;
		$recorddata['status'] = 0;
		$recorddata['jejaksinonim'] = "";
		$recorddata['jejakantonim'] = "";
		$this->nilaisubkategori_model->ubah($idnilaisubkategori, $recorddata);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/rating/lihat/' . $idmaskapai;
		redirect($url);
	}

	public function hapusitemkategori($idmaskapai, $idnilaikategori) {
		if ($idmaskapai == 0) {
			redirect(base_url() . 'administrator/rating/daftar');
		}

		$recorddata = array();
		$recorddata['jumlahkategori'] = 0;
		$recorddata['ratingkategori'] = 0;
		$this->nilaikategori_model->ubah($idnilaikategori, $recorddata);

		$recorddata = array();
		$recorddata['rating'] = 0;
		$this->maskapai_model->ubah($idmaskapai, $recorddata);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus');
		$url = base_url() . 'administrator/rating/lihat/' . $idmaskapai;
		redirect($url);
	}

	public function preparedatabase($idmaskapai) {
		$maskapai = $this->maskapai_model->lihat($idmaskapai)->row();

		$kategori = $this->kategori_model->daftarsemua();
		if ($kategori->num_rows() > 0) {
			$insertdatas = array();
			foreach ($kategori->result() as $row) {
				$insertdata = array();
				$insertdata['idmaskapai'] = $idmaskapai;
				$insertdata['idkategori'] = $row->idkategori;
				$insertdata['idkategori'] = $row->idkategori;
				if ($this->nilaikategori_model->carinilaikategori($idmaskapai, $row->idkategori, $row->idkategori) == 0) {
					array_push($insertdatas, $insertdata);
				}
			}

			if (sizeof($insertdatas) > 0) {
				$this->nilaikategori_model->tambahjamak($insertdatas);
			}
		}

		$subkategori = $this->subkategori_model->daftarsemua();
		if ($subkategori->num_rows() > 0) {
			$insertdatas = array();
			foreach ($subkategori->result() as $row) {
				$insertdata = array();
				$insertdata['idmaskapai'] = $idmaskapai;
				$insertdata['idkategori'] = $row->idkategori;
				$insertdata['idsubkategori'] = $row->idsubkategori;
				$insertdata['status'] = 0;
				if ($this->nilaisubkategori_model->carinilaisubkategori($idmaskapai, $row->idkategori, $row->idsubkategori) == 0) {
					array_push($insertdatas, $insertdata);
				}
			}

			if (sizeof($insertdatas) > 0) {
				$this->nilaisubkategori_model->tambahjamak($insertdatas);
			}
		}

		$response = array();
		$response['subcategorycount'] = $this->rating_model->hitungsemua($idmaskapai);
		$response['currentsubcategory'] = $this->rating_model->hitungsudah($idmaskapai);
		$response['success'] = 0;
		if ($kategori->num_rows() > 0 && $subkategori->num_rows() > 0) {
			$response['success'] = 1;
		}
		echo json_encode($response, TRUE);
	}

	public function calculatesubcategory($idmaskapai) {
		$maskapai = $this->maskapai_model->lihat($idmaskapai)->row();
		$antriansubkategori = $this->rating_model->pertama($idmaskapai)->row();
		if (isset($antriansubkategori)) {
			$datasubkategori = $this->subkategori_model->lihat($antriansubkategori->idsubkategori)->row();

			$kolomsinonim = $datasubkategori->sinonimsubkategori;
			$kolomantonim = $datasubkategori->antonimsubkategori;

			$sinonims = explode(', ', $kolomsinonim);
			$antonims = explode(', ', $kolomantonim);
			$jumlahsinonim = 0;
			$jumlahantonim = 0;
			$jejaksinonim = "";
			$jejakantonim = "";
			foreach ($sinonims as $sinonim) {
				if ($sinonim != '') {
					$banyaksinonim = $this->rating_model->hitungkata($idmaskapai, $sinonim);
					$jumlahsinonim += $banyaksinonim;
					$jejaksinonim .= $sinonim . ':' . $banyaksinonim . ', ';
				}
			}
			$jejaksinonim .= 'jumlah:' . $jumlahsinonim;
			foreach ($antonims as $antonim) {
				if ($antonim != '') {
					$banyakantonim = $this->rating_model->hitungkata($idmaskapai, $antonim);
					$jumlahantonim += $banyakantonim;
					$jejakantonim .= $antonim . ':' . $banyakantonim . ', ';
				}
			}
			$jejakantonim .= 'jumlah:' . $jumlahantonim;

			$recorddata = array();
			$recorddata['banyaksinonim'] = $jumlahsinonim;
			$recorddata['banyakantonim'] = $jumlahantonim;
			$jumlah = $jumlahsinonim - $jumlahantonim;
			$recorddata['jumlah'] = $jumlah;
			$treshold = 0;
			if ($jumlah >= 1) {
				$treshold = 1;
			} else if ($jumlah <= -1) {
				$treshold = -1;
			}
			$recorddata['threshold'] = $treshold;
			$recorddata['status'] = 1;
			$recorddata['jejaksinonim'] = $jejaksinonim;
			$recorddata['jejakantonim'] = $jejakantonim;
			$this->nilaisubkategori_model->ubah($antriansubkategori->idnilaisubkategori, $recorddata);

			$response = array();
			$response['finish'] = 0;
			$response['success'] = 1;
			$response['subcategorycount'] = $this->rating_model->hitungsemua($idmaskapai);
			$response['currentsubcategory'] = $this->rating_model->hitungsudah($idmaskapai);
			echo json_encode($response, TRUE);
		} else {
			$response = array();
			$response['finish'] = 1;
			$response['success'] = 1;
			$response['subcategorycount'] = $this->rating_model->hitungsemua($idmaskapai);
			$response['currentsubcategory'] = $this->rating_model->hitungsudah($idmaskapai);
			echo json_encode($response, TRUE);
		}
	}

	public function calculatecategory($idmaskapai) {
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

		$kategoris = $this->kategori_model->daftarsemua();
		$banyakkategori = $kategoris->num_rows();
		$jumlahkategori = 0;
		if ($banyakkategori > 0) {
			foreach ($kategoris->result() as $kategori) {
				$banyaksubkategori = $this->rating_model->banyaksubkategori($idmaskapai, $kategori->idkategori);
				$jumlahthreshold = $this->rating_model->jumlahthresholdsubkategori($idmaskapai, $kategori->idkategori);
				$ratingkategori = 0;
				if ($banyaksubkategori > 0) {
					$ratingkategori = $jumlahthreshold / $banyaksubkategori * $setting['maxrating'];
				}
				$jumlahkategori += $ratingkategori;

				$nilaikategori = $this->nilaikategori_model->lihatnilaikategori($idmaskapai, $kategori->idkategori);

				$recorddata = array();
				$recorddata['jumlahkategori'] = $jumlahthreshold;
				$recorddata['ratingkategori'] = $ratingkategori;
				$this->nilaikategori_model->ubah($nilaikategori->idnilaikategori, $recorddata);
			}

			$recorddata = array();
			$recorddata['rating'] = $jumlahkategori / $banyakkategori;
			$this->maskapai_model->ubah($idmaskapai, $recorddata);

			$response = array();
			$response['success'] = 1;
			echo json_encode($response, TRUE);
		} else {
			$response = array();
			$response['success'] = 0;
			echo json_encode($response, TRUE);
		}
	}

}

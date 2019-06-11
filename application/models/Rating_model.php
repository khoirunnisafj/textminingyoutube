<?php

Class Rating_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($idmaskapai, $katakunci = '') {
		$this->db->select('count(*)');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		if ($katakunci) {
			$this->db->like('filter', $katakunci);
		}
		return $this->db->count_all_results();
	}

	function daftarsubkategori($idmaskapai) {
		$this->db->select('*');
		$this->db->from('nilaisubkategori');
		$this->db->join('kategori', 'kategori.idkategori=nilaisubkategori.idkategori', 'left');
		$this->db->join('subkategori', 'subkategori.idsubkategori=nilaisubkategori.idsubkategori', 'left');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->order_by('namakategori');
		$this->db->order_by('namasubkategori');
		return $this->db->get();
	}

	function daftarkategori($idmaskapai) {
		$this->db->select('*');
		$this->db->from('nilaikategori');
		$this->db->join('kategori', 'kategori.idkategori=nilaikategori.idkategori', 'left');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->order_by('namakategori');
		return $this->db->get();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->where('idthread', $id);
		return $this->db->get();
	}

	function hapus($idmaskapai) {
		$recorddata = array();
		$recorddata['banyaksinonim'] = 0;
		$recorddata['banyakantonim'] = 0;
		$recorddata['jumlah'] = 0;
		$recorddata['threshold'] = 0;
		$recorddata['status'] = 0;
		$recorddata['jejaksinonim'] = "";
		$recorddata['jejakantonim'] = "";

		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->update('nilaisubkategori', $recorddata);
		
		$recorddata = array();
		$recorddata['jumlahkategori'] = 0;
		$recorddata['ratingkategori'] = 0;

		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->update('nilaikategori', $recorddata);		
	}

	function hitungsemua($idmaskapai) {
		$this->db->select('count(*)');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		return $this->db->count_all_results();
	}

	function hitungbelum($idmaskapai) {
		$this->db->select('count(*)');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('status', 0);
		return $this->db->count_all_results();
	}

	function hitungsudah($idmaskapai) {
		$this->db->select('count(*)');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('status', 1);
		return $this->db->count_all_results();
	}

	function hapussemua() {
		$recorddata = array();
		$recorddata['banyaksinonim'] = 0;
		$recorddata['banyakantonim'] = 0;
		$recorddata['jumlah'] = 0;
		$recorddata['threshold'] = 0;
		$recorddata['status'] = 0;
		$recorddata['jejaksinonim'] = "";
		$recorddata['jejakantonim'] = "";

		$this->db->update('nilaisubkategori', $recorddata);

		$recorddata = array();
		$recorddata['jumlahkategori'] = 0;
		$recorddata['ratingkategori'] = 0;

		$this->db->update('nilaikategori', $recorddata);

		$recorddata = array();
		$recorddata['rating'] = 0;

		$this->db->update('maskapai', $recorddata);
	}

	function pertama($idmaskapai) {
		$this->db->select('*');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('status', 0);
		$this->db->order_by('idnilaisubkategori asc');
		$this->db->limit('1');
		return $this->db->get();
	}

	function hitungkata($idmaskapai, $katakunci = '') {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $idmaskapai);
		if ($katakunci) {
			$sq = '"(^|[[:space:]])' . strtolower($katakunci) . '([[:space:]]|$)"';
			$this->db->where('filter REGEXP ', $sq, false);
		}
		return $this->db->count_all_results();
	}

	function banyaksubkategori($idmaskapai, $idkategori)
	{
		$this->db->select('count(idnilaisubkategori) as banyak');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('idkategori', $idkategori);
		return $this->db->get()->row()->banyak;
	}
	
	function jumlahthresholdsubkategori($idmaskapai, $idkategori)
	{
		$this->db->select('sum(threshold) as jumlah');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('idkategori', $idkategori);
		return $this->db->get()->row()->jumlah;
	}
	
}

?>
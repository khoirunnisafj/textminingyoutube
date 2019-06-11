<?php

Class Subkategori_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($katakunci, $saringan) {
		$this->db->select('count(*)');
		$this->db->from('subkategori');
		if ($katakunci) {
			$this->db->like('namasubkategori', $katakunci);
		}
		if ($saringan) {
			$this->db->where('idkategori', $saringan);
		}
		return $this->db->count_all_results();
	}

	function daftar($katakunci, $saringan, $baris, $nomor) {
		$this->db->select('*');
		$this->db->from('subkategori');
		$this->db->order_by('namasubkategori');
		if ($katakunci) {
			$this->db->like('namasubkategori', $katakunci);
		}
		if ($saringan) {
			$this->db->where('idkategori', $saringan);
		}
		$this->db->limit($baris, $nomor);
		return $this->db->get();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('subkategori');
		$this->db->where('idsubkategori', $id);
		return $this->db->get();
	}

	function tambah($data) {
		$this->db->insert('subkategori', $data);
	}

	function ubah($id, $data) {
		$this->db->where('idsubkategori', $id);
		$this->db->update('subkategori', $data);
	}

	function hapus($id, $data) {
		$this->db->where('idsubkategori', $id);
		$this->db->delete('subkategori');
	}

	function daftaritem($id) {
		$this->db->select('*');
		$this->db->from('subkategori');
		$this->db->where('idkategori', $id);
		$this->db->order_by('namasubkategori asc');
		return $this->db->get();
	}

	function cari($id) {
		$this->db->select('*');
		$this->db->from('subkategori');
		$this->db->where('idsubkategori', $id);
		return $this->db->get()->row();
	}

	function daftarsemua() {
		$this->db->select('*');
		$this->db->from('subkategori');
		$this->db->order_by('idkategori');
		$this->db->order_by('idsubkategori');
		return $this->db->get();
	}

}

?>
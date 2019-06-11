<?php

Class Kategori_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($katakunci) {
		$this->db->select('count(*)');
		$this->db->from('kategori');
		if ($katakunci) {
			$this->db->like('namakategori', $katakunci);
		}
		return $this->db->count_all_results();
	}

	function daftar($katakunci, $baris, $nomor) {
		$this->db->select('*');
		$this->db->from('kategori');
		$this->db->order_by('idkategori');
		if ($katakunci) {
			$this->db->like('namakategori', $katakunci);
		}
		$this->db->limit($baris, $nomor);
		return $this->db->get();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('kategori');
		$this->db->where('idkategori', $id);
		return $this->db->get();
	}

	function tambah($data) {
		$this->db->insert('kategori', $data);
	}

	function ubah($id, $data) {
		$this->db->where('idkategori', $id);
		$this->db->update('kategori', $data);
	}

	function hapus($id) {
		$this->db->where('idkategori', $id);
		$this->db->delete('subkategori');

		$this->db->where('idkategori', $id);
		$this->db->delete('kategori');
	}

	function larik() {
		$this->db->select('*');
		$this->db->from('kategori');
		$this->db->order_by('idkategori asc');
		$query = $this->db->get();
		$data = array();
		foreach ($query->result() as $row) {
			$data[$row->idkategori] = $row->namakategori;
		}
		return $data;
	}

	function cari($id) {
		$this->db->select('*');
		$this->db->from('kategori');
		$this->db->where('idkategori', $id);
		return $this->db->get()->row();
	}

	function daftarsemua() {
		$this->db->select('*');
		$this->db->from('kategori');
		$this->db->order_by('idkategori');
		return $this->db->get();
	}

}

?>
<?php

Class Ranking_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function daftar() {
		$this->db->select('*');
		$this->db->from('maskapai');
		$this->db->order_by('rating', 'desc');
		return $this->db->get();
	}

	function lihatpermaskapai($id) {
		$this->db->select('*');
		$this->db->from('maskapai');
		$this->db->where('idmaskapai', $id);
		$this->db->order_by('rating', 'desc');
		return $this->db->get();
	}

	function lihatperkategori($id) {
		$this->db->select('*');
		$this->db->from('nilaikategori');
		$this->db->join('kategori', 'kategori.idkategori=nilaikategori.idkategori', 'left');
		$this->db->where('idmaskapai', $id);
		$this->db->order_by('namakategori', 'asc');
		return $this->db->get();
	}

}

?>
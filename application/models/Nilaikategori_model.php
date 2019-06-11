<?php

Class Nilaikategori_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function ubah($id, $data) {
		$this->db->where('idnilaikategori', $id);
		$this->db->update('nilaikategori', $data);
	}

	function hapuspermaskapai($idmaskapai) {
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->delete('nilaikategori');
	}

	function tambahjamak($data) {
		$this->db->insert_batch('nilaikategori', $data);
	}

	function carinilaikategori($idmaskapai, $idkategori) {
		$this->db->select('count(*)');
		$this->db->from('nilaikategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('idkategori', $idkategori);
		return $this->db->count_all_results();
	}

	function lihatnilaikategori($idmaskapai, $idkategori) {
		$this->db->select('*');
		$this->db->from('nilaikategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('idkategori', $idkategori);
		return $this->db->get()->row();
	}

}

?>
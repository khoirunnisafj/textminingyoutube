<?php

Class Nilaisubkategori_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('nilaisubkategori');
		$this->db->where('idnilaisubkategori', $id);
		return $this->db->get();
	}

	function ubah($id, $data) {
		$this->db->where('idnilaisubkategori', $id);
		$this->db->update('nilaisubkategori', $data);
	}

	function hapuspermaskapai($id) {
		$this->db->where('idmaskapai', $id);
		$this->db->delete('nilaisubkategori');
	}

	function tambahjamak($data) {
		$this->db->insert_batch('nilaisubkategori', $data);
	}

	function carinilaisubkategori($idmaskapai, $idkategori, $idsubkategori) {
		$this->db->select('count(*)');
		$this->db->from('nilaisubkategori');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('idkategori', $idkategori);
		$this->db->where('idsubkategori', $idsubkategori);
		return $this->db->count_all_results();
	}

}

?>
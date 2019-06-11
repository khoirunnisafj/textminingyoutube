<?php

Class Maskapai_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($katakunci) {
		$this->db->select('count(*)');
		$this->db->from('maskapai');
		if ($katakunci) {
			$this->db->like('namamaskapai', $katakunci);
		}
		return $this->db->count_all_results();
	}

	function daftar($katakunci, $baris, $nomor) {
		$this->db->select('*');
		$this->db->from('maskapai');
		$this->db->order_by('namamaskapai');
		if ($katakunci) {
			$this->db->like('namamaskapai', $katakunci);
		}
		$this->db->limit($baris, $nomor);
		return $this->db->get();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('maskapai');
		$this->db->where('idmaskapai', $id);
		return $this->db->get();
	}

	function tambah($data) {
		$this->db->insert('maskapai', $data);
		return $this->db->insert_id();
	}

	function ubah($id, $data) {
		$this->db->where('idmaskapai', $id);
		$this->db->update('maskapai', $data);
	}

	function hapus($id, $data) {
		$this->db->where('idmaskapai', $id);
		$this->db->delete('thread');

		$this->db->where('idmaskapai', $id);
		$this->db->delete('video');

		$this->db->where('idmaskapai', $id);
		$this->db->delete('maskapai');
	}

	function larik() {
		$this->db->select('*');
		$this->db->from('maskapai');
		$this->db->order_by('namamaskapai asc');
		$query = $this->db->get();
		$data[''] = '';
		foreach ($query->result() as $row) {
			$data[$row->idmaskapai] = $row->namamaskapai;
		}
		return $data;
	}

}

?>
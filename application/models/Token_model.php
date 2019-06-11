<?php

Class Token_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($idmaskapai, $katakunci = '') {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $idmaskapai);
		if ($katakunci) {
			$this->db->like('token', $katakunci);
		}
		return $this->db->count_all_results();
	}

	function daftar($idmaskapai, $katakunci, $baris, $nomor) {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $idmaskapai);
		if ($katakunci) {
			$this->db->like('token', $katakunci);
		}
		$this->db->order_by('idthread');
		$this->db->limit($baris, $nomor);
		return $this->db->get();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->where('idthread', $id);
		return $this->db->get();
	}

	function ubah($id, $data) {
		$this->db->where('idthread', $id);
		$this->db->update('thread', $data);
	}

	function hapus($idmaskapai) {
		$recorddata = array();
		$recorddata['token'] = "";
		$recorddata['filter'] = "";
		$recorddata['status'] = 0;

		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->update('thread', $recorddata);
	}

	function hitungsemua($idmaskapai) {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $idmaskapai);
		return $this->db->count_all_results();
	}

	function hitungbelum($idmaskapai) {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('status', 0);
		return $this->db->count_all_results();
	}

	function hitungsudah($idmaskapai) {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $idmaskapai);
		$this->db->where('status', 1);
		return $this->db->count_all_results();
	}

	function hapusitem($idthread) {
		$recorddata = array();
		$recorddata['token'] = "";
		$recorddata['filter'] = "";
		$recorddata['status'] = 0;

		$this->db->where('idthread', $idthread);
		$this->db->update('thread', $recorddata);
	}

	function hapussemua() {
		$recorddata = array();
		$recorddata['token'] = "";
		$recorddata['filter'] = "";
		$recorddata['status'] = 0;

		$this->db->update('thread', $recorddata);
	}

	function pertama($id) {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $id);
		$this->db->where('status', 0);
		$this->db->order_by('idthread asc');
		$this->db->limit('1');
		return $this->db->get();
	}

}

?>
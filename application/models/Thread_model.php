<?php

Class Thread_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($katakunci) {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $katakunci);
		return $this->db->count_all_results();
	}

	function lihatByMaskapai($id) {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $id);
		$this->db->order_by('idthread');

		return $this->db->get();
	}

	function daftar($id, $katakunci, $baris, $nomor) {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $id);
		if ($katakunci) {
			$this->db->like('isi', $katakunci);
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

	function tambah($data) {
		$this->db->insert('thread', $data);
	}

	function ubah($id, $data) {
		$this->db->where('idthread', $id);
		$this->db->update('thread', $data);
	}

	function hapus($id) {
		$this->db->where('idthread', $id);
		$this->db->delete('thread');
	}

	function larik() {
		$this->db->select('*');
		$this->db->from('thread');
		$this->db->order_by('namathread asc');
		$query = $this->db->get();
		$data[''] = '';
		foreach ($query->result() as $row) {
			$data[$row->idthread] = $row->namathread;
		}
		return $data;
	}

	function tambahjamak($data) {
		$this->db->insert_batch('thread', $data);
	}

	function carithread($id) {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('threadid', $id);
		return $this->db->count_all_results();
	}

	function caricomment($id) {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('commentid', $id);
		return $this->db->count_all_results();
	}

	function hitungpermaskapai($id, $katakunci = '') {
		$this->db->select('count(*)');
		$this->db->from('thread');
		$this->db->where('idmaskapai', $id);
		if ($katakunci) {
			$this->db->like('isi', $katakunci);
		}
		return $this->db->count_all_results();
	}

	function hapuspermaskapai($id) {
		$this->db->where('idmaskapai', $id);
		$this->db->delete('thread');

		$recorddata = array();
		$recorddata['threadtoken'] = "";
		$recorddata['status'] = 0;
		$this->db->where('idmaskapai', $id);
		$this->db->update('video', $recorddata);
	}

	function hapussemua() {
		$this->db->where('idthread!=', 0);
		$this->db->delete('thread');

		$recorddata = array();
		$recorddata['threadtoken'] = "";
		$recorddata['status'] = 0;
		$this->db->update('video', $recorddata);
	}

}

?>
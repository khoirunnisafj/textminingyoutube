<?php

Class Video_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function hitung($katakunci) {
		$this->db->select('count(*)');
		$this->db->from('video');
		$this->db->where('idmaskapai', $katakunci);
		return $this->db->count_all_results();
	}

	function daftar($id, $katakunci, $baris, $nomor) {
		$this->db->select('*');
		$this->db->from('video');
		$this->db->where('idmaskapai', $id);
		if ($katakunci) {
			$this->db->like('judul', $katakunci);
		}
		$this->db->order_by('idvideo');
		$this->db->limit($baris, $nomor);
		return $this->db->get();
	}

	function lihat($id) {
		$this->db->select('*');
		$this->db->from('video');
		$this->db->where('idvideo', $id);
		return $this->db->get();
	}

	function tambah($data) {
		$this->db->insert('video', $data);
	}

	function ubah($id, $data) {
		$this->db->where('idvideo', $id);
		$this->db->update('video', $data);
	}

	function hapus($id) {
		$this->db->where('idvideo', $id);
		$this->db->delete('thread');

		$this->db->where('idvideo', $id);
		$this->db->delete('video');
	}

	function larik() {
		$this->db->select('*');
		$this->db->from('video');
		$this->db->order_by('namavideo asc');
		$query = $this->db->get();
		$data[''] = '';
		foreach ($query->result() as $row) {
			$data[$row->idvideo] = $row->namavideo;
		}
		return $data;
	}

	function tambahjamak($data) {
		$this->db->insert_batch('video', $data);
	}

	function cari($id) {
		$this->db->select('count(*)');
		$this->db->from('video');
		$this->db->where('videoid', $id);
		return $this->db->count_all_results();
	}

	function hitungpermaskapai($id, $katakunci = '') {
		$this->db->select('count(*)');
		$this->db->from('video');
		$this->db->where('idmaskapai', $id);
		if ($katakunci) {
			$this->db->like('judul', $katakunci);
		}
		return $this->db->count_all_results();
	}

	function hapuspermaskapai($id) {
		$this->db->where('idmaskapai', $id);
		$this->db->delete('thread');

		$this->db->where('idmaskapai', $id);
		$this->db->delete('video');
	}

	function hapussemua() {
		$this->db->where('idthread!=', 0);
		$this->db->delete('thread');

		$this->db->where('idvideo!=', 0);
		$this->db->delete('video');

		$recorddata = array();
		$recorddata['videotoken'] = "";
		$this->db->update('maskapai', $recorddata);
	}

	function pertama($id) {
		$this->db->select('*');
		$this->db->from('video');
		$this->db->where('idmaskapai', $id);
		$this->db->where('status', 0);
		$this->db->order_by('idvideo asc');
		$this->db->limit('1');
		return $this->db->get();
	}

}

?>
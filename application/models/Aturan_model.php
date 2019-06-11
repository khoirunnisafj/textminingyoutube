<?php

Class Aturan_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function daftar() {
		$this->db->select('*');
		$this->db->from('aturan');
		$this->db->order_by('idaturan');
		return $this->db->get();
	}

	function ubah($id, $data) {
		$this->db->where('idaturan', $id);
		$this->db->update('aturan', $data);
	}

	function larik() {
		$this->db->select('*');
		$this->db->from('aturan');
		$this->db->order_by('idaturan asc');
		$query = $this->db->get();
		$data = array();
		$data[''] = '';

		foreach ($query->result() as $row) {
			$data[$row->idaturan] = $row->nilaiaturan;
		}
		return $data;
	}

	function tahunajaranrekaman() {
		$this->db->select('*');
		$this->db->from('aturan');
		$this->db->where('idaturan', 1);
		$query = $this->db->get();
		return $query->row()->nilaiaturan;
	}

	function tahunajaransesi() {
		$tahunajaran = 0;
		if ($this->session->userdata('aturan-1')) {
			$tahunajaran = $this->session->userdata('aturan-1');
		} else {
			$this->db->select('*');
			$this->db->from('aturan');
			$this->db->where('idaturan', 1);
			$query = $this->db->get();
			$tahunajaran = $query->row()->nilaiaturan;
		}
		return $tahunajaran;
	}

	function semesterrekaman() {
		$this->db->select('*');
		$this->db->from('aturan');
		$this->db->where('idaturan', 2);
		$query = $this->db->get();
		return $query->row()->nilaiaturan;
	}

	function semestersesi() {
		$semester = 0;
		if ($this->session->userdata('aturan-2')) {
			$semester = $this->session->userdata('aturan-2');
		} else {
			$this->db->select('*');
			$this->db->from('aturan');
			$this->db->where('idaturan', 2);
			$query = $this->db->get();
			$semester = $query->row()->nilaiaturan;
		}
		return $semester;
	}

}

?>
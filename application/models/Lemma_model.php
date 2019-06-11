<?php

Class Lemma_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function cari($katakunci) {
		$this->db->select('count(*)');
		$this->db->from('katadasar');
		$this->db->where('katadasar', $katakunci);
		return $this->db->count_all_results();
	}

}

?>
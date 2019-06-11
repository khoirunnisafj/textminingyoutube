<?php

Class Login_model extends CI_Model {

	Public function __construct() {
		parent::__construct();
	}

	function login($username, $password) {
		$this->db->select('*');
		$this->db->from('pengguna');
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

}

?>
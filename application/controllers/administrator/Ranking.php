<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ranking extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('aturan_model', '', TRUE);
		$this->load->model('ranking_model', '', TRUE);
	}

	public function index() {
		$this->daftar();
	}

	public function daftar() {
		$viewdata = array();

		$settingsrecords = $this->aturan_model->daftar();
		foreach ($settingsrecords->result() as $row) {
			switch ($row->idaturan) {
				case 4: {
						$viewdata['maxrating'] = $row->nilaiaturan;
					}
					break;
			}
		}		

		$viewdata['ranking'] = $this->ranking_model->daftar();
		$this->load->view('administrator/ranking_daftar', $viewdata);
	}

	public function lihat($id) {
		$viewdata = array();

		$settingsrecords = $this->aturan_model->daftar();
		foreach ($settingsrecords->result() as $row) {
			switch ($row->idaturan) {
				case 4: {
						$viewdata['maxrating'] = $row->nilaiaturan;
					}
					break;
			}
		}		

		$viewdata['ranking'] = $this->ranking_model->lihatpermaskapai($id)->row();
		$viewdata['rankings'] = $this->ranking_model->lihatperkategori($id);
		$this->load->view('administrator/ranking_lihat', $viewdata);
	}

}

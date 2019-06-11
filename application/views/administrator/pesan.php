<?php
$pesan = @$this->session->flashdata('pesan');
if ($pesan != '') {
	?>
	<div class="alert alert-warning" role="alert"><?php echo $pesan; ?></div>

<?php } ?>
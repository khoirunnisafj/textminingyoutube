<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Daftar Video Maskapai</h3>
	<p>&nbsp;</p>

	<?php include('pesan.php'); ?>

	<form action="" method="post" id="form_search">
		<div class="row form-group">
			<label class="col-sm-2 control-label">Nama Maskapai</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" id="katakunci" name="katakunci" value="<?php echo $katakunci; ?>"/>
			</div>
			<div class="col-sm-2">
				<input type="submit" class="form-control" value="Cari"/>
			</div>
			<!-- <div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/video/hapussemua'; ?>" onclick="return confirm('Semua video, thread dan comment akan terhapus.\nVideo, thread dan comment yang sudah terhapus tidak dapat dikembalikan lagi.\nApakah anda benar-benar ingin menghapus semua video, thread dan comment?')" class="btn btn-success col-sm-12">Reset</a>
			</div>
 -->
		</div>
	</form>
	<p>&nbsp;</p>

	<table class="table table-bordered table-hover">
		<tr>
			<th class="col-sm-1 right">No.</th>
			<th class="col-sm-9">Nama Maskapai</th>
			<th class="col-sm-2">Perintah</th>
		</tr>

		<?php
		if ($maskapai->num_rows() == 0) {
			?>
			<tr>
				<td colspan="4">Data tidak ditemukan</td>
			</tr>
			<?php
		} else {
			foreach ($maskapai->result() as $row) {
				$nomor++;
				?>
				<tr>
					<td class="right"><?php echo $nomor; ?></td>
					<td><?php echo $row->namamaskapai; ?></td>
					<td>
						<a href="<?php echo base_url() . 'administrator/video/lihat/' . $row->idmaskapai; ?>" class="btn btn-info" title="Lihat"><span class="fa fa-list"></span></a>
						<a href="<?php echo base_url() . 'administrator/video/ubah/' . $row->idmaskapai; ?>" class="btn btn-primary" title="Ubah"><span class="fa fa-edit"></span></a>
						<!-- <a href="<?php echo base_url() . 'administrator/video/hapus/' . $row->idmaskapai; ?>" onclick="return confirm('Semua video, thread dan comment maskapai ini akan terhapus.\nVideo, thread dan comment yang sudah terhapus tidak dapat dikembalikan lagi.\nApakah anda benar-benar ingin menghapus video, thread dan comment maskapai ini?')" class="btn btn-danger" title="Hapus"><span class="fa fa-trash"></span></a> -->
					</td>
				</tr>
				<?php
			}
		}
		?>
	</table>

	<div class="pagination">
		<?php echo $this->pagination->create_links(); ?>
	</div>

</div>

<?php include('footer.php'); ?>

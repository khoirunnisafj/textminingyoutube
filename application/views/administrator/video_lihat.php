<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Daftar Video</h3>
	<p>&nbsp;</p>

	<form name="form_maskapai" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/video/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>

		<div class="row form-group">
			<label for="namamaskapai" class="col-sm-2 control-label">Nama Maskapai</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="namamaskapai" name="namamaskapai" value="<?php echo set_value('namamaskapai', @$video->namamaskapai); ?>" readonly />
			</div>
		</div>

		<br />

		<div class="row form-group">
			<label class="col-sm-2 control-label">Judul</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="katakunci" name="katakunci" value="<?php echo $katakunci; ?>"/>
			</div>
			<div class="col-sm-2">
				<input type="submit" class="form-control" value="Cari"/>
			</div>
		</div>

		<table border="0" class="table table-striped table-bordered">
			<tr>
				<th class="col-sm-1">No.</th>
				<th class="col-sm-2">Video ID</th>
				<th class="col-sm-7">Judul</th>
				<th class="col-sm-2">Perintah</th>
			</tr>

			<?php
			if ($videos->num_rows() == 0) {
				?>
				<tr>
					<td colspan="6">Data tidak ditemukan</td>
				</tr>
				<?php
			} else {
				foreach ($videos->result() as $row) {
					$nomor++;
					?>
					<tr>
						<td><?php echo $nomor; ?></td>
						<td><?php echo $row->videoid; ?></td>
						<td><?php echo $row->judul; ?></td>
						<td>
							<a href="<?php echo base_url() . 'administrator/video/lihatitem/' . $row->idmaskapai . '/' . $row->idvideo; ?>" class="btn btn-info" title="Lihat"><span class="fa fa-list"></span></a>
							<a href="<?php echo base_url() . 'administrator/video/hapusitem/' . $row->idmaskapai . '/' . $row->idvideo; ?>" onclick="return confirm('Semua thread dan comment video ini akan ikut terhapus.\nVideo, thread dan comment yang sudah terhapus tidak dapat dikembalikan lagi.\nApakah anda benar-benar ingin menghapus video ini?')" class="btn btn-danger" ><span class="fa fa-trash"></span></a>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</table>

	</form>

	<div class="pagination">
		<?php echo $this->pagination->create_links(); ?>
	</div>

</div>

<?php include('footer.php'); ?>
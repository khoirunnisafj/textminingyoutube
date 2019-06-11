<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Daftar Kategori dan Sub Kategori</h3>
	<p>&nbsp;</p>

	<form name="form_maskapai" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/rating/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>

		<div class="row form-group">
			<label for="namamaskapai" class="col-sm-2 control-label">Nama Maskapai</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="namamaskapai" name="namamaskapai" value="<?php echo set_value('namamaskapai', @$maskapai->namamaskapai); ?>" readonly />
			</div>
		</div>
		<p>&nbsp;</p>

		<h4>Daftar Sub Kategori</h4>
		<p>&nbsp;</p>

		<table border="0" class="table table-striped table-bordered">
			<tr>
				<th class="col-sm-1">No.</th>
				<th class="col-sm-3">Kategori</th>
				<th class="col-sm-2">Sub Kategori</th>
				<th class="col-sm-1">Sinonim</th>
				<th class="col-sm-1">Antonim</th>
				<th class="col-sm-1">Jumlah</th>
				<th class="col-sm-1">Threshold</th>
				<th class="col-sm-1">Perintah</th>
			</tr>

			<?php
			if ($subkategoris->num_rows() == 0) {
				?>
				<tr>
					<td colspan="6">Data tidak ditemukan</td>
				</tr>
				<?php
			} else {
				$nomor = 1;
				foreach ($subkategoris->result() as $row) {
					?>
					<tr>
						<td><?php echo $nomor; ?></td>
						<td><?php echo $row->namakategori; ?></td>
						<td><?php echo $row->namasubkategori; ?></td>
						<td><?php echo $row->banyaksinonim; ?></td>
						<td><?php echo $row->banyakantonim; ?></td>
						<td><?php echo $row->jumlah; ?></td>
						<td><?php echo $row->threshold; ?></td>
						<td>
							<a href="<?php echo base_url() . 'administrator/rating/lihatitem/' . $row->idmaskapai . '/' . $row->idnilaisubkategori; ?>" class="btn btn-info" title="Lihat"><span class="fa fa-list"></span></a>
							<a href="<?php echo base_url() . 'administrator/rating/hapusitemsubkategori/' . $row->idmaskapai . '/' . $row->idnilaisubkategori; ?>" onclick="return confirm('Hasil rating akan terhapus.\nHasil rating yang sudah terhapus tidak dapat dikembalikan lagi.\nApakah anda benar-benar ingin menghapus hasil rating ini?')" class="btn btn-danger" ><span class="fa fa-trash"></span></a>
						</td>
					</tr>
					<?php
					$nomor++;
				}
			}
			?>
		</table>
		<p>&nbsp;</p>

		<h4>Daftar Kategori</h4>
		<p>&nbsp;</p>

		<table border="0" class="table table-striped table-bordered">
			<tr>
				<th class="col-sm-1">No.</th>
				<th class="col-sm-8">Kategori</th>
				<th class="col-sm-1">Jumlah</th>
				<th class="col-sm-1">Rating</th>
				<th class="col-sm-1">Perintah</th>
			</tr>

			<?php
			if ($kategoris->num_rows() == 0) {
				?>
				<tr>
					<td colspan="6">Data tidak ditemukan</td>
				</tr>
				<?php
			} else {
				$nomor = 1;
				foreach ($kategoris->result() as $row) {
					?>
					<tr>
						<td><?php echo $nomor; ?></td>
						<td><?php echo $row->namakategori; ?></td>
						<td><?php echo $row->jumlahkategori; ?></td>
						<td><?php echo $row->ratingkategori; ?></td>
						<td>
							<a href="<?php echo base_url() . 'administrator/rating/hapusitemkategori/' . $row->idmaskapai . '/' . $row->idnilaikategori; ?>" onclick="return confirm('Hasil rating akan terhapus.\nHasil rating yang sudah terhapus tidak dapat dikembalikan lagi.\nApakah anda benar-benar ingin menghapus hasil rating ini?')" class="btn btn-danger" ><span class="fa fa-trash"></span></a>
						</td>
					</tr>
					<?php
					$nomor++;
				}
			}
			?>
		</table>
	</form>

</div>

<?php include('footer.php'); ?>
<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Daftar Thread</h3>
	<p>&nbsp;</p>

	<form name="form_maskapai" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/thread/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>

		<div class="row form-group">
			<label for="namamaskapai" class="col-sm-2 control-label">Nama Maskapai</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="namamaskapai" name="namamaskapai" value="<?php echo set_value('namamaskapai', @$thread->namamaskapai); ?>" readonly />
			</div>
		</div>

		<br />

		<div class="row form-group">
			<label class="col-sm-2 control-label">Isi</label>
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
				<th class="col-sm-2">Thread ID / Comment ID</th>
				<th class="col-sm-3">Isi</th>
				<th class="col-sm-2">Token</th>
				<th class="col-sm-2">Filter</th>
				<th class="col-sm-2">Perintah</th>
			</tr>

			<?php
			if ($threads->num_rows() == 0) {
				?>
				<tr>
					<td colspan="6">Data tidak ditemukan</td>
				</tr>
				<?php
			} else {
				foreach ($threads->result() as $row) {
					$nomor++;
					?>
					<tr>
						<td><?php echo $nomor; ?></td>
						<td><?php
							echo $row->threadid;
							if ($row->commentid != '') {
								echo '<br />' . $row->commentid;
							}
							?></td>
						<td><?php echo $row->isi; ?></td>
						<td><?php echo $row->token; ?></td>
						<td><?php echo $row->filter; ?></td>
						<td>
							<a href="<?php echo base_url() . 'administrator/thread/lihatitem/' . $row->idmaskapai . '/' . $row->idthread; ?>" class="btn btn-info" title="Lihat"><span class="fa fa-list"></span></a>
							<!--
							<a href="<?php echo base_url() . 'administrator/thread/ubahitem/' . $row->idmaskapai . '/' . $row->idthread; ?>" class="btn btn-primary" title="Ubah"><span class="fa fa-edit"></span></a>
							-->
							<a href="<?php echo base_url() . 'administrator/thread/hapusitem/' . $row->idmaskapai . '/' . $row->idthread; ?>" onclick="return confirm('Apakah anda benar-benar ingin menghapus thread ini?')" class="btn btn-danger" ><span class="fa fa-trash"></span></a>
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
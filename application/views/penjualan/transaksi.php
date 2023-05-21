<!-- <?php $this->load->view('include/header'); ?> -->
<?php $this->load->view('include/navbar'); ?>

<style>
	.footer {
		margin-bottom: 22px;
	}

	.panel-primary .form-group {
		margin-bottom: 10px;
	}

	.form-control {
		border-radius: 0px;
		box-shadow: none;
	}

	.error_validasi {
		margin-top: 0px;
	}
</style>

<?php
$level 		= $this->session->userdata('ap_level');
$readonly	= '';
$disabled	= '';
if ($level !== 'admin') {
	$readonly	= 'readonly';
	$disabled	= 'disabled';
}
?>

<section class="content">
<div class="container-fluid">
	<div class="col-lg-12">
        <div class="card card-primary card-outline">
			<div class="card-header">
				<h3 class="card-title">
					<i class='fa fa-shopping-cart fa-fw'></i> Sales <i class='fa fa-angle-right fa-fw'></i> Transaction
					<a href="<?php echo site_url('penjualan/transaksi'); ?>" class="pull-right" style="float: right;"><i class='fa fa-refresh fa-fw'></i> Refresh Page</a>
					
				</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="card card-primary">
							<div class="card-header"> Receipt Information
							</div>
					
							<div class="card-body">
								<div class="form-horizontal">
									<div class="form-group row">
										<label class="col-sm-4 control-label">No. Receipt</label>
										<div class="col-sm-8">
											<input type='text' name='nomor_nota' class='form-control input-sm' id='nomor_nota' readonly value="<?php echo strtoupper(uniqid()) . $this->session->userdata('ap_id_user'); ?> " <?php echo $readonly; ?>>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label">Date</label>
										<div class="col-sm-8">
											<input type='text' name='tanggal' class='form-control input-sm' id='tanggal' value="<?php echo date('Y-m-d H:i:s'); ?>" <?php echo $disabled; ?>>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 control-label">Cashier</label>
										<div class="col-sm-8">
											<select name='id_kasir' id='id_kasir' class='form-control input-sm' <?php echo $disabled; ?>>
												<?php
												if ($kasirnya->num_rows() > 0) {
													foreach ($kasirnya->result() as $k) {
														$selected = '';
														if ($k->id_user == $this->session->userdata('ap_id_user')) {
															$selected = 'selected';
														}

														echo "<option value='" . $k->id_user . "' " . $selected . ">" . $k->nama . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="col-md-6">
						<div class="card card-primary">
							<div class="card-header"> Customer Information
							</div>

							<div class="card-body">
								<div class="form-horizontal">
									<div class="form-group row">
										<label class="col-sm-4 control-label">Customer</label>
										<a href="<?php echo site_url('penjualan/tambah-pelanggan'); ?>" class='pull-right' id='TambahPelanggan' style="color: red;">Add Member ?</a>
									</div>

									<div class="form-group row">
										<label class="col-sm-4 control-label">Name</label>
										<select name='id_pelanggan' id='id_pelanggan' class='form-control input-sm col-sm-8' style='cursor: pointer;'>
											<option value=''>-- No Member --</option>
											<?php
											if ($pelanggan->num_rows() > 0) {
												foreach ($pelanggan->result() as $p) {
													echo "<option value='" . $p->id_pelanggan . "'>" . $p->nama . "</option>";
												}
											}
											?>
										</select>
										
									</div>

									<div class="form-group row">
										<label class="col-sm-4 control-label">No Handphone</label>
										<div class="col-sm-8">
											<div id='telp_pelanggan'><small><i>No Data</i></small></div>
										</div>
									</div>
								
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>




			<div class="card-body">
				<div class="col-md-12">
					<div class="card card-primary">
						<div class="card-header"> Product
						</div>

						<div class="card-body">
							<table class="table table-bordered table-striped" id="TabelTransaksi">
							<thead>
								<tr>
									<th style='width:35px;'> No </th>
									<th style='width:210px;'> Product Code </th>
									<th> Product Name </th>
									<th style='width:120px;'> Price </th>
									<th style='width:75px;'> Qty </th>
									<th style='width:125px;'> Sub Total </th>
									<th style='width:40px;'> Action </th>
								</tr>
							</thead>
							<tbody></tbody>
							</table>
							
							<div class="card-body">
								<button id='BarisBaru' class='btn btn-default'><i class='fa fa-plus fa-fw'></i> Add Item </button>
							</div>

							<div class='card-body'>

								<div class='row'>
									<div class='col-sm-8' style='padding-right: 0px;'>
										<p><b> Keyboard Shortcut  : </b></p>
										<!-- <div class='row'> -->
											<div class='col-sm-6'>F7 : Add Item </div>
											<div class='col-sm-6'>F8 : Field Focus Payment </div>
											<div class='col-sm-6'>F10 : Save Transaction </div>
										<!-- </div> -->
									</div>

									<div class="col-sm-4">
										<div class="form-horizontal">
											<div class="form-group row">
												<h2 class="col-sm-4 control-label"> Total </h2> 
												<div class="col-sm-8">
													<h2><span id='TotalBayar'>Rp. 0</span></h2>
														<input type="hidden" id='TotalBayarHidden'>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-4 control-label"> Cash </label>
												<div class="col-sm-8">
													<input type='text' name='cash' id='UangCash' class='form-control' onkeypress='return check_int(event)' oninput='formatCurrency(this)'>
												</div>
											</div>
											<div class="form-group row" >
												<label class="col-sm-4 control-label"> Change </label>
												<div class="col-sm-8">
													<input type='text' id='UangKembali' class='form-control' disabled>
												</div>
											</div>
										</div>
									</div>
										
								
								</div>

							</div>

							<div class='row'>
								<div class='col-sm-6' style='padding-right: 0px;'>
								</div>

								<div class='col-sm-6'>
									<div class="form-horizontal">
										<div class='row'>
											<div class='col-sm-6' style='padding-right: 0px;'>
												<button type='button' class='btn btn-warning btn-block' id='CetakStruk'>
													Print Receipt 
												</button>
											</div>
											<div class='col-sm-6'>
												<button type='button' class='btn btn-success btn-block' id='Simpann'>
												 	Save Transaction
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>

<!-- <p class='footer'><?php echo config_item('web_footer'); ?></p> -->

<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css" />
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script  type="text/javascript">
	$('#tanggal').datetimepicker({
		lang: 'en',
		timepicker: true,
		format: 'Y-m-d H:i:s'
	});

	$(document).ready(function() {

		for (B = 1; B <= 1; B++) {
			BarisBaru();
		}

		$('#id_pelanggan').change(function() {
			if ($(this).val() !== '') {
				$.ajax({
					url: "<?php echo site_url('penjualan/ajax-pelanggan'); ?>",
					type: "POST",
					cache: false,
					data: "id_pelanggan=" + $(this).val(),
					dataType: 'json',
					success: function(json) {
						$('#telp_pelanggan').html(json.telp);
						$('#alamat_pelanggan').html(json.alamat);
						$('#info_tambahan_pelanggan').html(json.info_tambahan);
					}
				});
			} else {
				$('#telp_pelanggan').html('<small><i>No data</i></small>');
				$('#alamat_pelanggan').html('<small><i>No data</i></small>');
				$('#info_tambahan_pelanggan').html('<small><i>No data</i></small>');
			}
		});

		$('#BarisBaru').click(function() {
			BarisBaru();
		});

		$("#TabelTransaksi tbody").find('input[type=text],textarea,select').filter(':visible:first').focus();
	});

	function BarisBaru() {
		var Nomor = $('#TabelTransaksi tbody tr').length + 1;
		var Baris = "<tr>";
		Baris += "<td>" + Nomor + "</td>";
		Baris += "<td>";
		Baris += "<input type='text' class='form-control' name='kode_produk[]' id='pencarian_kode' placeholder='Add Code / Product Name'>";
		Baris += "<div id='hasil_pencarian'></div>";
		Baris += "</td>";
		Baris += "<td></td>";
		Baris += "<td>";
		Baris += "<input type='hidden' name='harga_satuan[]'>";
		Baris += "<span></span>";
		Baris += "</td>";
		Baris += "<td><input type='text' class='form-control' id='jumlah_beli' name='jumlah_beli[]' onkeypress='return check_int(event)' disabled></td>";
		Baris += "<td>";
		Baris += "<input type='hidden' name='sub_total[]'>";
		Baris += "<span></span>";
		Baris += "</td>";
		Baris += "<td><button class='btn btn-default' id='HapusBaris'><i class='fa fa-times' style='color:red;'></i></button></td>";
		Baris += "</tr>";

		$('#TabelTransaksi tbody').append(Baris);

		$('#TabelTransaksi tbody tr').each(function() {
			$(this).find('td:nth-child(2) input').focus();
		});

		HitungTotalBayar();
	}

	$(document).on('click', '#HapusBaris', function(e) {
		e.preventDefault();
		$(this).parent().parent().remove();

		var Nomor = 1;
		$('#TabelTransaksi tbody tr').each(function() {
			$(this).find('td:nth-child(1)').html(Nomor);
			Nomor++;
		});

		HitungTotalBayar();
	});

	function AutoCompleteGue(Lebar, KataKunci, Indexnya) {
		$('div#hasil_pencarian').hide();
		var Lebar = Lebar + 25;

		var Registered = '';
		$('#TabelTransaksi tbody tr').each(function() {
			if (Indexnya !== $(this).index()) {
				if ($(this).find('td:nth-child(2) input').val() !== '') {
					Registered += $(this).find('td:nth-child(2) input').val() + ',';
				}
			}
		});

		if (Registered !== '') {
			Registered = Registered.replace(/,\s*$/, "");
		}

		$.ajax({
			url: "<?php echo site_url('penjualan/ajax-kode'); ?>",
			type: "POST",
			cache: false,
			data: 'keyword=' + KataKunci + '&registered=' + Registered,
			dataType: 'json',
			success: function(json) {
				if (json.status == 1) {
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').css({
						'width': Lebar + 'px'
					});
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').show('fast');
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').html(json.datanya);
				}
				if (json.status == 0) {
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(3)').html('');
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val('');
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(4) span').html('');
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').prop('disabled', true).val('');
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(0);
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html('');
				}
			}
		});

		HitungTotalBayar();
	}

	$(document).on('keyup', '#pencarian_kode', function(e) {
		if ($(this).val() !== '') {
			var charCode = e.which || e.keyCode;
			if (charCode == 40) {
				if ($('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0) {
					var Selanjutnya = $('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').next();
					$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

					Selanjutnya.addClass('autocomplete_active');
				} else {
					$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
				}
			} else if (charCode == 38) {
				if ($('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0) {
					var Sebelumnya = $('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').prev();
					$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

					Sebelumnya.addClass('autocomplete_active');
				} else {
					$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
				}
			} else if (charCode == 13) {
				var Field = $('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)');
				var Kodenya = Field.find('div#hasil_pencarian li.autocomplete_active span#kodenya').html();
				var Produknya = Field.find('div#hasil_pencarian li.autocomplete_active span#produknya').html();
				var Harganya = Field.find('div#hasil_pencarian li.autocomplete_active span#harganya').html();

				Field.find('div#hasil_pencarian').hide();
				Field.find('input').val(Kodenya);

				$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(3)').html(Produknya);
				$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(4) input').val(Harganya);
				$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(4) span').html(to_rupiah(Harganya));
				$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(5) input').removeAttr('disabled').val(1);
				$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(6) input').val(Harganya);
				$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(6) span').html(to_rupiah(Harganya));

				var IndexIni = $(this).parent().parent().index() + 1;
				var TotalIndex = $('#TabelTransaksi tbody tr').length;
				if (IndexIni == TotalIndex) {
					BarisBaru();

					$('html, body').animate({
						scrollTop: $(document).height()
					}, 0);
				} else {
					$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(5) input').focus();
				}
			} else {
				AutoCompleteGue($(this).width(), $(this).val(), $(this).parent().parent().index());
			}
		} else {
			$('#TabelTransaksi tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
		}

		HitungTotalBayar();
	});

	$(document).on('click', '#daftar-autocomplete li', function() {
		$(this).parent().parent().parent().find('input').val($(this).find('span#kodenya').html());

		var Indexnya = $(this).parent().parent().parent().parent().index();
		var NamaProduk = $(this).find('span#produknya').html();
		var Harganya = $(this).find('span#harganya').html();

		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(3)').html(NamaProduk);
		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val(Harganya);
		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(4) span').html(to_rupiah(Harganya));
		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').removeAttr('disabled').val(1);
		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(Harganya);
		$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(to_rupiah(Harganya));

		var IndexIni = Indexnya + 1;
		var TotalIndex = $('#TabelTransaksi tbody tr').length;
		if (IndexIni == TotalIndex) {
			BarisBaru();
			$('html, body').animate({
				scrollTop: $(document).height()
			}, 0);
		} else {
			$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').focus();
		}

		HitungTotalBayar();
	});

	$(document).on('keyup', '#jumlah_beli', function() {
		var Indexnya = $(this).parent().parent().index();
		var Harga = $('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val();
		var JumlahBeli = $(this).val();
		var KodeProduk = $('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(2) input').val();

		$.ajax({
			url: "<?php echo site_url('produk/cek-stok'); ?>",
			type: "POST",
			cache: false,
			data: "kode_produk=" + encodeURI(KodeProduk) + "&stok=" + JumlahBeli,
			dataType: 'json',
			success: function(data) {
				if (data.status == 1) {
					var SubTotal = parseInt(Harga) * parseInt(JumlahBeli);
					if (SubTotal > 0) {
						var SubTotalVal = SubTotal;
						SubTotal = to_rupiah(SubTotal);
					} else {
						SubTotal = '';
						var SubTotalVal = 0;
					}

					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(SubTotalVal);
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(SubTotal);
					HitungTotalBayar();
				}
				if (data.status == 0) {
					$('.modal-dialog').removeClass('modal-lg');
					$('.modal-dialog').addClass('modal-md');
					$('#ModalHeader').html('Oops !');
					$('#ModalContent').html(data.pesan);
					$('#ModalFooter').html("<button type='button' class='btn btn-danger' data-dismiss='modal' autofocus> Close </button>");
					$('#ModalGue').modal('show');

					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').val('1');
					
					//Jadikan qty 1 dan hitung ulang
					var SubTotal = parseInt(Harga) * 1;
					if (SubTotal > 0) {
						var SubTotalVal = SubTotal;
						SubTotal = to_rupiah(SubTotal);
					} else {
						SubTotal = '';
						var SubTotalVal = 0;
					}

					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(SubTotalVal);
					$('#TabelTransaksi tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(SubTotal);
					HitungTotalBayar();
				}
			}
		});
	});

	$(document).on('keydown', '#jumlah_beli', function(e) {
		var charCode = e.which || e.keyCode;
		if (charCode == 9) {
			var Indexnya = $(this).parent().parent().index() + 1;
			var TotalIndex = $('#TabelTransaksi tbody tr').length;
			if (Indexnya == TotalIndex) {
				BarisBaru();
				return false;
			}
		}

		HitungTotalBayar();
	});

	$(document).on('keyup', '#UangCash', function() {
		HitungTotalKembalian();
	});

	function HitungTotalBayar() {
		var Total = 0;
		$('#TabelTransaksi tbody tr').each(function() {
			if ($(this).find('td:nth-child(6) input').val() > 0) {
				var SubTotal = $(this).find('td:nth-child(6) input').val();
				Total = parseInt(Total) + parseInt(SubTotal);
			}
		});

		$('#TotalBayar').html(to_rupiah(Total));
		$('#TotalBayarHidden').val(Total);

		$('#UangCash').val('');
		$('#UangKembali').val('');
	}

	function HitungTotalKembalian() {
		var Cash = $('#UangCash').val();
		var TotalBayar = $('#TotalBayarHidden').val();

		
		if (parseInt(Cash) >= parseInt(TotalBayar)) {
			var Selisih = parseInt(Cash) - parseInt(TotalBayar);
			$('#UangKembali').val(to_rupiah(Selisih));
		} else {
			$('#UangKembali').val('');
		}
	}

	function to_rupiah(angka) {
		var rev = parseInt(angka, 10).toString().split('').reverse().join('');
		var rev2 = '';
		for (var i = 0; i < rev.length; i++) {
			rev2 += rev[i];
			if ((i + 1) % 3 === 0 && i !== (rev.length - 1)) {
				rev2 += '.';
			}
		}
		return 'Rp. ' + rev2.split('').reverse().join('');
	}

	function check_int(evt) {
		var charCode = (evt.which) ? evt.which : event.keyCode;
		return (charCode >= 48 && charCode <= 57 || charCode == 8);
	}

	$(document).on('keydown', 'body', function(e) {
		var charCode = (e.which) ? e.which : event.keyCode;

		if (charCode == 118) //F7
		{
			BarisBaru();
			return false;
		}

		if (charCode == 119) //F8
		{
			$('#UangCash').focus();
			return false;
		}

		if (charCode == 120) //F9
		{
			CetakStruk();
			return false;
		}

		if (charCode == 121) //F10
		{
			var Cash = $('#UangCash').val().replace(/[^\d]/g, '');
			var TotalBayar = $('#TotalBayarHidden').val();

			if (parseInt(Cash) < parseInt(TotalBayar)) {
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('');
				$('#ModalContent').html("insufficient payment !");
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
				$('#ModalGue').modal('show');
			} else {
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('');
				$('#ModalContent').html("Want to save transaction ?");
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Save</button><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
				$('#ModalGue').modal('show');

				setTimeout(function() {
					$('button#SimpanTransaksi').focus();
				}, 500);
			}

			return false;
		}
	});

	$(document).on('click', '#Simpann', function() {
		var Cash = $('#UangCash').val().replace(/[^\d]/g, '');
		var TotalBayar = $('#TotalBayarHidden').val();

		if (parseInt(Cash) < parseInt(TotalBayar)) {
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('');
			$('#ModalContent').html("insufficient payment !");
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
			$('#ModalGue').modal('show');
		} else {
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('');
			$('#ModalContent').html("Want to save transaction ? ?");
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Save</button><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
			$('#ModalGue').modal('show');

			setTimeout(function() {
				$('button#SimpanTransaksi').focus();
			}, 500);
		}
	
	});

	$(document).on('click', 'button#SimpanTransaksi', function() {
		SimpanTransaksi();
	});

	$(document).on('click', 'button#CetakStruk', function() {
		CetakStruk();
	});

	function SimpanTransaksi() {
		var FormData = "nomor_nota=" + encodeURI($('#nomor_nota').val());
		FormData += "&tanggal=" + encodeURI($('#tanggal').val());
		FormData += "&id_kasir=" + $('#id_kasir').val();
		FormData += "&id_pelanggan=" + $('#id_pelanggan').val();
		FormData += "&" + $('#TabelTransaksi tbody input').serialize();
		FormData += "&cash=" + $('#UangCash').val();
		FormData += "&catatan=" + encodeURI($('#catatan').val());
		FormData += "&grand_total=" + $('#TotalBayarHidden').val();

		$.ajax({
			url: "<?php echo site_url('penjualan/transaksi'); ?>",
			type: "POST",
			cache: false,
			data: FormData,
			dataType: 'json',
			success: function(data) {
				if (data.status == 1) {
					alert(data.pesan);
					window.location.href = "<?php echo site_url('penjualan/transaksi'); ?>";
				} else {
					$('.modal-dialog').removeClass('modal-lg');
					$('.modal-dialog').addClass('modal-sm');
					$('#ModalHeader').html('Oops !');
					$('#ModalContent').html(data.pesan);
					$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Save</button>");
					$('#ModalGue').modal('show');
				}
			}
		});
	}

	$(document).on('click', '#TambahPelanggan', function(e) {
		e.preventDefault();

		$('.modal-dialog').removeClass('modal-sm');
		$('.modal-dialog').removeClass('modal-lg');
		$('#ModalHeader').html('Tambah Pelanggan');
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});

	function CetakStruk() {
		if ($('#TotalBayarHidden').val() > 0) {
			if ($('#UangCash').val() !== '') {
				var FormData = "nomor_nota=" + encodeURI($('#nomor_nota').val());
				FormData += "&tanggal=" + encodeURI($('#tanggal').val());
				FormData += "&id_kasir=" + $('#id_kasir').val();
				FormData += "&id_pelanggan=" + $('#id_pelanggan').val();
				FormData += "&" + $('#TabelTransaksi tbody input').serialize();
				FormData += "&cash=" + $('#UangCash').val();
				FormData += "&catatan=" + encodeURI($('#catatan').val());
				FormData += "&grand_total=" + $('#TotalBayarHidden').val();

				window.open("<?php echo site_url('penjualan/transaksi-cetak/?'); ?>" + FormData, '_blank');
			} else {
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('Oops !');
				$('#ModalContent').html('Pleace add total cash');
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
				$('#ModalGue').modal('show');
			}
		} else {
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html('Pleace add 1 product');
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>OK</button>");
			$('#ModalGue').modal('show');

		}
	}

	function formatCurrency(input) {
		let value = input.value.replace(/[^\d]/g, '');
		value = Number(value);
		value = numeral(value).format('0,0');
		input.value = value;
	}
</script>

<?php $this->load->view('include/navbar2'); ?>
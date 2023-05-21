<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>


<section class="content">
<div class="container-fluid">
	<div class="col-lg-12">
        <div class="card card-primary card-outline">
			<div class="card-header"> List Product </div>
			<div class="card-body">
			<?php if ($level != "kasir") { ?>
				<div style="margin-bottom:10px;">
					<div>
						<b style="color:red;">List red color = this product expired</b></font>
					</div>
				</div>
			<?php } ?>
			<div class='table-responsive'>
				<table id="my-grid" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Product Code</th>
							<th>Product Name</th>
							<th>Expired Date</th>
							<th>Category</th>
							<th>Size</th>
							<th>Price</th>
							<th>Stock</th>
							<?php if ($level == 'admin' or $level == 'inventory') { ?>
								<th class='no-sort'>Edit</th>
								<th class='no-sort'>Delete</th>
							<?php } ?>
						</tr>
					</thead>
				</table>
			</div>
			</div>
		</div>
	</div>
</div>
</section>


<?php
$tambahan = '';
if ($level == 'admin' or $level == 'inventory') {
	$tambahan .= nbs(2) . "<a href='" . site_url('produk/tambah') . "' class='btn btn-default' id='TambahProduk'><i class='fa fa-plus fa-fw'></i> Add Product</a>";
	$tambahan .= nbs(2) . "<span id='Notification' style='display: none;'></span>";
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js" integrity="sha512-r22gChDnGvBylk90+2e/ycr3RVrDi8DIOkIGNhJlKfuyQM4tIRAI062MaV8sfjQKYVGjOBaZBOA87z+IhZE9DA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css" />
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script src="<?php echo config_item('plugin'); ?>numeral/numeral.min.js"></script>
<script src="<?php echo config_item('js'); ?>jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#ModalGue').draggable({
			handle: '.modal-content'
		});

		var dataTable = $('#my-grid').DataTable({
			"serverSide": true,
			"stateSave": false,
			"bAutoWidth": false,
			"oLanguage": {
				"sSearch": "<i class='fa fa-search fa-fw'></i> Seacrh : ",
				"sLengthMenu": "Show &nbsp; _MENU_ &nbsp; entries <?php echo $tambahan; ?>",
				"sInfo": "Showing _START_ to _END_ of <b>_TOTAL_ entries</b>",
				"sInfoFiltered": "(difilter dari _MAX_ total data)",
				"sZeroRecords": "No data",
				"sEmptyTable": "No data",
				"sLoadingRecords": "Please wait...",
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"aaSorting": [
				[3, "desc"]
			],
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
			}, {
				"orderable": false,
				"targets": 0
			}],
			"sPaginationType": "simple_numbers",
			"iDisplayLength": 10,
			"aLengthMenu": [
				[10, 20, 50, 100, 150],
				[10, 20, 50, 100, 150]
			],
			"ajax": {
				url: "<?php echo site_url('produk/produk-json'); ?>",
				type: "post",
				error: function() {
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="10"><?php echo config_item('no_list_data'); ?></th></tr></tbody>');
					$("#my-grid_processing").css("display", "none");
				}
			},
			"createdRow": function(row, data, dataIndex) {
				var today = new Date();
				var rowDate = new Date(data[3]); // kolom 3 pada data adalah tanggal
				if (rowDate < today) {
					$('td', row).addClass('bg-danger');
				}
			}
		});
	});

	$(document).on('click', '#HapusProduk', function(e) {
		e.preventDefault();
		var Link = $(this).attr('href');

		$('.modal-dialog').removeClass('modal-xl');
		$('.modal-dialog').addClass('modal-md');
		$('#ModalHeader').html('');
		$('#ModalContent').html('Want to delete this product <b>' + $(this).parent().parent().find('td:nth-child(3)').html() + ' (' + $(this).parent().parent().find('td:nth-child(2)').html() + ')</b> ?');
		$('#ModalFooter').html("<button type='button' class='btn btn-success' id='YesDelete' data-url='" + Link + "'>Save</button><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
		$('#ModalGue').modal('show');
	});

	$(document).on('click', '#YesDelete', function(e) {
		e.preventDefault();
		$('#ModalGue').modal('hide');

		$.ajax({
			url: $(this).data('url'),
			type: "POST",
			cache: false,
			dataType: 'json',
			success: function(data) {
				$('#Notifikasi').html(data.pesan);
				$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
				$('#my-grid').DataTable().ajax.reload(null, false);
			}
		});
	});

	$(document).on('click', '#TambahProduk, #EditProduk', function(e) {
		e.preventDefault();
		if ($(this).attr('id') == 'TambahProduk') {
			$('.modal-dialog').removeClass('modal-xl');
			$('.modal-dialog').addClass('modal-xl');
			$('#ModalHeader').html('Add Product');
		}
		if ($(this).attr('id') == 'EditProduk') {
			$('.modal-dialog').removeClass('modal-xl');
			$('.modal-dialog').addClass('modal-md');
			$('#ModalHeader').html('Edit Product');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});

	$(document).on('keyup', '.kode_produk', function() {
		$(this).parent().find('span').html("");

		var Kode = $(this).val();
		var Indexnya = $(this).parent().parent().index();
		var Pass = 0;
		$('#TabelTambahProduk tbody tr').each(function() {
			if (Indexnya !== $(this).index()) {
				var KodeLoop = $(this).find('td:nth-child(2) input').val();
				if (KodeLoop !== '') {
					if (KodeLoop == Kode) {
						Pass++;
					}
				}
			}
		});

		if (Pass > 0) {
			$(this).parent().find('span').html("<font color='red'>Code is already</font>");
			$('#SimpanTambahProduk').addClass('disabled');
		} else {
			$(this).parent().find('span').html('');
			$('#SimpanTambahProduk').removeClass('disabled');

			$.ajax({
				url: "<?php echo site_url('produk/ajax-cek-kode'); ?>",
				type: "POST",
				cache: false,
				data: "kodenya=" + Kode,
				dataType: 'json',
				success: function(json) {
					if (json.status == 0) {
						$('#TabelTambahProduk tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('span').html(json.pesan);
						$('#SimpanTambahProduk').addClass('disabled');
					}
					if (json.status == 1) {
						$('#SimpanTambahProduk').removeClass('disabled');
					}
				}
			});
		}
	});
</script>

<?php $this->load->view('include/navbar2'); ?>
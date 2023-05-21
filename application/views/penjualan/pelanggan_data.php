<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>


<section class="content">
<div class="container-fluid">
	<div class="col-lg-12">
        <div class="card card-primary card-outline">
			<div class="card-header">
				<h3 class="card-title">
					<i class='fa fa-shopping-cart fa-fw'></i> Sales <i class='fa fa-angle-right fa-fw'></i> List Member 
				</h3>
			</div>
			<div class="card-body">

				<div class='table-responsive'>
					<table id="my-grid" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Name</th>
								<th>No Handphone</th>
								<th>Input Date</th>
								<?php if($level == 'admin' OR $level == 'kasir' OR $level == 'keuangan') { ?>
								<th class='no-sort'>Edit</th>
								<?php } ?>

								<?php if($level == 'admin') { ?>
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
if($level == 'admin' OR $level == 'kasir' OR $level == 'keuangan')
{
	$tambahan .= nbs(2)."<a href='".site_url('penjualan/tambah-pelanggan')."' class='btn btn-default' id='TambahPelanggan'><i class='fa fa-plus fa-fw'></i> Add Member</a>";
	$tambahan .= nbs(2)."<span id='Notifikasi' style='display: none;'></span>";
}
?>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var dataTable = $('#my-grid').DataTable( {
			"serverSide": true,
			"stateSave" : false,
			"bAutoWidth": true,
			"oLanguage": {
				"sSearch": "<i class='fa fa-search fa-fw'></i> Search : ",
				"sLengthMenu": "Show &nbsp; _MENU_ &nbsp; entries &nbsp;&nbsp; <?php echo $tambahan; ?>",
				"sInfo": "Showing _START_ of _END_ to <b>_TOTAL_ entries</b>",
				"sInfoFiltered": "(difilter dari _MAX_ total data)", 
				"sZeroRecords": "No Data", 
				"sEmptyTable": "No Data", 
				"sLoadingRecords": "Waiting For...", 
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"columnDefs": [ 
				{
					"targets": 'no-sort',
					"orderable": false,
				}
	        ],
			"sPaginationType": "simple_numbers", 
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]],
			"ajax":{
				url :"<?php echo site_url('penjualan/pelanggan-json'); ?>",
				type: "post",
				error: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		} );
	});
	
	$(document).on('click', '#HapusPelanggan', function(e){
		e.preventDefault();
		var Link = $(this).attr('href');

		$('.modal-dialog').removeClass('modal-lmd');
		$('.modal-dialog').addClass('modal-md');
		$('#ModalHeader').html('Delete Member');
		$('#ModalContent').html('Want to delete Member , <b style ="color:red;">'+$(this).parent().parent().find('td:nth-child(2)').html()+'</b> ?');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDeletePelanggan' data-url='"+Link+"'>Save</button><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
		$('#ModalGue').modal('show');
	});

	$(document).on('click', '#YesDeletePelanggan', function(e){
		e.preventDefault();
		$('#ModalGue').modal('hide');

		$.ajax({
			url: $(this).data('url'),
			type: "POST",
			cache: false,
			dataType:'json',
			success: function(data){
				$('#Notifikasi').html(data.pesan);
				$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
				$('#my-grid').DataTable().ajax.reload( null, false );
			}
		});
	});
	
	$(document).on('click', '#TambahPelanggan, #EditPelanggan', function(e){
		e.preventDefault();

		$('.modal-dialog').removeClass('modal-md');
		$('.modal-dialog').removeClass('modal-md');
		if($(this).attr('id') == 'TambahPelanggan')
		{
			$('#ModalHeader').html('Add Member');
		}
		if($(this).attr('id') == 'EditPelanggan')
		{
			$('#ModalHeader').html('Edit Member');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});
</script>

<?php $this->load->view('include/navbar2'); ?>
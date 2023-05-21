<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<section class="content">
<div class="container-fluid">
	<div class="col-lg-12">
        <div class="card card-primary card-outline">
			<div class="card-header"> List Categori Product </div>
			<div class="card-body">

			<div class='table-responsive'>
				<table id="my-grid" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Category Product</th>
							<?php if($level == 'admin' OR $level == 'inventory') { ?>
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
if($level == 'admin' OR $level == 'inventory')
{
	$tambahan .= nbs(2)."<a href='".site_url('produk/tambah-kategori')."' class='btn btn-default' id='TambahKategori'><i class='fa fa-plus fa-fw'></i> Add Category Product</a>";
	$tambahan .= nbs(2)."<span id='Notification' style='display: none;'></span>";
}
?>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var dataTable = $('#my-grid').DataTable( {
			"serverSide": true,
			"stateSave" : false,
			"bAutoWidth": true,
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
			"aaSorting": [[ 0, "asc" ]],
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
				url :"<?php echo site_url('produk/list-kategori-json'); ?>",
				type: "post",
				error: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="4"><?php echo config_item('no_list_data'); ?></th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		} );
	});
	
	$(document).on('click', '#HapusKategori', function(e){
		e.preventDefault();
		var Link = $(this).attr('href');

		$('.modal-dialog').removeClass('modal-md');
		$('.modal-dialog').addClass('modal-md');
		$('#ModalHeader').html('Konfirmasi');
		$('#ModalContent').html('Want to delete category <br /><b>'+$(this).parent().parent().find('td:nth-child(2)').html()+'</b> ?');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDeleteKategori' data-url='"+Link+"'>Save </button><button type='button' class='btn btn-default' data-dismiss='modal'> Cancel </button>");
		$('#ModalGue').modal('show');
	});

	$(document).on('click', '#YesDeleteKategori', function(e){
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

	$(document).on('click', '#TambahKategori, #EditKategori', function(e){
		e.preventDefault();

		$('.modal-dialog').addClass('modal-md');
		$('.modal-dialog').removeClass('modal-md');
		if($(this).attr('id') == 'TambahKategori')
		{
			$('#ModalHeader').html('Add Category Product');
		}
		if($(this).attr('id') == 'EditKategori')
		{
			$('#ModalHeader').html('Edit Category Product');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});
</script>

<?php $this->load->view('include/navbar2'); ?>
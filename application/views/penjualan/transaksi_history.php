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
					<i class='fa fa-shopping-cart fa-fw'></i> Sales <i class='fa fa-angle-right fa-fw'></i> History Transaction
				</h3>
			</div>
			<div class="card-body">

				<div class='table-responsive'>
					<table id="my-grid" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Date</th>
								<th>No Receipt</th>
								<th>Grand Total</th>
								<th>Member</th>
								<th>Note</th>
								<th>Cashier</th>
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
$tambahan = nbs(2)."<span id='Notifikasi' style='display: none;'></span>";
?>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var dataTable = $('#my-grid').DataTable( {
			"serverSide": true,
			"stateSave" : false,
			"bAutoWidth": true,
			"oLanguage": {
				"sSearch": "<i class='fa fa-search fa-fw'></i> Seacrh : ",
				"sLengthMenu": " Show &nbsp; _MENU_ &nbsp entries <?php echo $tambahan; ?>",
				"sInfo": "Showing _START_ of _END_ to <b>_TOTAL_ entries</b>",
				"sInfoFiltered": "(difilter dari _MAX_ total data)", 
				"sZeroRecords": "No data", 
				"sEmptyTable": "No data", 
				"sLoadingRecords": "Waiting...", 
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"aaSorting": [[ 1, "desc" ]],
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
				url :"<?php echo site_url('penjualan/history-json'); ?>",
				type: "post",
				error: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		} );
	});
	
	$(document).on('click', '#HapusTransaksi', function(e){
		e.preventDefault();
		var Link = $(this).attr('href');
		var Check = "<br /><hr style='margin:10px 0px 8px 0px;' /><div class='checkbox'><label><input type='checkbox' name='reverse_stok' value='yes' id='reverse_stok'> Return product stock </label></div>";
		$('.modal-dialog').removeClass('modal-md');
		$('.modal-dialog').addClass('modal-md');
		$('#ModalHeader').html('');
		$('#ModalContent').html('Want to delete this transaction <b>'+$(this).parent().parent().find('td:nth-child(3)').text()+'</b> ?' + Check);
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"' autofocus>Save</button><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
		$('#ModalGue').modal('show');
	});

	$(document).on('click', '#YesDelete', function(e){
		e.preventDefault();
		$('#ModalGue').modal('hide');

		var reverse_stok = 'no';
		if($('#reverse_stok').prop('checked')){
			var reverse_stok = 'yes';
		}

		$.ajax({
			url: $(this).data('url'),
			type: "POST",
			cache: false,
			data: "reverse_stok="+reverse_stok,
			dataType:'json',
			success: function(data){
				$('#Notifikasi').html(data.pesan);
				$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
				$('#my-grid').DataTable().ajax.reload( null, false );
			}
		});
	});

	$(document).on('click', '#LihatDetailTransaksi', function(e){
		e.preventDefault();
		var CaptionHeader = 'No Receipt ' + $(this).text();
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-lg');
		$('#ModalHeader').html(CaptionHeader);
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>");
		$('#ModalGue').modal('show');
	});
</script>

<?php $this->load->view('include/navbar2'); ?>
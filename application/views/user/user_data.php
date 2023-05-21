<!-- <?php $this->load->view('include/header'); ?> -->
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<!-- <div class="container"> -->

<section class="content">
<div class="container-fluid">
	<div class="col-lg-12">

        <div class="card card-primary card-outline">
		<div class="card-header"> List User 
		</div>
		<div class="card-body">

			<div class='table-responsive'>
				<table id="my-grid" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>No </th>
							<th>Username</th>
							<th>Fullname</th>
							<th>Access</th>
							<th>Status</th>
							<th class='no-sort'>Edit</th>
							<th class='no-sort'>Delete</th>
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
$tambahan = nbs(2)."<a href='".site_url('user/tambah')."' class='btn btn-default' id='TambahUser'><i class='fa fa-plus fa-fw'></i> Add User</a>";
$tambahan .= nbs(2)."<span id='Notifikasi' style='display: none;'></span>";
?>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var dataTable = $('#my-grid').DataTable( {
			"serverSide": true,
			"stateSave" : false,
			"bAutoWidth": false,
			"oLanguage": {
				"sSearch": "<i class='fa fa-search fa-fw'></i> Seacrh : ",
				"sLengthMenu": "Show &nbsp; _MENU_ &nbsp; entries <?php echo $tambahan; ?>",
				"sInfo": "Showing _START_ of _END_ to <b>_TOTAL_ entries</b>",
				"sInfoFiltered": "(difilter dari _MAX_ total data)", 
				"sZeroRecords": "No data", 
				"sEmptyTable": "No data", 
				"sLoadingRecords": "Please wait...", 
				"oPaginate": {
					"sPrevious": "Prev",
					"sNext": "Next"
				}
			},
			"aaSorting": [[ 0, "desc" ]],
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
				url :"<?php echo site_url('user/user-json'); ?>",
				type: "post",
				error: function(){ 
					$(".my-grid-error").html("");
					$("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
					$("#my-grid_processing").css("display","none");
				}
			}
		} );


	});
	
	$(document).on('click', '#HapusUser', function(e){
		e.preventDefault();
		var Link = $(this).attr('href');

		$('.modal-dialog').removeClass('modal-md');
		$('.modal-dialog').addClass('modal-md');
		$('#ModalHeader').html('');
		$('#ModalContent').html('Want to delete this user, <b>'+$(this).parent().parent().find('td:nth-child(3)').html()+'</b> ?');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"'>Save</button><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
		$('#ModalGue').modal('show');
	});

	$(document).on('click', '#YesDelete', function(e){
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

	$(document).on('click', '#TambahUser, #EditUser', function(e){
		e.preventDefault();
		if($(this).attr('id') == 'TambahUser')
		{
			$('.modal-dialog').removeClass('modal-md');
			$('.modal-dialog').addClass('modal-md');
			$('#ModalHeader').html('Add User');
		}
		if($(this).attr('id') == 'EditUser')
		{
			$('.modal-dialog').removeClass('modal-md');
			$('.modal-dialog').addClass('modal-md');
			$('#ModalHeader').html('Edit User');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});
</script>

<?php $this->load->view('include/navbar2'); ?>
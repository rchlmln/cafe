<!-- <?php $this->load->view('include/header'); ?> -->
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<section class="content">
<div class="container-fluid">
	<div class="col-lg-12">
        <div class="card card-primary card-outline">
			<div class="card-header"> Report Sales </div>
			<div class="card-body">

				<?php echo form_open('laporan', array('id' => 'FormLaporan')); ?>
				<div class="row">
					<div class="col-sm-5">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-4 control-label">From Date</label>
								<div class="col-sm-8">
									<input type='text' name='from' class='form-control' id='tanggal_dari' value="<?php echo date('Y-m-d'); ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-4 control-label">To Date</label>
								<div class="col-sm-8">
									<input type='text' name='to' class='form-control' id='tanggal_sampai' value="<?php echo date('Y-m-d'); ?>">
								</div>
							</div>
						</div>
					</div>
				</div>	

				<div class='row'>
					<div class="col-sm-5">
						<div class="form-horizontal">
							<div class="form-group">
								<div class="col-sm-4"></div>
								<div class="col-sm-8">
									<button type="submit" class="btn btn-primary" style='margin-left: 0px;'>Show</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>

				<br />
				<div id='result'></div>
			</div>
		</div>
	</div>
</div>
</section>

<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css"/>
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script>
$('#tanggal_dari').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});
$('#tanggal_sampai').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});

$(document).ready(function(){
	$('#FormLaporan').submit(function(e){
		e.preventDefault();

		var TanggalDari = $('#tanggal_dari').val();
		var TanggalSampai = $('#tanggal_sampai').val();

		if(TanggalDari == '' || TanggalSampai == '')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('');
			$('#ModalContent').html("Please add data !");
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
			$('#ModalGue').modal('show');
		}
		else
		{
			var URL = "<?php echo site_url('laporan/penjualan'); ?>/" + TanggalDari + "/" + TanggalSampai;
			$('#result').load(URL);
		}
	});
});
</script>

<?php $this->load->view('include/navbar2'); ?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='<?php echo config_item('img'); ?>favicon.png' type='image/x-icon' rel='shortcut icon'>
	<title>Cafe Bahagia</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="<?php echo config_item('font_awesome_adminlte'); ?>css/all.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo config_item('plugin'); ?>datatables-bs4/css/dataTables.bootstrap4.min.css"></link>
	<link rel="stylesheet" href="<?php echo config_item('plugin'); ?>datatables-responsive/css/responsive.bootstrap4.min.css"></link>
	<link rel="stylesheet" href="<?php echo config_item('plugin'); ?>datatables-buttons/css/buttons.bootstrap4.min.css"></link>
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo config_item('css_adminlte'); ?>adminlte.min.css">
	<!-- IonIcons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

		
	<!-- jQuery -->
	<script src="<?php echo config_item('plugin'); ?>jquery/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="<?php echo config_item('plugin'); ?>bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- AdminLTE -->
	<script src="<?php echo config_item('js_adminlte'); ?>adminlte.js"></script>


	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="<?php echo config_item('js_adminlte'); ?>pages/dashboard3.js"></script>
	<!-- <link href="<?php echo config_item('bootstrap'); ?>css/bootstrap.min.css" rel="stylesheet"> -->
	<!-- <link href="<?php echo config_item('bootstrap'); ?>css/bootstrap-theme.min.css" rel="stylesheet"> -->
	<link href="<?php echo config_item('font_awesome'); ?>css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo config_item('css'); ?>style-gue.css" rel="stylesheet">

</head>


<body class="login-page" style="min-height: 496.781px;">
    <div class="login-box">

        <div class="card">
        	<div class="card-header">
                <p class="login-box-msg">Login Cafe Bahagia Online</p>
        	</div>
            <div class="card-body login-card-body">

                <!-- <p class="login-box-msg">Under Maintenance to 12:00</p> -->
                
                <div id='ResponseInput'></div>
                <div class="input-group mb-3"></div>
                        
                <?php echo form_open('secure', array('id' => 'FormLogin')); ?>
				<div class="input-group mb-3">
					<?php
					echo form_input(array(
						'name' => 'username',
						'class' => 'form-control',
						'autocomplete' => 'off',
						'autofocus' => 'autofocus',
						'placeholder' => 'username'
					));
					?>
					<div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
				</div>
				<div class="input-group mb-3">
					<?php
					echo form_input(array(
						'name' => 'password',
						'class' => 'form-control',
						'type' => 'password',
						'id' => 'InputPassword',
						'placeholder' => 'password'
					));
					?>
					<div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
				</div>

				<button type="submit" class="btn btn-primary" style="float: right;">
					<span aria-hidden="true"></span> Login
				</button>
				<!-- <button type="reset" class="btn btn-default" id='ResetData'>Reset</button> -->
				<?php echo form_close(); ?>

            </div>

        </div>
    </div>
</body>

<script>
	$(function() {
		//------------------------Proses Login Ajax-------------------------//
		$('#FormLogin').submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: $(this).attr('action'),
				type: "POST",
				cache: false,
				data: $(this).serialize(),
				dataType: 'json',
				success: function(json) {
					//response dari json_encode di controller

					if (json.status == 1) {
						window.location.href = json.url_home;
					}
					if (json.status == 0) {
						$('#ResponseInput').html(json.pesan);
					}
					if (json.status == 2) {
						$('#ResponseInput').html(json.pesan);
						$('#InputPassword').val('');
					}
				}
			});
		});

		//-----------------------Ketika Tombol Reset Diklik-----------------//
		$('#ResetData').click(function() {
			$('#ResponseInput').html('');
		});
	});
</script>

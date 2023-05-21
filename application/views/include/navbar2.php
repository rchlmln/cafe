        </div>    
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- <script src="<?php echo config_item('bootstrap'); ?>js/bootstrap.min.js"></script> -->
    <!-- <div class="modal" id="ModalGue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class='fa fa-times-circle'></i></button>
                    <h4 class="modal-title" id="ModalHeader"></h4>
                </div>
                <div class="modal-body" id="ModalContent"></div>
                <div class="modal-footer" id="ModalFooter"></div>
            </div>
        </div>
    </div> -->

    <div class="modal fade in" id="ModalGue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ModalHeader"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="ModalContent"></div>
                <div class="modal-footer" id="ModalFooter"></div>
            
            </div>  
        </div>
    </div>


<script type="text/javascript">
    $('#ModalGue').on('hide.bs.modal', function () {
           setTimeout(function(){ 
                $('#ModalHeader, #ModalContent, #ModalFooter').html('');
           }, 500);
        });
    
    $(document).on('click', '#GantiPass', function(e){
        e.preventDefault();

        $('.modal-dialog').removeClass('modal-md');
        $('.modal-dialog').addClass('modal-md');
        $('#ModalHeader').html('Change Password');
        $('#ModalContent').load($(this).attr('href'));
        $('#ModalGue').modal('show');
    });
</script>


</body>
</html>
<!--
*
*  JI-LIFE HOME PAGE
*
-->

<?php include 'common/header.php'; ?>


<div class="row">
    <div class="col-lg-12">
    
    </div>
</div>


<?php include 'common/scripts.php'; ?>

<script type="text/javascript">
    require(['jquery', 'toastr'], function ($, toastr) {
        $(document).ready(function () {
            setTimeout(function () {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000
                };
                toastr.success('A Web Service For All JIers', 'Welcome to JI-Life');
            }, 1000);
        });
    });
</script>


<?php include 'common/footer.php'; ?>


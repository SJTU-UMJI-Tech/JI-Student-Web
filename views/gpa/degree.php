<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">
    <div class="alert alert-warning">
        The Degree Progress Check Sheet is simple and naive now!
        I will improve the AI later. (version alpha.2.1)
    </div>


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<script type="text/javascript">
    
    initJIFramework('ji/gpa/degree', <?php echo $data;?>);
    
</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


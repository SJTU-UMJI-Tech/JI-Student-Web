<?php include 'header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight
<?php echo isset($article) && $article ? 'article' : ''; ?>">


</div>

<?php include 'scripts.php'; ?>

<!-- JI Framework -->
<script type="text/javascript">
    
    initJIFramework('<?php echo isset($js) ? $js : '';;?>', <?php echo isset($data) ? $data : '{}';  ?>);

</script>


<?php include 'footer.php'; ?>



<!-- footer -->
<div class="footer">
    <span>
        <strong>Copyleft</strong> UM-SJTU JI Student Life 2016-2017
    </span>
    <span class="pull-right">
        developed by <strong>Peng</strong>, <strong>Zhuangzhuang</strong>,
        <strong>tc-imba</strong> and <strong>AuroraZK</strong>
    </span>
</div>

<!-- Close the div in header.php -->
</div>
</div>

<!-- requireJS -->
<script src="<?php echo ROOT_DIR ?>/node_modules/requirejs/require.js"></script>

<script src="<?php echo ROOT_DIR ?>/js/ji/app<?php echo ENVIRONMENT == 'production' ? '.min' : ''; ?>.js"></script>

<script type="text/javascript">
    
    var rootUrl = "<?php echo ROOT_DIR; ?>/";
    initJIRequire(rootUrl);

</script>
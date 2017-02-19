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
<script src="<?php echo ROOT_DIR; ?>/node_modules/requirejs/require.js"></script>

<?php if (ENVIRONMENT == 'production'): ?>
    <script src="<?php echo ROOT_DIR; ?>/js/app.production.min.js?v=<?php echo time(); ?>"></script>
<?php else: ?>
    <script src="<?php echo ROOT_DIR; ?>/js/app.development.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>

<!-- Initialize -->
<script type="text/javascript">
    
    // Pace should be loaded as early as possible
    require(['pace'], function (pace) {
        pace.start();
    });
    // Bootstrap 4 needs globalized Tether
    require(['Tether'], function (Tether) {
        window.Tether = Tether
    });
    
    require(['ji/app'], function (app) {
        var config = {
            navbar: <?php echo isset($navbar_data) ? $navbar_data : '{}';?>,
            url   : {
                profile: 'user/profile',
                login  : 'user/login?uri=<?php echo $this->Site_model->get_relative_url(); ?>',
                logout : 'user/logout'
            }
        };
        <?php if ($this->Site_model->is_login()):?>
        config.info = {
            avatar   : '<?php echo $this->Site_model->get_avatar(); ?>',
            user_name: '<?php echo $_SESSION['user_name']; ?>',
            user_type: '<?php echo $_SESSION['user_type']; ?>',
        };
        <?php endif;?>
        app(config);
    });

</script>
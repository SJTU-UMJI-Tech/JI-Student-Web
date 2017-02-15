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
    require(['pace'], function (pace) {
        pace.start();
    });
    require(['Tether'], function (Tether) {
        window.Tether = Tether;
    });
    require(['jquery', 'handlebars.runtime', 'templates/common/navbar'], function ($, Handlebars, template) {
        Handlebars.registerHelper('root_dir', function () {
            return new Handlebars.SafeString(window.ROOT_DIR);
        });
        var config = {
            navbar: <?php echo isset($navbar_data) ? $navbar_data : '{}';?>,
        };
        <?php if ($this->Site_model->is_login()):?>
        config.login     = true;
        config.avatar    = '<?php echo $this->Site_model->get_avatar(); ?>';
        config.user_name = '<?php echo $_SESSION['user_name']; ?>';
        config.user_type = '<?php echo $_SESSION['user_type']; ?>';
        config.profile   = '<?php echo base_url('/user/profile'); ?>';
        <?php endif;?>
        $("#ji-navbar").html(template(config));
    });
    require(['inspinia']);
</script>
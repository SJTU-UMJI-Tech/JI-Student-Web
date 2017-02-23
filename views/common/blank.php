<!DOCTYPE html>
<html>

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- If page name is not defined, use a default one -->
    <title><?php echo isset($page_name) ? $page_name : 'UM-SJTU JI LIFE'; ?></title>
    
    <link rel="shortcut icon" href="<?php echo ROOT_DIR; ?>/img/favicon.png">
    
    <script type="text/javascript">
        window.ROOT_DIR        = '<?php echo ROOT_DIR; ?>';
        window.BASE_URL        = '<?php echo base_url(); ?>';
        window.JS_SUFFIX       = '<?php echo ENVIRONMENT == 'production' ? '.min' : ''; ?>';
        window.initJIFramework = function (filename, options) {
            require([filename], function (instance) {
                instance(options);
            });
        }
    </script>
    
    <style>
        html, body{
            height: 100%
        }
    </style>

</head>

<body>

<script src="<?php echo ROOT_DIR; ?>/node_modules/requirejs/require.js"></script>

<?php if (ENVIRONMENT == 'production'): ?>
    <script src="<?php echo ROOT_DIR; ?>/js/app.production.min.js?v=<?php echo time(); ?>"></script>
<?php elseif (ENVIRONMENT == 'testing'): ?>
    <script src="<?php echo ROOT_DIR; ?>/js/app.testing.min.js?v=<?php echo time(); ?>"></script>
<?php else: ?>
    <script src="<?php echo ROOT_DIR; ?>/js/app.development.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>

<!-- JI Framework -->
<script type="text/javascript">
    
    initJIFramework('<?php echo isset($js) ? $js : '';;?>', <?php echo isset($data) ? $data : '{}';  ?>);

</script>

</body>
</html>
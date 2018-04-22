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
<?php elseif (ENVIRONMENT == 'testing'): ?>
    <script src="<?php echo ROOT_DIR; ?>/js/app.testing.min.js?v=<?php echo time(); ?>"></script>
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
    //require(['Tether'], function (Tether) {
    //    window.Tether = Tether
    //});

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

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download" data-size="{%=file.size%}" data-type="{%=file.type%}">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
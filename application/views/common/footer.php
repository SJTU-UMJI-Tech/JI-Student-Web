<div class="container">
	<br>
	<hr>
	<p>UM-SJTU JI Student Life | developed by <a
				href="http://ji.sjtu.edu.cn/student/org_member.php?org=Advising&memberID=5123709184">Peng</a>,
		Zhuangzhuang and tc-imba</p>
</div>

</body>

<script src="//cdn.bootcss.com/require.js/2.2.0/require.min.js"></script>

<script type="text/javascript">
	var paths = {
	<?php if (ENVIRONMENT == 'production'): ?>
		jquery: '//cdn.bootcss.com/jquery/3.1.0/jquery.min',
		tether: '//cdn.bootcss.com/tether/1.3.3/js/tether.min',
		bootstrap: '//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min',
		bootstrapHoverDropdown: '//cdn.bootcss.com/bootstrap-hover-dropdown/2.2.1/bootstrap-hover-dropdown.min',
		moment: '//cdn.bootcss.com/moment.js/2.14.1/moment.min',
		fullCalendar: '//cdn.bootcss.com/fullcalendar/2.9.1/fullcalendar.min',
		marked: '//cdn.bootcss.com/marked/0.3.6/marked.min',
	<?php else: ?>
		jquery: 'lib/jquery-3.1.0.min',
		tether: 'lib/tether-1.3.3.min',
		bootstrap: 'lib/bootstrap-4.0.0-alpha.3.min',
		bootstrapHoverDropdown: 'lib/bootstrap-hover-dropdown-2.2.1.min',
		moment: 'lib/moment-2.14.1.min',
		fullCalendar: 'lib/fullcalendar-2.9.1.min',
		marked: 'lib/marked-0.3.6.min',
	<?php endif; ?>
		prettify: 'lib/prettify-r298.min',
		raphael: 'lib/raphael-2.2.1.min',
		underscore: 'lib/underscore-1.8.3.min',
		flowchart: "lib/flowchart-1.3.4.min",
		jqueryflowchart: "lib/jquery.flowchart.min",
		sequenceDiagram: "lib/sequence-diagram-1.0.6.min",
		katex: "lib/katex-0.6.0.min",
		editormd: "../vendors/editor.md-1.5.0/editormd.amd.min",
		'jquery.cookie': 'lib/jquery.cookie-1.4.1.min',
		'jquery.md5': 'lib/jquery.md5',
		'jquery.ui.widget': 'lib/jquery.ui.widget',
		'jquery.iframe-transport': 'lib/jquery.iframe-transport',
		'jquery.fileupload': 'lib/jquery.fileupload',
		'jquery.fileupload-ui': 'lib/jquery.fileupload-ui',
		'jquery.fileupload-audio': 'lib/jquery.fileupload-audio',
		'jquery.fileupload-image': 'lib/jquery.fileupload-image',
		'jquery.fileupload-validate': 'lib/jquery.fileupload-validate',
		'jquery.fileupload-video': 'lib/jquery.fileupload-video',
		'jquery.fileupload-process': 'lib/jquery.fileupload-process',
		
		bootstrapDatetimepicker: 'lib/bootstrap-datetimepicker-2.3.8',
		'bootstrap-treeview': 'lib/bootstrap-treeview-1.2.0.min',
		
		
		'load-image': 'lib/load-image-2.6.1.min',
		'load-image-meta': 'lib/load-image-meta-2.6.1.min',
		'load-image-exif': 'lib/load-image-exif-2.6.1.min',
		'tmpl': 'lib/tmpl.min',
		'canvas-to-blob': 'lib/canvas-to-blob.min'
		
	};
	
	requirejs.config({
		baseUrl: "..<?php echo ROOT_DIR; ?>/js/",
		shim: {
			bootstrap: ['jquery', 'tether'],
			bootstrapHoverDropdown: ['jquery', 'bootstrap'],
			fullCalendar: ['moment'],
			flowchart: ['raphael'],
			sequenceDiagram: ['raphael', 'underscore'],
			jqueryflowchart: ['flowchart'],
			'jquery.md5': ['jquery'],
			//bootstrapDatetimepicker: ['jquery', 'bootstrap']
		},
		paths: paths,
		waitSeconds: 30
	});
	require(['jquery', 'tether', 'raphael'], function ($, tether, raphael)
	{
		window.Tether = tether;
		window.Raphael = raphael;
	});
	require(['jquery', 'bootstrap', 'bootstrapHoverDropdown'], function ($)
	{
		//$("[data-hover='dropdown']").dropdownHover();
	});
</script>
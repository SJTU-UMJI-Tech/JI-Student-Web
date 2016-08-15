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
	var paths;
	<?php if (ENVIRONMENT == 'production'): ?>
	paths = {
		jquery: '//cdn.bootcss.com/jquery/3.1.0/jquery.min',
		tether: '//cdn.bootcss.com/tether/1.3.3/js/tether.min',
		bootstrap: '//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min',
		bootstrapHoverDropdown: '//cdn.bootcss.com/bootstrap-hover-dropdown/2.2.1/bootstrap-hover-dropdown.min',
		moment: '//cdn.bootcss.com/moment.js/2.14.1/moment.min',
		fullCalendar: '//cdn.bootcss.com/fullcalendar/2.9.1/fullcalendar.min',
		marked: '//cdn.bootcss.com/marked/0.3.6/marked.min',
	};
	<?php else: ?>
	paths = {
		jquery: './lib/jquery-3.1.0.min',
		tether: './lib/tether-1.3.3.min',
		bootstrap: './lib/bootstrap-4.0.0-alpha.3.min',
		bootstrapHoverDropdown: './lib/bootstrap-hover-dropdown-2.2.1.min',
		bootstrapDatetimepicker: './lib/bootstrap-datetimepicker-2.3.8',
		moment: './lib/moment-2.14.1.min',
		fullCalendar: './lib/fullcalendar-2.9.1.min',
		marked: './lib/marked-0.3.6.min',
		prettify: './lib/prettify-r298.min',
		raphael: './lib/raphael-2.2.1.min',
		underscore: './lib/underscore-1.8.3.min',
		flowchart: "./lib/flowchart-1.3.4.min",
		jqueryflowchart: "./lib/jquery.flowchart.min",
		sequenceDiagram: "./lib/sequence-diagram-1.0.6.min",
		katex: "./lib/katex-0.6.0.min",
		editormd: "../vendors/editor.md-1.5.0/editormd.amd.min",
		jqueryCookie: './lib/jquery.cookie-1.4.1.min',
		jqueryMD5: './lib/jquery.md5'
	};
	<?php endif; ?>
	requirejs.config({
		baseUrl: "../js/",
		shim: {
			bootstrap: ['jquery', 'tether'],
			bootstrapHoverDropdown: ['bootstrap'],
			fullCalendar: ['moment'],
			flowchart: ['raphael'],
			sequenceDiagram: ['raphael', 'underscore'],
			jqueryflowchart: ['flowchart'],
			jqueryMD5: ['jquery'],
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
	require(['bootstrap', 'bootstrapHoverDropdown']);
</script>
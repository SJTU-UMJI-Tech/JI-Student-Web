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
		jquery: '/vendors/jquery-3.1.0/dist/jquery.min',
		tether: '/vendors/tether-1.3.3/dist/js/tether.min',
		bootstrap: '/vendors/bootstrap-4.0.0-alpha.3/dist/js/bootstrap.min',
		bootstrapHoverDropdown: '/vendors/bootstrap-hover-dropdown-2.2.1/bootstrap-hover-dropdown.min',
		moment: '/vendors/moment-2.14.1/min/moment.min',
		fullCalendar: '/vendors/fullcalendar-2.9.1/dist/fullcalendar.min',
		marked: '/vendors/marked-0.3.6/marked.min',
		prettify: "prettify.min",
		raphael: "raphael.min",
		underscore: "underscore.min",
		flowchart: "flowchart.min",
		jqueryflowchart: "jquery.flowchart.min",
		sequenceDiagram: "sequence-diagram.min",
		katex: "//cdnjs.cloudflare.com/ajax/libs/KaTeX/0.1.1/katex.min",
		editormd: "/vendors/editor.md-1.5.0/editormd.amd"
	};
	<?php endif; ?>
	requirejs.config({
		baseUrl: "../js/",
		shim: {
			bootstrap: ['jquery', 'tether'],
			bootstrapHoverDropdown: ['bootstrap'],
			fullCalendar: ['moment']
		},
		paths: paths,
		waitSeconds: 30
	});
	require(['jquery', 'tether'], function ($, tether)
	{
		window.Tether = tether;
	});
	require(['bootstrap', 'bootstrapHoverDropdown']);
</script>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="shortcut icon" href="<?php echo ROOT_DIR; ?>/images/favicon.png">
	<title><?php echo $page_name; ?></title>
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">

</head>

<body>

<br>

<div class="container text-xs-center">
	<h4>俞黎明</h4>
	<h5>浙江黎明发动机零部件有限公司董事长</h5>
	<h5 id="score"></h5>
	<hr>
	<button id="btn" class="btn btn-outline-primary">VOTE!</button>
	<hr>
	<h5 id="text"></h5>
	<hr>
	<p>俞黎明，男，汉族，生于1963年7月，现任舟山市慈善总会副会长、浙江黎明发动机零部件有限公司董事长、总经理、党支部书记。
		
		该公司十分关心残疾人家庭子女的教育培养情况，每逢其子女中高考试，公司给其家长带薪陪考假，每年给予学费补助。在市慈善总会设立黎明慈善助学金，先后资助500名寒门学子完成大学学业计划。2014年开始，俞黎明同志分别在吉林大学、上海交通大学密西根学院各捐助500万元，助品学兼优的学生向更高层次发展，鼓励莘莘学子努力学习，他先后结对扶贫，资助社会生活困难残疾人、捐助老年协会、帮助癌症者康复、抗震赈灾、脑瘫儿康复中心、赞助母校计算机室及在上海交大设立学生中心等。历年来，俞黎明同志为慈善事业捐助累计2000余万元。被舟山市慈善总会授予“慈善献爱心荣誉单位”，“浙江慈善奖”（机构奖），曾获浙江省和国务院授予“扶残助残先进集体”称号；市“优秀福利企业”，“全国福利企业示范单位”，2016年6月被评为浙江省优秀共产党员。</p>
	<hr>
	<h5>Powered by <a href="https://github.com/tc-imba">tc-imba</a></h5>
</div>

<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function ()
	{
		$.ajax({
			type: "get",
			url: "http://vip.zjqq.mobi/index.php/vote/voteList.html?id=2346&page=5&num=5&_=" + (new Date()).getTime(),
			dataType: "jsonp",
			success: function (data)
			{
				console.log(data);
				$("#score").html('COUNT : '+data.list[4].vote.score);
			}
		});
		
		
		$("#btn").click(function ()
		{
			$.ajax({
				type: "get",
				url: "http://vip.zjqq.mobi/vote/vote?activity_id=2346&voteid=45229&_=" + (new Date()).getTime(),
				dataType: "jsonp",
				success: function (data)
				{
					console.log(data);
					if (data.status == 'error')
					{
						$("#text").html(data.msg);
					}
					else if (data.status == 'ok')
					{
						$("#text").html(data.msg + " count: " + data.count);
					}
				}
			});
		});
		
	});
</script>

</body>



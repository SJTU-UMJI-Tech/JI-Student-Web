<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div class="container">
	<input id="file" type="file" accept=".xls,.xlsx,.csv,.ods">
	<br><br>
	<div id="tex">
	</div>
</div>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	require(['jszip'], function (jszip)
	{
		window.JSZip = jszip;
		require(['jquery', 'xlsx'], function ($)
		{
			$(document).ready(function ()
			{
				var col2num = function (col)
				{
					var num = 0;
					for (var i = col.length - 1; i >= 0; i--)
					{
						num *= 26;
						num += col.charCodeAt(i) - 64;
					}
					return num;
				};
				
				var num2col = function (num)
				{
					var col = '';
					while (num != 0)
					{
						col = String.fromCharCode(num % 26 + 64) + col;
						num = Math.floor(num / 26);
					}
					return col;
				};
				
				var formcell = function (row_num, col_num)
				{
					return num2col(col_num) + row_num;
				};
				
				var generate_header = function (col)
				{
					var tex = [
						'\\begin{table}[H]\n',
						'\t\\centering\n',
						'\t\\begin{tabular}{|'
					].join('');
					for (var i = 0; i < col; i++)
					{
						tex += 'c|';
					}
					tex += '}\n';
					//console.log(tex);
					return tex;
				};
				
				var generate_body = function (worksheet, row, col)
				{
					var data = [], merge = [];
					var tex = '\t\\hline\n';
					for (var i = 1; i <= row; i++)
					{
						var line = [1];
						for (var j = 1; j <= col; j++)
						{
							var cell = worksheet[formcell(i, j)];
							if (cell)
							{
								line[j] = cell.v;
							}
						}
						data[i] = line;
						merge[i] = [];
					}
					if (worksheet.hasOwnProperty('!merges'))
					{
						for (var index in worksheet['!merges'])
						{
							var obj = worksheet['!merges'][index];
							merge[obj.s.r + 1][obj.s.c + 1] = obj;
							//console.log(obj);
						}
					}
					
					//console.log(data);
					
					for (var i = 1; i <= row; i++)
					{
						for (var j = 1; j <= col; j++)
						{
							if (data[i][j])
							{
								tex += '\t';
								if (merge[i][j])
								{
									var merge_obj = merge[i][j];
									var multi_row = merge_obj.e.r - merge_obj.s.r + 1;
									var multi_col = merge_obj.e.c - merge_obj.s.c + 1;
									for (var r = 1; r < multi_row && i + r <= row; r++)
									{
										data[i + r][0] = 0;
										for (var c = 0; c < multi_col; c++)
										{
											merge[i + r][j + c] = 1;
										}
									}
									if (multi_row > 1)
									{
										tex += '\\multirow{' + multi_row + '}{*}{';
									}
									if (multi_col > 1)
									{
										tex += '\t\\multicolumn{' + multi_col + '}{|c|}{' + data[i][j] + '}';
										j += multi_col;
									}
									else
									{
										tex += data[i][j];
									}
									if (multi_row > 1)
									{
										tex += '}';
									}
								}
								else
								{
									tex += data[i][j];
								}
							}
							if (j < col)
							{
								tex += '\t&';
							}
						}
						tex += '\t\\\\\n';
						if (i == row || data[i + 1][0] == 1)
						{
							tex += '\t\\hline\n';
						}
						else
						{
							var start = 1;
							for (var j = 1; j <= col; j++)
							{
								if (merge[i + 1][j])
								{
									if (start < j)
									{
										tex += '\t\\cline{' + start + '-' + (j - 1) + '}';
									}
									start = j + 1;
								}
							}
							if (start <= col)
							{
								tex += '\t\\cline{' + start + '-' + col + '}';
							}
							tex += '\n';
						}
					}
					return tex;
				};
				
				var generate_footer = function (name)
				{
					var tex = [
						'\t\\end{tabular}\n',
						'\t\\caption{', name, '}\n',
						'\\end{table}\n'
					].join('');
					//console.log(tex);
					return tex;
				};
				
				
				var xlsx2latex = function (worksheet, name)
				{
					var size = worksheet['!ref'];
					var reg = /^([a-zA-Z]+)(\d+):([a-zA-Z]+)(\d+)$/;
					var result = size.match(reg);
					var col = col2num(result[3]) - col2num(result[1]) + 1;
					var row = result[4] - result[2] + 1;
					//console.log(worksheet);
					var tex =
							generate_header(col) +
							generate_body(worksheet, row, col) +
							generate_footer(name);
					return tex;
				};
				
				$("#file").change(function (e)
				{
					var files = e.target.files;
					if (files.length > 0)
					{
						var container = $("#tex");
						container.html('');
						var file = files[0];
						var reader = new FileReader();
						reader.onload = function (e)
						{
							var data = e.target.result;
							var workbook = window.XLSX.read(data, {type: 'binary'});
							for (var index in workbook.SheetNames)
							{
								var name = workbook.SheetNames[index];
								var worksheet = workbook.Sheets[name];
								var tex = xlsx2latex(worksheet, name);
								var html = '<h5>' + name + '</h5><textarea  cols="100" style="resize: none;overflow-y:hidden;height:auto;"></textarea><br>';
								container.append(html);
								var textarea = container.children("textarea").last();
								textarea[0].innerHTML = tex;
								textarea[0].style.height = textarea[0].scrollHeight + 'px';
								//console.log(textarea[0].scrollHeight);
							}
							
						};
						reader.readAsBinaryString(file);
					}
					else
					{
						alert("Please input a file");
					}
				});
			});
			//console.log(XLSX);
			//
			//var workbook = new Excel.Workbook();
		});
	});

</script>

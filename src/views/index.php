<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

	<title>Convert excel - csv</title>
	<link rel="stylesheet" href="./css/style.css">
</head>

<body>
	<div class="container">
		<h2>Convert excel - csv</h2>
	</div>

	<div class="container clearfix">
		<p><?php echo $message?></p>

		<div class="box">
		<p>Convert excel to csv</p>
			<form action="./controller.php?method=convert_excel_to_csv" method="post" enctype="multipart/form-data">
				<input name="xlsxfile" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
				<p><input type="submit" value="convert"></p>
			</form>
		</div>

		<div class="box">
			<p>Convert csv to excel</p>
			<form action="./controller.php?method=convert_csv_to_excel" method="post" enctype="multipart/form-data">
				<input name="csvfile" type="file" accept="text/*">
				<p><input type="submit" value="convert"></p>
			</form>
		</div>
	</div>
</body>
</html>
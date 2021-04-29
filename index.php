<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@500&display=swap" rel="stylesheet">
<style>

.left {
	height: 70%;
	width: 45%;
	text-align: center;
	font-size: 40px;
	font-family: 'Roboto Slab', serif;
	float: left;
	border: 1px solid #000000;
	border-radius: 5px;
	margin: 2%;
	padding-top: 10%;
}

.right {
	height: 70%;
	width: 45%;
	font-size: 40px;
	text-align: center;
	font-family: 'Roboto Slab', serif;
	float: right;
	border: 1px solid #000000;
	border-radius: 5px;
	margin: 2%;
	padding-top: 10%;	
}

.btnstyle {
	height: 50%;
	width: 50%;
	font-size: 35px;
}

.btnstyle:hover {
	cursor: pointer;
}

</style>
</head>
<body>
<div class='left'>
<button class='btnstyle' onclick="location.href='sql.php'" type="button">SQL</button></div>
<div class='right'>
<button class='btnstyle' onclick="location.href='maria.php'" type="button">MARIADB</button></div>
</body>
</html>
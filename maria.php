<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@500&display=swap" rel="stylesheet">
<style>

.top {
	background-color: #568c5e;	
	color: white;
	height: 20%;
	width: 100%;
	text-align: center;
	font-size: 40px;
	line-height: 200%;
	font-family: 'Roboto Slab', serif;
}

.middle {
	background-color: white;
	height: 60%;
	width: 100%;
	overflow: hidden;
}

.api {
	float: left;
	width: 40%;
	height: 100%;
}

.sql {
	float: right;
	width: 40%;
	height: 100%;
}

.bottom {
	background-color: #086f17;
	height: 20%;
	width: 100%;
	font-size: 40px;
	text-align: center;
	line-height: 200%;
	font-family: 'Roboto Slab', serif;
}

</style>
</head>
<body>
<div class='top'>INVENTORY RECORDS</div>
<?php


$command = "curl -k -u emccumbe@us.ibm.com:fxhmuADQ6UvZPNqt3 'https://inventory.devit.ibm.com/api/v1/os_instances?cloud_name_EQ=Skyline&is_clean_EQ=0&LIMIT=20'";
$output = shell_exec($command);
$servers = json_decode($output, true);
echo "<div class='middle'>";
	echo "<div class='api'>GATHERED BY PHP";
	echo "<table>";

	$total = 0;
	$ip = 0;
	$contact = 0;
	$poweroncount = 0;
	$poweroffcount = 0;
	$csvary = array();
	$csvheader = "System, PowerState, IP, OS, Dev Contact, Missing\r\n";
	$csv = fopen ('dirtyrecords.csv', 'w');
	fwrite ($csv, $csvheader);
	foreach ($servers['records'] as $server) {
			//echo "<tr class='dirtyrow'>";
			if ($server['os_extras.hostname'] != "") {
				echo "<tr class='dirtyrow'><td><a href='https://inventory.devit.ibm.com/details/os_instances/" . $server['id'] . "' target='new_window'>" . $server['os_extras.hostname'] . "</a></td>";

			}
			else echo "<tr class='dirtyrow'><td><a href='https://inventory.devit.ibm.com/details/os_instances/" . $server['id'] . "' target='new_window'>" . $server['os_extras.hostname'] . "</a></td>";
			
			//CSV
			$csvary[0] = $server['os_extras.hostname'];
			//End CSV
							
			if ($server['primary_ip'] == null)  {
				echo "<td><font color='red'>IP Empty</font></td>";
				$flag = 1;
				$ip++;
				$csvary[2] = "Empty";
			}
			else {
				echo "<td><font color='green'>IP Clean</font></td>";
				$csvary[2] = "Clean";
			}
			

			if ($server['os_version_id'] == null)  {
				echo "<td><font color='red'>OS Empty</font></td>";
				$flag = 1;
				$csvary[3] = "Empty";
			}
			else {
				echo "<td><font color='green'>OS Clean</font></td>";
				$csvary[3] = "Clean";
			}

			if ($server['owner_email'] == null)  {
				echo "<td><font color='red'>Contact Empty</font></td>";
				$flag = 1;
				$contact++;
				$csvary[4] = "Empty";
			}
			else {
				echo "<td><font color='green'>Contact Clean</font></td>";
				$csvary[4] = "Clean";
			}
			if ($flag == 0) {
				echo "<td>" . $server['owner_email'] . "</td>";
				$csvary[5] = $server['owner_email'];
			}
			else {
				echo "<td>&nbsp;</td>";
				$csvary[5] = "None";
			}
			echo "</tr>";
			fputcsv ($csv, $csvary);
			$csvary = array();

	}
	echo "</table>";
	echo "</div>";
	
	
	echo "<div class='sql'>";
		echo "Database Tie-in";
		
		
		$host = getenv("MARIADB_SERVICE_HOST");
		$port = getenv("MARIADB_SERVICE_PORT");
		$user = getenv("databaseuser");
		$pass = getenv("databasepassword");
		$db = getenv("databasename");
		$con = mysqli_connect($host,$user,$pass);
		if (!$con) echo "Could not connect: " . mysqli_error();
		mysqli_select_db($con, "mariadb_eric");
		
		$create = mysqli_query($con, "SELECT 1 FROM demotable LIMIT 1");
		if ($create !== FALSE) {
			if (isset($_POST['newcomment'])) {
				$newval = $_POST['newcomment'];
				mysqli_query($con, "INSERT INTO demotable (Comment) VALUES ('$newval')") or die("Could not connect: " . mysqli_error()); ;
			}
	}
			else {
				mysqli_query($con, "CREATE TABLE demotable (Comment varchar(5000))");
				if (isset($_POST['newcomment'])) {
					$newval = $_POST['newcomment'];
					mysqli_query($con, "INSERT INTO demotable (Comment) VALUES ('$newval')") or die("Could not connect: " . mysqli_error()); ;
				}
			}	
				
			echo "<form action='' method='POST' id='comment' name='comment'>";
			echo "<textarea rows='15' cols='40' name='newcomment' form='comment' placeholder='Enter Text Here...'></textarea><br>";
			echo "<input type='submit' value='Submit'></form>";
			$request = mysqli_query($con, "SELECT Comment FROM demotable") or die("Could not connect: " . mysqli_error());
				while($row = mysqli_fetch_array($request)) {
					echo $row['Comment'] . "<br>";
				}
	echo "</div>";
echo "</div>";
?>
<div class='bottom'>AND COMMENTS...</div>
</body>
</html>
<?php
$url = $_GET['url'];
if($url != "")
{
	include("ref/mysql_connect.php");
	$command = "Select content, date from reports where url='".mysqli_real_escape_string($link,$url)."' order by date DESC;";
	$result = mysqli_query($link,$command);
	$count = 0;
	echo "<table>";
	echo "<tr>";
	echo "<td>";
    	echo "Reason";
	echo "</td>";
	echo "<td>";
	echo "Date";
	echo "</td>";
	echo "</tr>";
	while ($row = mysqli_fetch_assoc($result)) {
		$count++;
		echo "<tr>";
		echo "<td>";
	    	echo $row['content'];
		echo "</td>";
		echo "<td>";
		echo $row['date'];
		echo "</td>";
		echo "</tr>";
	}
	if ($count == 0)
	{
		echo "<tr>";
		echo "<td>";
	    	echo "NONE";
		echo "</td>";
		echo "<td>";
		echo "NONE";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";

	mysqli_close($link);
}

?>

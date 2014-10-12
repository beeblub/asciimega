<?php
//rename the file to mysql_connect.php after you inserted your database information
$link = mysqli_connect('localhost', 'USERNAME', 'USERPASSWORD','DATABASE');
if (!$link) {
    die('Im sorry but the database is down.');
}
?>

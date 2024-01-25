<?php
# connection details
$servername = "localhost";
$username = "Console";
$password = "ConsExclusive";
$tietokanta = "tietokanta";

$dbconnect = array($servername, $username, $password);

#connect to $servername with $username and $password
$conn = mysqli_connect($dbconnect[0], $dbconnect[1], $dbconnect[2]);

#set database as tietokanta
mysqli_select_db($conn, $tietokanta);

#set charset to utf-8
mysqli_set_charset($conn, "utf8");

#check charset
#echo "charset: " . mysqli_character_set_name($conn) . "\n";

#connection status
if (!$conn) {
    die("Yhteys tietokantaan epäonnistui <br><br>");
}
# onnistumisviesti
#echo "Yhteys tietokantaan onnistui <br><br>";
?>

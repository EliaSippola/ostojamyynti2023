<?php
session_start();
include("kantayhteys.php");
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

$poista = mysqli_real_escape_string($conn, $_POST['poista']);
$ilmoitus_id = mysqli_real_escape_string($conn, $_POST['ilmoitus_id']);

#onko poisto varmistettu, onko kirjauduttu sisään
if (isset($poista) && $poista == 1 && isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1) {
    #ie tarvetta suojaukselle, $ilmoitus_id ei ole käyttäjän muokattavissa
    mysqli_query($conn, "DELETE FROM ilmoitukset WHERE ilmoitukset_id = '$ilmoitus_id'");

    echo "Ilmoitus poistettu! <a href='index.php'>Palaa etusivulle</a>";
} elseif (!isset($_SESSION['LOGGEDIN']) || $_SESSION['LOGGEDIN'] !== 1) {
    echo "Et ole kirjautunut sisään!<br><br><a href='index.php'>Etusivulle</a> - <a href='kirjautuminen.html>Kirjaudu sisään</a>'";

#poistamisen varmistus
} else {
    $ilmoitus_nimi = $_POST['ilmoitus_nimi'];

    echo "Haluatko varmasti poistaa ilmoituksen " . $ilmoitus_nimi . "?<br>
    <tr><td><form action='poistailmoitus.php' method='post'><input type='hidden' name='poista' value='1'><input type='hidden' name='ilmoitus_id' value='$ilmoitus_id'>
    <input type='hidden' name='ilmoitus_nimi' value='$ilmoitus_nimi'><input type='submit' value='Kyllä' style='float: left;'>
    </form><form action='index.php' methos='post'><input type='submit' value='Ei' style='position: relative; left: 5px; bottom: 16px'></form></td></tr>";
}

mysqli_close($conn);
?>
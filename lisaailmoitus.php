<?php
session_start();
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#virheilmoitukset
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);

#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

$myyja_id = $_SESSION["kayttaja_id"];
$ilmoitus_aika = date("Y-m-d");

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1) {
    echo "<form action='ilmoitushallinta.php' method='post' class='form'>";
    echo "<h3>Lisää ilmoitus</h3>";
    echo "<p>Ilmoitustyyppi: <select name='ilmoitus_laji' class='IlmKysely'><option value='1'>Myydään</option><option value='2'>Ostetaan</option></select></p>";
    echo "<p>Kohteen nimi: <input name='ilmoitus_nimi' type='text' size='50' class='IlmKysely'></p>";
    echo "<p>Kohteen kuvaus: <textarea name='ilmoitus_kuvaus' rows='5' cols='80' class='IlmKysely'></textarea></p><br>";
    echo "<input type='hidden' name='myyja_id' value='$myyja_id'>";
    echo "<input type='hidden' name='ilmoitus_paivays' value='$ilmoitus_aika'>";
    echo "<input type='hidden' name='lomaketunnistin' value='1'>";
    echo "<p style='position: relative; top: 15px; right: 8px'><input type='submit' value='Lähetä' class='IlmKysely' ></p>";
    echo "</form>";
    echo "<br><p style='position: relative; top: 15px; right: 8px'><a href='index.php' class='IlmKysely'>Palaa etusivulle</a>.</p>";
    exit;
} else {
    echo "Et ole kirjautunut sisään. <a href='kirjautuminen.html'>Kirjaudu sisään tästä</a>.";
}
?>
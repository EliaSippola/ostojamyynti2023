<?php
session_start();
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#virheilmoitukset
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1) {

    $sahkoposti = $_SESSION["kayttaja_sahkoposti"];

    echo "<form action='kayttajatunnistus.php' method='post' class='form'>";

    echo "<p>Muutetaan kayttäjätietoja käyttäjälle: <b>" . $_SESSION["kayttaja_tunnus"] . "</b></p>";
    echo "<h3>Salasanatietojen muuttaminen</h3>";

    echo "Vanha salasana: <input name='kayttaja_salasana' type='password' class='tietkysely'><br>";
    echo "Uusi salasana: <input name='kayttaja_uusisalasana' type='password' class='TietKysely'><br>";
    echo "Uusi salasana uudelleen: <input name='kayttaja_salasanauudelleen' type='password' class='TietKysely'><br>";

    #echo "Nykyinen sähköpostiosoite: " . $sahkoposti . "<br>";
    echo "Muokkaa sähköpostiosoitetta: <input name='kayttaja_uusisahkoposti' type='text' value='$sahkoposti' class='TietKysely'><br>";

    echo "<input type='hidden' name='kayttaja_tunnus' value='$_SESSION[kayttaja_tunnus]'>";
    echo "<input type='hidden' name='lomaketunnistin' value='2'";

    echo "<p><input type='submit' value='Muuta' class='TietKysely'></p>";

    echo "</form>";

    echo "<a href='index.php'>Palaa etusivulle</a>";
} else {
    echo "<title>Virhe!</title>";
    echo "Et ole kirjautunut sisään. <a href='kirjautuminen.html'>Kirjaudu sisään tästä</a>";
}

?>
<?php
session_start();
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#virheilmoitukset
/*
ini_set('display_errors', 1);
ini_set('display_startut_errors', 1);
error_reporting(E_ALL);
*/

header("Content-Type:text/html;charset=utf-8");

#tarkista onko sisäänkirjautunut ja lopeta sessio
if (isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1) {
    $_SESSION['LOGGEDIN'] = 0;
    session_unset();
    session_destroy();
    echo "Uloskirjautuminen onnistui! <a href='kirjautuminen.html'>Kirjaudu uudelleen</a> tai <a href='index.php'>palaa etusivulle</a>.";
} else {
    echo "Ei sisäänkirjautuneita istuntoja. <br><br><a href='kirjautuminen.html'>Kirjaudu sisään</a> tai <a href='index.php'>siirry etusivulle</a>.";
}
?>
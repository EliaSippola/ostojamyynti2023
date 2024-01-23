<?php
session_start();
include("kantayhteys.php");
?>
<html><head><link rel="stylesheet" href="styles.css"><title>Osto- ja myyntikanava</title><link rel="icon" type="image/x-icon" href="/favicon.png"><head><html>
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

#sivun alku
echo "<h2>Osto- ja myyntipalsta</h2>";

#onko kirjautunut 
if (isset($_SESSION["LOGGEDIN"]) && $_SESSION["LOGGEDIN"] == 1) {

    echo "Tervetuloa käyttämään palvelua " . $_SESSION["kayttaja_tunnus"] . "!<br>";

    echo "(<a href='lisaailmoitus.php'>Lisää ilmoitus</a>) - (<a href='tiedot.php'>Muuta tietojasi</a>) - (<a href='uloskirjautuminen.php'>Kirjaudu ulos</a>)<br>";
} else {
    echo "<a href='kirjautuminen.html'>Kirjaudu palveluun</a><br><br>Etkö ole vielä rekisteröitynyt? <a href='rekisterointi.html'>Rekisteröidy palveluun tästä</a>.";
}

echo "<h3>Ilmoitukset:</h3>";

#ilmoitusten hakeminen
echo "Hae ilmoituksia:<br><p><form action='haeilmoitus.php' method='post'><input name='haku' type='text'><input type='submit' name='submit' value='Hae' style='position: relative; left: 5px'></form></p>";

//ilmoitukset
#ei tarvetta sql injection suojaukselle
$result = mysqli_query($conn, "SELECT * FROM ilmoitukset INNER JOIN kayttajat ON ilmoitukset.myyja_id = kayttajat.kayttaja_id");

#virheilmoitukset
if (!$result) {
    $result = mysqli_query($link, $sql);
    printf("Error: %s\n", mysqli_error($conn));
    exit;
}

#tiedot
$num = mysqli_num_rows($result);

#ilmoitukset
$i = 0;
while ($i < $num) {
    $row = mysqli_fetch_assoc($result);

    #ilmoituksen laji
    $ilmoitus_id = $row['ilmoitukset_id'];
    $ilmoitus_laji = $row['ilmoitukset_laji'];

    if ($ilmoitus_laji == false) {
        echo mysqli_error($conn);
    }

    if ($ilmoitus_laji == 1) {
        $ilmoitus_laji = "Myydään";
    } elseif ($ilmoitus_laji == 2) {
        $ilmoitus_laji = "Ostetaan";
    } else {
        $ilmoitus_laji = "Virhe ladattaessa ilmoituslajia";
    }

    #ilmoitukset tiedot
    $ilmoitus_nimi = $row['ilmoitukset_nimi'];
    $ilmoitus_kuvaus = $row['ilmoitukset_kuvaus'];
    $ilmoitus_paivays = $row['ilmoitukset_paivays'];
    
    $myyja_id = $row['kayttaja_id'];
    $myyja_tunnus = $row['kayttaja_tunnus'];
    $myyja_sahkoposti = $row['kayttaja_sahkoposti'];

    $ilmoitus_oikeapaivays = date("d-m-Y", strtotime($ilmoitus_paivays));

    #kaikille näkyvä osa
    echo "<p><table width='500' bgcolor='#10B8B6'><tr><td bgcolor='#AABBCC'><b>$ilmoitus_laji: $ilmoitus_nimi</b></td></tr>
    <tr><td>$ilmoitus_kuvaus</td></tr><tr><td>ilmoitus jätetty: $ilmoitus_oikeapaivays</td></tr>
    <tr><td>Ilmoittaja: $myyja_tunnus (<a href='mailto: $myyja_sahkoposti'>$myyja_sahkoposti</a>)</td></tr>";
    
    #admineille ja postaukesn omistajille näkyvä osa
    if ((isset($_SESSION['kayttaja_id']) && $_SESSION['kayttaja_id'] == $myyja_id) || (isset($_SESSION['kayttaja_taso']) && $_SESSION['kayttaja_taso'] == "admin")) {
        echo "<tr><td><form action='poistailmoitus.php' method='post'><input type='hidden' name='poista' value='0'><input type='hidden' name='ilmoitus_id' value='$ilmoitus_id'>
        <input type='hidden' name='ilmoitus_nimi' value='$ilmoitus_nimi'><input type='submit' value='Poista' style='float: left;'>
        </form><form action='muokkaailmoitus.php' method='post'><input type='hidden' name='muokkaa_id' value='$ilmoitus_id'>
        <input type='submit' value='Muokkaa' style='position: relative; left: 5px;'></form></td></tr>";
    }
    echo "</table></p>";

    $i++;
}

mysqli_close($conn);
?>
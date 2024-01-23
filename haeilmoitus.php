<?php
session_start();
include("kantayhteys.php");
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

#onko hakusana asetettu
if (isset($_POST['haku'])) {$haku = $_POST['haku'];}

echo "<h3>Haun tulokset:</h3><br><form action='haeilmoitus.php' method='post'><input name='haku' type='text'>
<input type='submit' name='submit' value='Hae'></form></p>";

#hakufunktio
if (isset($_POST['submit']) && !empty($haku)) {
    #valitaan ilmoitukset jotka vastaavat hakua

    #alkuperäinen
    #$result = mysqli_query($conn, "SELECT * FROM ilmoitukset INNER JOIN kayttajat ON ilmoitukset.myyja_id = kayttajat.kayttaja_id 
    #WHERE ilmoitus_kuvaus LIKE '%" . $haku . "%' OR ilmoitus_nimi LIKE '%" . $haku . "%'");

    #for sql injection
    $sql = "SELECT * FROM ilmoitukset INNER JOIN kayttajat ON ilmoitukset.myyja_id = kayttajat.kayttaja_id WHERE ilmoitukset_kuvaus LIKE ? OR ilmoitukset_nimi LIKE ?";
    $param = ["%$haku%", "%$haku%"];
    $result = mysqli_execute_query($conn, $sql, $param);

    $num = mysqli_num_rows($result);

    if ($num == 0) {
        echo "Hakusanallesi '<b>" . $haku . "</b>' ei löytynyt ilmoituksia.";
    } else {
        echo "Hakusanallesi '<b>" . $haku . "</b>' löytyi seuraavat ilmoitukset:<br>";
    }

    #loopataan kaikkien vastaavien ilmoitusten läpi
    $i = 0;
    while ($i < $num) {
        $row = mysqli_fetch_assoc($result);

        $ilmoitus_id = $row['ilmoitukset_id'];
        $ilmoitus_laji = $row['ilmoitukset_laji'];
        
        if ($ilmoitus_laji === false) {
            echo mysqli_error($conn);
        }

        if ($ilmoitus_laji == 1) {
            $ilmoitus_laji = "Myydään";
        } elseif ($ilmoitus_laji == 2) {
            $ilmoitus_laji = "Ostetaan";
        } else {
            $ilmoitus_laji = "Virhe ladattaessa ilmoituslajia";
        }

        #ilmoitusten tiedot
        $ilmoitus_nimi = $row['ilmoitukset_nimi'];
        $ilmoitus_kuvaus = $row['ilmoitukset_kuvaus'];
        $ilmoitus_paivays = $row['ilmoitukset_paivays'];

        $ilmoitus_oikeapaivays = date("d-m-Y", strtotime($ilmoitus_paivays));

        $myyja_id = $row['myyja_id'];
        $myyja_tunnus = $row['kayttaja_tunnus'];
        $myyja_sahkoposti = $row['kayttaja_sahkoposti'];


        #kaikille näkyvä osa
        echo "<p><table width='500' bgcolor='#10B8B6'><tr><td bgcolor='#AABBCC'><b>$ilmoitus_laji: $ilmoitus_nimi</b></td></tr>
        <tr><td>$ilmoitus_kuvaus</td></tr><tr><td>ilmoitus jätetty: $ilmoitus_oikeapaivays</td></tr>
        <tr><td>Ilmoittaja: $myyja_tunnus (<a href='mailto: $myyja_sahkoposti'>$myyja_sahkoposti</a>)</td></tr>";

        #admineille ja postaukesn omjistajille näkyvä osa
        if ((isset($_SESSION['kayttaja_id']) && $_SESSION['kayttaja_id'] == $myyja_id) || (isset($_SESSION['kayttaja_taso']) && $_SESSION['kayttaja_taso'] == "admin")) {
            echo "<tr><td><form action='poistailmoitus.php' method='post'><input type='hidden' name='poista' value='0'><input type='hidden' name='ilmoitus_id' value='$ilmoitus_id'>
            <input type='hidden' name='ilmoitus_nimi' value='$ilmoitus_nimi'><input type='submit' value='Poista' style='float: left;'>
            </form><form action='muokkaailmoitus.php' method='post'><input type='hidden' name='muokkaa_id' value='$ilmoitus_id'>
            <input type='submit' value='Muokkaa' style='position: relative; left: 5px;'></form></td></tr>";
        }
        echo "</table></p>";

        $i++;
    }
    echo "<br><a href='haeilmoitus.php'>Tyhjennä haku</a> - ";
} else {
    echo "Syötä hakusana yllä olevaan kenttään<br>";
}
echo "<a href='index.php'>Palaa etusivulle</a>";

mysqli_close($conn);
?>
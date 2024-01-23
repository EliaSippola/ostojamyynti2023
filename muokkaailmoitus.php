<?php
session_start();
include("kantayhteys.php");
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

$ilmoitus_id = mysqli_real_escape_string($conn, $_POST['muokkaa_id']);

#ilmoitusten muokkaaminen
if (isset($ilmoitus_id)) {
    #alkuperäinen
    #$query = mysqli_query($conn, "SELECT * FROM ilmoitukset WHERE ilmoitus_id = '$ilmoitus_id'");
    
    #for sql injection
    $sql = "SELECT * FROM ilmoitukset WHERE ilmoitukset_id = ?";
    $param = [$ilmoitus_id];
    $query = mysqli_execute_query($conn, $sql, $param);

    $row = mysqli_fetch_assoc($query);

    $ilmoitus_nimi = $row['ilmoitukset_nimi'];
    $ilmoitus_kuvaus = $row['ilmoitukset_kuvaus'];
    $ilmoitus_laji = $row['ilmoitukset_laji'];

    if ($ilmoitus_laji == 1) {
        $ilmoitus_laji = "Myydään";
    } elseif ($ilmoitus_laji == 2) {
        $ilmoitus_laji = "Ostetaan";
    } else {
        $ilmoitus_laji = "Virhe ladattaessa ilmoituslajia";
    }

    echo "<form action='ilmoitushallinta.php' method='post' class='form'>";
    echo "<h3>Muokkaa ilmoitusta</h3>";
    echo "<p>Ilmoitustyyppi: " . $ilmoitus_laji . "</b><br>Muuta: <select name='ilmoitus_uusilaji' class='MuokKysely'><option value='1'>Myydään</option>
    <option value='2'>Ostetaan</option></select></p>";
    echo "<p>Muokkaa nimeä: <input name='ilmoitus_uusinimi' type='text' size='50' value='$ilmoitus_nimi' class='MuokKysely'></p>";
    echo "<p>Muokkaa kuvausta: <textarea name='ilmoitus_uusikuvaus' rows='5' cols'80' class='MuokKysely'>$ilmoitus_kuvaus</textarea></p>";
    echo "<input type='hidden' name='ilmoitus_kuvaus' value='$ilmoitus_kuvaus'>";
    echo "<input type='hidden' name='lomaketunnistin' value='2'>";
    echo "<input type='hidden' name='ilmoitus_id' value='$ilmoitus_id'>";
    echo "<p><input type='submit' value='Lähetä' class='MuokKysely' style='position: relative; top: 50px'></p>";
    echo "</form>";
    echo "<br><br><p><a href='index.php'>Palaa etusivulle</a>.</p>";
}

mysqli_close($conn);
?>
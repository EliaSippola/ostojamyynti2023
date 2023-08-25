<?php
session_start();
include("kantayhteys.php");
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

$sivu = $_POST['lomaketunnistin'];

#ilmoituksen luominen
if ($sivu == 1 && isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1) {
    $ilmoitus_laji = mysqli_real_escape_string($conn, $_POST['ilmoitus_laji']);
    $ilmoitus_nimi = mysqli_real_escape_string($conn, $_POST['ilmoitus_nimi']);
    $ilmoitus_kuvaus = mysqli_real_escape_string($conn, $_POST['ilmoitus_kuvaus']);
    $ilmoitus_paivays = mysqli_real_escape_string($conn, $_POST['ilmoitus_paivays']);
    $myyja_id = mysqli_real_escape_string($conn, $_POST['myyja_id']);

    if (!empty($ilmoitus_nimi) && !empty($ilmoitus_kuvaus)) {
        #alkuperäinen
        #$query = mysqli_query($conn, "INSERT INTO ilmoitukset (ilmoitus_laji, ilmoitus_nimi, ilmoitus_kuvaus, ilmoitus_paivays, myyja_id) VALUES ('$ilmoitus_laji', '$ilmoitus_nimi', '$ilmoitus_kuvaus', '$ilmoitus_paivays', '$myyja_id')");
        
        #for sql injection
        $sql = "INSERT INTO ilmoitukset (ilmoitus_laji, ilmoitus_nimi, ilmoitus_kuvaus, ilmoitus_paivays, myyja_id) VALUES (?, ?, ?, ?, ?)";
        $param = [$ilmoitus_laji, $ilmoitus_nimi, $ilmoitus_kuvaus, $ilmoitus_paivays, $myyja_id];
        mysqli_execute_query($conn, $sql, $param);

        echo "Ilmoituksen lisääminen onnistui! <a href='index.php'>Palaa etusivulle</a>.";
    } else {
        echo "Jätit tietoja täyttämättä. Ole hyvä ja <a href='lisaailmoitus.php'>täytä lomake uudelleen</a>.";
    }

#ilmoituksen muokkaaminen
} elseif ($sivu == 2 && isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] == 1) {

    $ilmoitus_id = mysqli_real_escape_string($conn, $_POST['ilmoitus_id']);
    $ilmoitus_uusilaji = mysqli_real_escape_string($conn, $_POST['ilmoitus_uusilaji']);
    $ilmoitus_uusinimi = mysqli_real_escape_string($conn, $_POST['ilmoitus_uusinimi']);

    if (isset($_POST['ilmoitus_uusikuvaus'])) {
        $ilmoitus_uusikuvaus = mysqli_real_escape_string($conn, $_POST['ilmoitus_uusikuvaus']);
    } else {
        $ilmoitus_uusikuvaus = mysqli_real_escape_string($conn, $_POST['ilmoitus_kuvaus']);
    }

    if (!empty($ilmoitus_id) && !empty($ilmoitus_uusilaji) && !empty($ilmoitus_uusinimi) && !empty($ilmoitus_uusikuvaus)) {
        #alkuperäinen
        #mysqli_query($conn, "UPDATE ilmoitukset SET ilmoitus_laji = '$ilmoitus_uusilaji', ilmoitus_nimi = '$ilmoitus_uusinimi', 
        #ilmoitus_kuvaus = '$ilmoitus_uusikuvaus' WHERE ilmoitus_id = '$ilmoitus_id'");

        #for sql injection
        #vain nimi ja kuvaus tarvitsisi asettaa erikseen koska muut on asetettu automaattisesti. Koodi kuitenkin näyttää siistimmältä näin.
        $sql = "UPDATE ilmoitukset SET ilmoitus_laji = ?, ilmoitus_nimi = ?, ilmoitus_kuvaus = ? WHERE ilmoitus_id = ?";
        $param = [$ilmoitus_uusilaji, $ilmoitus_uusinimi, $ilmoitus_uusikuvaus, $ilmoitus_id];
        mysqli_execute_query($conn, $sql, $param);

        echo "Ilmoituksen muokkaaminen onnistui! <a href='index.php'>Palaa etusivulle</a>";
    } else {
        echo "Jätit kenttiä tyhjiksi. <a href='index.php'>Palaa etusivulle</a> ja kokeile uudelleen.";
    }


} else {
    echo "Incorrect form value.";
}
mysqli_close($conn);
?>
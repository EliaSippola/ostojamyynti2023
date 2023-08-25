<?php
#luodaan sessio
session_start();
include("kantayhteys.php");
?>
<html><head><link rel="stylesheet" href="styles.css"><head><html>
<?php
#aika ja kieli
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("Europe/Helsinki");

#tunnukset
$kayttaja_tunnus = mysqli_real_escape_string($conn, $_POST['kayttaja_tunnus']);
$kayttaja_salasana = $_POST['kayttaja_salasana'];

#alkuperäinen, sha256 ilman suolaa
#$password_hash = hash("sha256", $kayttaja_salasana);

#md5
#$password_hash = md5($kayttaja_salasana);

#with salt
$password_hash = password_hash($kayttaja_salasana, PASSWORD_DEFAULT);

#funktio sähköpostin vaihtoon
#saaOllaSama tarkistaa saako sähköposti olla sama kuin alkuperäinen
function vaihdaSahkoposti(bool $saaOllaSama) {
    global $conn;

    $kayttaja_sahkoposti = mysqli_real_escape_string($conn, $_SESSION['kayttaja_sahkoposti']);
    $kayttaja_uusisahkoposti = mysqli_real_escape_string($conn, $_POST['kayttaja_uusisahkoposti']);
    global $kayttaja_tunnus;

    #alkuperäinen
    #$sahkopostit = mysqli_query($conn, "SELECT * FROM kayttajat WHERE kayttaja_sahkoposti = '$kayttaja_uusisahkoposti'");

    #for sql injection
    $sql = "SELECT * FROM kayttajat WHERE kayttaja_sahkoposti = ?";
    $param = [$kayttaja_uusisahkoposti];
    $sahkopostit = mysqli_execute_query($conn, $sql, $param);

    #jos sähköpostia ei ole asetettu
    if (empty($kayttaja_uusisahkoposti)) {
        echo "Sähköposti on tyhjä. <a href='tiedot.php'>Kokeile uudelleen</a>.";
        return False;
    }

    #tarkista onko sähköposti olemassa
    if ($kayttaja_sahkoposti == $kayttaja_uusisahkoposti && !$saaOllaSama) {
        echo "Sähköposti on sama! <a href='tiedot.php'>Takaisin muuttamaan tietoja</a>";
        return False;

    #onko sähköposti olemassa
    #1: ei saa olla sama, ja sähköposti on uusi
    #2: saa olla sama, sähköposti ei ole oma, ja sähköposti on uusi
    #3: saa olla sama, sähköposti on oma, ja sähköpostia ei ole olemassa tai niitä on monia
    } elseif (mysqli_num_rows($sahkopostit) !== 0 && !$saaOllaSama || mysqli_num_rows($sahkopostit) !== 0 && $kayttaja_sahkoposti !== $kayttaja_uusisahkoposti && $saaOllaSama || mysqli_num_rows($sahkopostit) !== 1 && $kayttaja_sahkoposti == $kayttaja_uusisahkoposti && $saaOllaSama) {
        echo "Sähköposti on varattu! <a href='tiedot.php'>Takaisin muuttamaan tietoja</a>";
        return False;
    }

    #vaihdetaan sähköposti tietokantaan

    #alkuperäinen
    #mysqli_query($conn, "UPDATE kayttajat SET kayttaja_sahkoposti = '$kayttaja_uusisahkoposti' WHERE kayttaja_tunnus = '$kayttaja_tunnus'");
    
    #for sql injection
    $sql = "UPDATE kayttajat SET kayttaja_sahkoposti = ? WHERE kayttaja_tunnus = ?";
    $param = [$kayttaja_uusisahkoposti, $kayttaja_tunnus];
    mysqli_execute_query($conn, $sql, $param);

    $_SESSION['kayttaja_sahkoposti'] = $kayttaja_uusisahkoposti;
    return True;
}

#mikä sivu kyseessä
$sivu = $_POST['lomaketunnistin'];


// SIVUJEN LOMAKKEET

#Rekisteröinti
if ($sivu == 0) {
    $kayttaja_sahkoposti = mysqli_real_escape_string($conn, $_POST['kayttaja_sahkoposti']);
    $varmistus = mysqli_real_escape_string($conn, $_POST['varmistus']);
    
    #tietojen tarkistus
    if (!empty($kayttaja_tunnus) && !empty($kayttaja_salasana) && !empty($kayttaja_sahkoposti) && strtolower($varmistus) == "kuusi") {

        #ei samanlaisia tunnuksia

        #alkuperäinen
        #$tunnukset = mysqli_query($conn, "SELECT * FROM kayttajat WHERE kayttaja_tunnus = '$kayttaja_tunnus'");
        #$sahkopostit = mysqli_query($conn, "SELECT * FROM kayttajat WHERE kayttaja_sahkoposti = '$kayttaja_sahkoposti'");

        #for sql injection
        $sql = "SELECT * FROM kayttajat WHERE kayttaja_tunnus = ?";
        $param = [$kayttaja_tunnus];
        $tunnukset = mysqli_execute_query($conn, $sql, $param);

        $sql = "SELECT * FROM kayttajat WHERE kayttaja_sahkoposti = ?";
        $param = [$kayttaja_sahkoposti];
        $sahkopostit = mysqli_execute_query($conn, $sql, $param);

        if (mysqli_num_rows($tunnukset) !== 0) {
            echo "Tunnus on jo käytössä! <a href='rekisterointi.html'>Kokeile uudestaan</a>.";
        } elseif (mysqli_num_rows($sahkopostit) !== 0) {
            echo "Sähköposti on jo käytössä! <a href='rekisterointi.html'>Kokeile uudestaan</a>.";
        
        #lisätään tiedot
        } else {
            #alkuperäinen
            #$sql = "INSERT INTO kayttajat (kayttaja_taso, kayttaja_tunnus, kayttaja_salasana, kayttaja_sahkoposti) 
            #VALUES ('user', '$kayttaja_tunnus', '$password_hash', '$kayttaja_sahkoposti');";

            #for sql injection
            $sql = "INSERT INTO kayttajat (kayttaja_taso, kayttaja_tunnus, kayttaja_salasana, kayttaja_sahkoposti) VALUES ('user', ?, ?, ?)";
            $param = [$kayttaja_tunnus, $password_hash, $kayttaja_sahkoposti];

            if ($query = mysqli_execute_query($conn, $sql, $param)) {
                echo "Rekisteröinti onnistui! <a href='kirjautuminen.html'>Kirjaudu sisään tästä</a>.";
            } else {
                echo "Rekisteröinti epäonnistui odottamattomasta syystä. < href='kirjautuminen.html'>Takaisin rekisteröintiin</a>.";
            }
        }
    } elseif (empty($varmistus) or $varmistus !== "kuusi") {
        echo "Varmistus on väärin, varmista että kirjoitit sen oikein. <a href='rekisterointi.html'>Täytä lomake uudelleen</a>.";
    } else {
        echo "Jätit tietoja täyttämättä. Ole hyvä ja <a href='rekisterointi.html'>täytä lomake uudelleen</a>.";
    }

#kirjautuminen
} elseif ($sivu == 1) {
    #alkuperäinen
    #$kayttaja = mysqli_query($conn, "SELECT * FROM kayttajat WHERE kayttaja_tunnus ='$kayttaja_tunnus' AND kayttaja_salasana = '$password_hash'");

    #for sql injection
    #$sql = "SELECT * FROM kayttajat WHERE kayttaja_tunnus = ? AND kayttaja_salasana = ?";
    #$param = [$kayttaja_tunnus, $password_hash];
    #$kayttaja = mysqli_execute_query($conn, $sql, $param);

    #better password protection
    $sql = "SELECT * FROM kayttajat WHERE kayttaja_tunnus = ?";
    $param = [$kayttaja_tunnus];
    $query = mysqli_execute_query($conn, $sql, $param);

    if (mysqli_num_rows($query) !== 0) {
        $tiedot = mysqli_fetch_array($query) or die(mysqli_error($conn));
        $password_true = password_verify($kayttaja_salasana, $tiedot['kayttaja_salasana']);
    }

    if (empty($kayttaja_tunnus)) {
        echo "Sinun täytyy kirjoittaa käyttäjätunnus. <a href='kirjautuminen.html'>Takaisin kirjautumiseen</a>.";
    } elseif (empty($kayttaja_salasana)) {
        echo "Sinun täytyy kirjoittaa salasana. <a href='kirjautuminen.html'>Takaisin kirjautumiseen</a>.";
    } elseif (mysqli_num_rows($query) == 0) {
        echo "Kirjautumistiedot väärin. <a href='kirjautuminen.html'>Yritä uudelleen</a>. Varmista tietojen oikeinkirjoitus. 
        <br><br><br>Etkö ole vielä rekisteröitynyt? <a href='rekisterointi.html'>Rekisteröidy käyttäjäksi tästä</a>.";
    } elseif (isset($password_true) && !$password_true) {
        echo "Salasana väärin! <a href='kirjautuminen.html'>Yritä uudelleen</a>.";
    } elseif (mysqli_num_rows($query) !==0) {
        echo "Kirjautuminen onnistui! <br><br><a href='index.php'>Siirry palveluun</a>.";

        $_SESSION["kayttaja_id"] = $tiedot['kayttaja_id'];
        $_SESSION["kayttaja_taso"] = $tiedot['kayttaja_taso'];
        $_SESSION["kayttaja_tunnus"] = $tiedot['kayttaja_tunnus'];
        $_SESSION["kayttaja_salasana"] = $tiedot['kayttaja_salasana'];
        $_SESSION["kayttaja_sahkoposti"] = $tiedot['kayttaja_sahkoposti'];
        $_SESSION["LOGGEDIN"] = 1;
    } else {
        echo "Odottamaton virhe kirjautumisessa. <a href='kirjautuminen.html'>Takaisin kirjautumaan</a>.";
    }

#käyttäjätietojen muuttaminen
} elseif ($sivu == 2 && isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN'] = 1) {
    #salasanat
    $kayttaja_uusisalasana = $_POST['kayttaja_uusisalasana'];
    $kayttaja_salasanauudelleen = $_POST['kayttaja_salasanauudelleen'];
    $kayttaja_uusisahkoposti = mysqli_real_escape_string($conn, $_POST['kayttaja_uusisahkoposti']);

    #tietojen hakeminen

    #alkuperäinen
    #$query = mysqli_query($conn, "SELECT * FROM kayttajat WHERE kayttaja_tunnus = '$kayttaja_tunnus'");

    #for sql injection
    $sql = "SELECT * FROM kayttajat WHERE kayttaja_tunnus = ?";
    $param = [$kayttaja_tunnus];
    $query = mysqli_execute_query($conn, $sql, $param);

    $tiedot = mysqli_fetch_array($query) or die(mysqli_error($conn));

    #jos salasanakohdat tyhjiä, sähköpostin vaihto
    if (empty($_POST['kayttaja_salasana']) && empty($kayttaja_uusisalasana) && empty($kayttaja_salasanauudelleen)) {
        $onnistui = vaihdaSahkoposti(false);
        if ($onnistui) {
            echo "Tietojen muutos onnistui, sähköpostisi on nyt " . $kayttaja_uusisahkoposti . ". <br><a href='index.php'>Palaa etusivulle</a> - <a href='tiedot.php'>Jatka tietojen muokkaamista</a>";
        }
    
    #salasanojen virheet
    } elseif (!password_verify($kayttaja_salasana, $tiedot['kayttaja_salasana'])) {
        echo "Vanha salasana on väärin! <a href='tiedot.php'>Yritä uudelleen</a>.";
    } elseif (empty($kayttaja_uusisalasana)) {
        echo "Sinun täytyy kirjoittaa uusi salasana! <a href='tiedot.php'>Yritä uudelleen</a>.";
    } elseif (empty($kayttaja_salasanauudelleen) || $kayttaja_uusisalasana !== $kayttaja_salasanauudelleen) {
        echo "Salasanat eivät täsmää. <a href='tiedot.php'>yritä uudelleen</a>.";

    #salasanan vaihtaminen
    } else {
        $onnistui = vaihdaSahkoposti(True);

        if ($onnistui) {
            #alkuperäinen
            #$password_hash = hash("sha256", $kayttaja_uusisalasana);

            #md5
            #$password_hash = md5($kayttaja_uusisalasana);

            #for better password protection
            $password_hash = password_hash($kayttaja_uusisalasana, PASSWORD_DEFAULT);

            #alkuperäinen
            #mysqli_query($conn, "UPDATE kayttajat SET kayttaja_salasana = '$password_hash' WHERE kayttaja_tunnus = '$kayttaja_tunnus'");

            #for sql injection
            $sql = "UPDATE kayttajat SET kayttaja_salasana = ? WHERE kayttaja_tunnus = ?";
            $param = [$password_hash, $kayttaja_tunnus];
            mysqli_execute_query($conn, $sql, $param);

            $_SESSION['$kayttaja_salasana'] = $kayttaja_uusisalasana;
            echo "Tietojen muutos onnistui. <br><br>Sähköposti: " . $kayttaja_uusisahkoposti . "<br>Salasana: " . $kayttaja_uusisalasana . "<br><br><a href='index.php'>Palaa etusivulle</a> - <a href='tiedot.php'>Jatka tietojen muokkaamista</a>";
        }
    }

} else {
    echo "Incorrect form value.";
}

?>
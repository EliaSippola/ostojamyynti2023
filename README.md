# ostojamyynti2023
Riverian ammattikoulussa tehty projektityö. Ensimmäinen tekemäni nettisivu, joka käyttää tietokantaa. Tehty 08/2023-09/2023. Projektia ei enää muokata.

Sivusto on yksinkertainen, ja se on tehty koulussa annettujen ohjeiden avulla. Sivustoon kuuluu SQL-tietokanta, joka tallentaa käyttäjien tiedot, ja ilmoitukset. Sivusto on suojattu SQL-injetkioita vastaan, ja salasanat on suojattu PHP:n `password_hash()` -komennolla. Sivuston tyyli on hyvin yksinkertainen.

Projektiin kuuluu yksinkertainen nettisivu, johon kuuluu tietokanta. Nettisivulla on seuraavat toiminnot:
- käyttäjän luonti, muokkaaminen ja poistaminen
- Ilmoitusten lisääminen, muokkaaminen ja poistaminen
- Ilmoitusten selaaminen, tietojen näkeminen, ja hakeminen

## asennus
Nettisivun ja tietokannan hostaamiseen käytetty XAMPP -ympäristöä (verkkosivu ja asennus: [www.apachefriends.org/](https://www.apachefriends.org/){target="_blank"})

1. Siirrä projekti uuteen kansioon sijainnissa `C:/xampp/htdocs/`. Jos et löyfä kansiota `htdocs`, tarkista mihin `XAMPP` on asennettu. *tietokantaa ei ole pakko siirtää.*
2. Luo tietokanta nimeltä `tietokanta`, ja tuo tiedoston <a href="tietokanta.sql" target="_blank">tietokanta.sql</a> tiedot tietokantaan.
3. Luo käyttäjä `Console`, ja aseta sen salasanaksi `ConsExclusive`. Anna käyttäjälle `SELECT`, `INSERT`, `UPDATE` ja `DELETE` -oikeudet tietokantaan.
4. 

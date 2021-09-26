<?php
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Strona startowa";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenu($stronka->menu_nologin);
$stronka->DomknijBlok(); //menugorne
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("srodek");
?>

<p style="margin-top:15%;";><a href="zaloguj.php">Logowanie</a><br /><br />
<a href="rejestracja.php">Rejestracja</a><br /><br />
Jeżeli zapomniałeś hasło wyślij <a href="mailto:ryszard@rkhost.strefa.pl">mail_do_administratora</a></p>

<?php
$stronka->DomknijBlok(); //srodek
$stronka->DomknijBlok(); //tresc
$stronka->WyswietlStopke();
?>


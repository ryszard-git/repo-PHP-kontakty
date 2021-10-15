<?php
session_start();

require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Przeglądanie treści kontaktu";
$stronka->WyswietlNaglowek();

if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==="tak"))
{
	$stronka->OtworzBlok("menugorne");
	$stronka->WyswietlMenuGlowne();
	$stronka->DomknijBlok(); //menugorne
	$stronka->WyswietlPasekLogin();
}
else
{
	$stronka->OtworzBlok("menugorne");
	$stronka->WyswietlMenu($stronka->menu_nologin);
	$stronka->DomknijBlok(); //menugorne
}

$stronka->OtworzBlok("tresc");

echo '<p style="text-align:center;">Przeglądanie treści kontaktu</p><br /><br />';
$id_kontakt=$_GET['id_kontakt'];
require "config/config.php";

@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
if ($mysqli->connect_error)
{
	echo 'Próba połączenia z bazą nie powiodła się.<br />';
	echo $mysqli->connect_error;
	$stronka->DomknijStrone1();
	exit;
}
$result=$mysqli->set_charset("utf8");
if (!$result)
{
	echo 'Błąd ustawienia kodowania.<br />';
	$stronka->DomknijStrone1();
	exit;
}

$sql='SELECT imie, nazwisko, adres, miejscowosc, email, telefon, data_urodzenia FROM znajomi WHERE id_kontakt="'.$id_kontakt.'"';
$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania SELECT.<br />';
	$stronka->DomknijStrone1();
	exit;
}

$wiersz=$result->fetch_object();

$imie=$wiersz->imie;
$nazwisko=$wiersz->nazwisko;
$adres=$wiersz->adres;
$miejscowosc=$wiersz->miejscowosc;
$email=$wiersz->email;
$telefon=$wiersz->telefon;
$data_ur=$wiersz->data_urodzenia;

echo '<table class="wyswietlanie" border="0">';
echo '<tr><td>Imię: </td><td><strong>'.$imie.'</strong></td></tr>';
echo '<tr><td>Nazwisko: </td><td><strong>'.$nazwisko.'</strong></td></tr>';
echo '<tr><td>Adres: </td><td><strong>'.$adres.'</strong></td></tr>';
echo '<tr><td>Miejscowość: </td><td><strong>'.$miejscowosc.'</strong></td></tr>';
echo '<tr><td>E-mail: </td><td><strong>'.$email.'</strong></td></tr>';
echo '<tr><td>Telefon: </td><td><strong>'.$telefon.'</strong></td></tr>';
echo '<tr><td>Data urodzenia: </td><td><strong>'.$data_ur.'</strong></td></tr>';
echo '</table><br /><hr /><br />';

if (isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==="tak"))
{
	echo '<button type="button" style="cursor:pointer;" onclick="edycja('.$id_kontakt.')">Edytuj</button>';
	echo '<button type="button" style="float:right; cursor:pointer;" onclick="zapytaj('.$id_kontakt.')">Usuń</button>';
}

$mysqli->close();
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>

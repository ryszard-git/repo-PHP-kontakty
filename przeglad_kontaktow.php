<?php
session_start();

require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Przeglądanie kontaktów";
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
$stronka->OtworzBlok("tematy");

require "config/config.php";

@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
if ($mysqli->connect_error)
{
	echo 'Próba połączenia z bazą nie powiodła się.<br />';
	echo $mysqli->connect_error;
	$stronka->DomknijStrone();
	exit;
}
$result=$mysqli->set_charset("utf8");
if (!$result)
{
	echo 'Błąd ustawienia kodowania.<br />';
	$stronka->DomknijStrone();
	exit;
}

$sql='SELECT id_kontakt, imie, nazwisko FROM znajomi ORDER BY nazwisko';

$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania SELECT.<br />';
	$stronka->DomknijStrone();
	exit;
}
$ile_znaleziono=$result->num_rows;
if ($ile_znaleziono===0)
{
	echo '<span style="font-style:italic;">Brak danych.</span><br />';
}
else
{
	while ($wiersz=$result->fetch_object())
	{
		$imie=$wiersz->imie;
		$nazwisko=$wiersz->nazwisko;
		$id_kontakt=$wiersz->id_kontakt;
		echo '<a href="pokaz_tresc.php?id_kontakt='.$id_kontakt.'">'.$imie.' '.$nazwisko.'</a><br /><br />';
	}
}
$mysqli->close();
$stronka->DomknijBlok(); // div tematy 
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>

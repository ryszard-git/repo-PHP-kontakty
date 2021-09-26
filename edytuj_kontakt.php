<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<script>window.location.href = 'zaloguj.php';</script>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Edycja kontaktów";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("kontakt");

$id_kontakt=$_GET['id_kontakt'];

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

$sql='SELECT imie, nazwisko, adres, miejscowosc, email, telefon, data_urodzenia FROM znajomi WHERE id_kontakt="'.$id_kontakt.'"';
$result=$mysqli->query($sql);
if (!$result)
{
	echo 'Błąd zapytania SELECT.<br />';
	$stronka->DomknijStrone();
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
//****************************************************************************
$data_urodzenia=explode("-",$data_ur); // zmienna $data_urodzenia jest tablicą zawierającą dd mm rr urodzenia
$rok_urodzenia=$data_urodzenia[0];
$mc_urodzenia=$data_urodzenia[1];
$dzien_urodzenia=$data_urodzenia[2];

//przygotowanie listy rozwijanej dla daty urodzenia
// $du_dd - dzień daty
// $du_mm - miesiąc daty
// $du_rr - rok daty

$data_ur_option_dd='<option value="">- dzień -</option>';
$data_ur_option_mm='<option value="">- miesiąc -</option>';
$data_ur_option_rr='<option value="">- rok -</option>';

for ($du_dd=1; $du_dd<=31; $du_dd++)
{
	if ($du_dd<10)
	{
		$du_dd="0".$du_dd;
	}
	if ($dzien_urodzenia==$du_dd)
	{
		$data_ur_option_dd.="<option selected=\"selected\" value=\"$du_dd\">$du_dd</option>";
	}
	else
	{
		$data_ur_option_dd.="<option value=\"$du_dd\">$du_dd</option>";
	}
}

for ($du_mm=1; $du_mm<=12; $du_mm++)
{
	if ($du_mm<10)
	{
		$du_mm="0".$du_mm;
	}
	if ($mc_urodzenia==$du_mm)
	{
		$data_ur_option_mm.="<option selected=\"selected\" value=\"$du_mm\">$du_mm</option>";
	}
	else
	{
	$data_ur_option_mm.="<option value=\"$du_mm\">$du_mm</option>";
	}
}

for ($du_rr=1920; $du_rr<=2000; $du_rr++)
{
	if ($rok_urodzenia==$du_rr)
	{
		$data_ur_option_rr.="<option selected=\"selected\" value=\"$du_rr\">$du_rr</option>";
	}
	else
	{
	$data_ur_option_rr.="<option value=\"$du_rr\">$du_rr</option>";
	}
}
?>

<p>Edycja kontaktu</p>
<br /><br />
<form method="post" action="">
<table>
<tr><td>Imię:</td><td><input type="text" name="imie" value="<?php echo $imie;?>" size="15" maxlength="30" /></td></tr>
<tr><td>Nazwisko:</td><td><input type="text" name="nazwisko" value="<?php echo $nazwisko;?>" size="15" maxlength="50" /></td></tr>
<tr><td>Adres:</td><td><input type="text" name="adres" value="<?php echo $adres;?>" size="15" maxlength="70" /></td></tr>
<tr><td>Miejscowość:</td><td><input type="text" name="miejscowosc" value="<?php echo $miejscowosc;?>" size="15" maxlength="70" /></td></tr>
<tr><td>Email:</td><td><input type="text" name="email" value="<?php echo $email;?>" size="15" maxlength="50" /></td></tr>
<tr><td>Telefon:</td><td><input type="text" name="telefon" value="<?php echo $telefon;?>" size="15" maxlength="20" /></td></tr>
<tr><td>Data urodzenia:</td><td><select name="dudd">
<?php echo $data_ur_option_dd; ?>
</select>
<select name="dumm">
<?php echo $data_ur_option_mm; ?>
</select>
<select name="durr">
<?php echo $data_ur_option_rr; ?>
</select></td></tr>
</table>
<br />
<p><input type="submit" name="submit" value="Zapisz zmiany" /></p>
</form>
<br/><br/>

<?php
if (isset($_POST["submit"]))
{
	$imie=addslashes(htmlspecialchars(trim($_POST['imie'])));
	$nazwisko=addslashes(htmlspecialchars(trim($_POST['nazwisko'])));
	$adres=addslashes(htmlspecialchars(trim($_POST['adres'])));
	$miejscowosc=addslashes(htmlspecialchars(trim($_POST['miejscowosc'])));
	$email=addslashes(htmlspecialchars(trim($_POST['email'])));
	$telefon=addslashes(htmlspecialchars(trim($_POST['telefon'])));
	if ((empty($_POST['durr'])) || (empty($_POST['dumm'])) || (empty($_POST['dudd'])))
	{
		echo 'Błędna data.<br />Wprowadź pełną datę urodzenia.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	if ((!$imie) || (!$nazwisko) || (!$adres) || (!$miejscowosc) || (!$email) || (!$telefon))
	{
		echo 'Proszę wypełnić wszystkie pola formularza.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	if (checkdate($_POST['dumm'],$_POST['dudd'],$_POST['durr'])===false)
	{
		echo 'Podana data urodzenia nie istnieje.<br />Wprowadź poprawną datę.<br />';
		$stronka->DomknijStrone();
		exit;
	}
	$data_ur=$_POST['durr'].'-'.$_POST['dumm'].'-'.$_POST['dudd']; //data urodzenia w formacie RRRR-MM-DD

	// zapytanie w celu ustalenia czy podany email jest unikalny

	$sql='SELECT id_kontakt FROM znajomi WHERE email="'.$email.'"';
	$result=$mysqli->query($sql);
	if (!$result)
	{
		echo 'Błąd zapytania SELECT.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	$ilosc_email=$result->num_rows;

// sprawdzenie czy id usera znalezionego w bazie ($id_kontakt_usera) i usera edytowanego ($id_kontakt) są różne.
// jeżeli są równe to znaczy że email znaleziony w bazie należy do edytowanego usera i może zostać mu przypisany.
// Jeżeli są różne to znaczy że inny niż edytowany user posiada ten adres.

	if ($ilosc_email===1)
	{
		$wiersz=$result->fetch_object();
		$id_kontakt_usera=$wiersz->id_kontakt; // id usera którego email jest równy wprowadzonemu do formularza edycji.
		if ($id_kontakt_usera!=$id_kontakt)
		{
			echo 'Podany email już istnieje.<br />Wprowadź unikalny adres.<br />';
			$stronka->DomknijStrone();
			exit;
		}
	}

	$sql='UPDATE znajomi SET imie="'.$imie.'",
	nazwisko="'.$nazwisko.'",
	adres="'.$adres.'",
	miejscowosc="'.$miejscowosc.'",
	email="'.$email.'",
	telefon="'.$telefon.'",
	data_urodzenia="'.$data_ur.'"
	WHERE id_kontakt="'.$id_kontakt.'"';

	$result=$mysqli->query($sql);
	if (!$result)
	{
		echo 'Błąd zapytania UPDATE.<br />';
		$stronka->DomknijStrone();
		exit;
	}
	echo 'Kontakt zapisany';
}
$mysqli->close();
$stronka->DomknijBlok(); // div kontakt
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>

<?php
session_start();
if ($_SESSION['zalogowany']!="tak") {
	echo "<script>window.location.href = 'zaloguj.php';</script>";
	exit;
}
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Dodawanie kontaktów";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenuGlowne();
$stronka->DomknijBlok(); //menugorne
$stronka->WyswietlPasekLogin();
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("kontakt");

//przygotowanie listy rozwijanej dla daty urodzenia
// $du_dd - dzień daty
// $du_mm - miesiąc daty
// $du_rr - rok daty

$data_ur_option_dd='<option value="">- dzień -</option>';
$data_ur_option_mm='<option value="">- miesiąc -</option>';
$data_ur_option_rr='<option value="">- rok -</option>';

for ($du_dd=1; $du_dd<=31; $du_dd++) {
	if ($du_dd<10) {
		$du_dd="0".$du_dd;
	}
	$data_ur_option_dd.="<option value=\"$du_dd\">$du_dd</option>";
}

for ($du_mm=1; $du_mm<=12; $du_mm++) {
	if ($du_mm<10) {
		$du_mm="0".$du_mm;
	}
	$data_ur_option_mm.="<option value=\"$du_mm\">$du_mm</option>";
}

for ($du_rr=1920; $du_rr<=2000; $du_rr++) {
	$data_ur_option_rr.="<option value=\"$du_rr\">$du_rr</option>";
}
?>
	<form method="post" action="">
	<table>
	<tr><td>Imię:</td><td><input type="text" name="imie" size="15" maxlength="30" /></td></tr>
	<tr><td>Nazwisko:</td><td><input type="text" name="nazwisko" size="15" maxlength="50" /></td></tr>
	<tr><td>Adres:</td><td><input type="text" name="adres" size="15" maxlength="70" /></td></tr>
	<tr><td>Miejscowość:</td><td><input type="text" name="miejscowosc" size="15" maxlength="70" /></td></tr>
	<tr><td>Email:</td><td><input type="text" name="email" size="15" maxlength="50" /></td></tr>
	<tr><td>Telefon:</td><td><input type="text" name="telefon" size="15" maxlength="20" /></td></tr>
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
	<p><input type="submit" name="submit" value="Dodaj kontakt" /></p>
	</form>
	<br/><br/>
<?php
if (isset($_POST['submit']))
{
	$imie=addslashes(htmlspecialchars(trim($_POST["imie"])));
	$nazwisko=addslashes(htmlspecialchars(trim($_POST["nazwisko"])));
	$adres=addslashes(htmlspecialchars(trim($_POST["adres"])));
	$miejscowosc=addslashes(htmlspecialchars(trim($_POST["miejscowosc"])));
	$email=addslashes(htmlspecialchars(trim($_POST["email"])));
	$telefon=addslashes(htmlspecialchars(trim($_POST["telefon"])));

	if ((!$imie) || (!$nazwisko) || (!$email) || (!$telefon) || (!$adres) || (!$miejscowosc))
	{
		echo 'Proszę wypełnić wszystkie pola.<br />';
		$stronka->DomknijStrone();
		exit;
	}
// sprawdzenie czy podana data urodzenia istnieje
	$dudd=$_POST['dudd']; // dzień
	$dumm=$_POST['dumm']; // miesiąc
	$durr=$_POST['durr']; // rok

	if ((empty($durr)) || (empty($dumm)) || (empty($dudd)))
	{
		echo 'Błędna data.<br />Wprowadź pełną datę urodzenia.<br />';
		$stronka->DomknijStrone();
		exit;
	}
	if (checkdate($dumm,$dudd,$durr)===false)
	{
		echo 'Podana data urodzenia nie istnieje';
		$stronka->DomknijStrone();
		exit;
	}
	$data_ur=$_POST['durr'].'-'.$_POST['dumm'].'-'.$_POST['dudd'];

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
	if ($ilosc_email!=0)
	{
		echo 'Podany email już istnieje.<br />Wprowadź unikalny adres.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	$sql="INSERT INTO znajomi (imie, nazwisko, adres, miejscowosc, email, telefon, data_urodzenia) VALUES 
	(\"$imie\", \"$nazwisko\", \"$adres\", \"$miejscowosc\", \"$email\", \"$telefon\", \"$data_ur\")";

	$result=$mysqli->query($sql);
	if (!$result)
	{
		echo 'Błąd zapytania INSERT.<br />';
		$stronka->DomknijStrone();
		exit;
	}
	echo 'Kontakt zapisany.<br />';
	$mysqli->close();
} // if isset
$stronka->DomknijBlok(); // div kontakt
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>

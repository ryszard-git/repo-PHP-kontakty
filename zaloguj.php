<?php
session_start();
require_once("classes/Strona.php");
$stronka=new Strona();
$stronka->tytul="Logowanie";
$stronka->WyswietlNaglowek();
$stronka->OtworzBlok("menugorne");
$stronka->WyswietlMenu($stronka->menu_startowe);
$stronka->DomknijBlok(); //menugorne
$stronka->OtworzBlok("tresc");
$stronka->OtworzBlok("srodek");
?>
	<p>Logowanie</p>
	<br />
	<form method="post" action="">
	<table border="0">
	<tr><td>login:</td><td><input type="text" name="login_name" size="15" maxlength="25" /></td></tr>
	<tr><td>hasło:</td><td><input type="password" name="haslo" size="15" maxlength="25" /></td></tr>
	</table>
	<br />
	<p><input type="submit" name="submit" value="Zaloguj" /></p>
	</form>

<?php
if (isset($_POST['submit'])) {

	if ((!$_POST["login_name"]) || (!$_POST["haslo"])) {
		echo 'Proszę wprowadzić login i hasło<br />';
		$stronka->DomknijStrone();
		exit;
	}

	$login_name=addslashes(trim($_POST["login_name"]));
	$haslo=sha1(trim($_POST["haslo"]));

	require "config/config.php";

	@ $mysqli = new mysqli($host,$db_user,$db_passwd,$db_name);
	if ($mysqli->connect_error) {
		echo 'Próba połączenia z bazą nie powiodła się.<br />';
		echo $mysqli->connect_error;
		$stronka->DomknijStrone();
		exit;
	}

	$result=$mysqli->set_charset("utf8");
	if (!$result) {
		echo 'Błąd ustawienia kodowania.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	// szukanie pary login - hasło zgodnego z podanym w formularzu

	$sql="SELECT id_login FROM logins WHERE login=? AND haslo=?";
	$stmt=$mysqli->stmt_init();

	$stmt->prepare($sql); //Prepare an SQL statement for execution

	@ $result=$stmt->bind_param("ss",$login_name,$haslo); //Binds variables to a prepared statement as parameters
	if (!$result) {
		echo 'Błąd instrukcji bind_param.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	@ $result=$stmt->execute(); //Executes a prepared Query
	if (!$result) {
		echo 'Błąd zapytania login - hasło.<br />';
		$stronka->DomknijStrone();
		exit;
	}

	$stmt->store_result(); //Transfers a result set from a prepared statement

	$ilosc=$stmt->num_rows;

		if ($ilosc === 1) {
			$_SESSION['login_name']=$login_name;
			$_SESSION['zalogowany']="tak";
		} else {
			echo 'Nie zostałeś uwierzytelniony<br />';
			$stronka->DomknijStrone();
			exit;
		}

	$stmt->close();
	$mysqli->close();

	echo "<script>window.location.href = 'glowna.php';</script>";
} //if isset

$stronka->DomknijBlok(); // div srodek
$stronka->DomknijBlok(); // div tresc
$stronka->WyswietlStopke();
?>

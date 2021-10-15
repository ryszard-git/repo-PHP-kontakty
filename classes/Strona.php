<?php
class Strona
{
public $tytul;
public $menu_nologin=array("Strona startowa" => "index.php","Przeglądanie kontaktów" => "przeglad_kontaktow.php"); 
public $menu_startowe=array("Strona startowa" => "index.php");

public $menu_glowne=array("Strona główna" => "glowna.php",
			"Dodawanie kontaktów" => "dodaj_kontakt.php",
			"Przeglądanie kontaktów" => "przeglad_kontaktow.php",
			"Zmiana hasła" => "zmiana_hasla.php");

public function DomknijStrone()
{
// funkcja zapewnia poprawne wyświetlenie szablonu strony po przerwaniu skryptu funkcją exit
	$this->DomknijBlok();
	$this->DomknijBlok();
	$this->WyswietlStopke();
}

public function DomknijStrone1()
{
// funkcja zapewnia poprawne wyświetlenie szablonu strony po przerwaniu skryptu funkcją exit
	$this->DomknijBlok();
	$this->WyswietlStopke();
}

public function OtworzBlok($blok)
{
	echo '<div class="'.$blok.'">';
}

public function WyswietlNaglowek()
{
?>
<!DOCTYPE html>
<html lang="pl">    
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/kontakty.css">
<script src="js/kontakty.js" defer></script>
<title><?php echo $this->tytul; ?></title>
</head>
<body>
<div class="all">
	<div class="baner">
		<p>System zarządzania kontaktami</p>
	</div>

<?php
}

public function WyswietlMenuGlowne()
{
	$this->WyswietlMenu($this->menu_glowne);
}

public function WyswietlPasekLogin()
{
	$this->OtworzBlok("paseklogin");
	echo 'Zalogowany: <strong>'.$_SESSION["login_name"].'</strong> &nbsp;&nbsp; [ <a href="wyloguj.php">Wyloguj</a> ] &nbsp;&nbsp;';
	$this->DomknijBlok();
}

public function WyswietlMenu($przyciski)
{
	echo '<ul>';
	foreach ($przyciski as $nazwa=>$url)
	{
		$this->WyswietlPrzycisk($nazwa,$url,!$this->CzyToAktualnyURL($url));
	}
	echo '</ul>';
}

public function CzyToAktualnyURL($url)
{
	if (strpos($_SERVER['PHP_SELF'],$url)==false)
	{
		return false;
	}
	else
	{
		return true;
	}
}

public function WyswietlPrzycisk($nazwa,$url,$aktywny=true)
{
	if ($aktywny)
	{
		echo '<li><a href="'.$url.'">'.$nazwa.'</a></li>';
	}
	else
	{
		echo '<li><span class="przycisk">'.$nazwa.'</span></li>';
	}
}

public function DomknijBlok()
{
	echo '</div>';
}

public function WyswietlStopke()
{
?>
<div class="stopka">
	&copy; 2012 - 2021 Wszystkie prawa zastrzeżone<br/>
	<a href="mailto:ryszard@rkhost.strefa.pl">napisz do administratora aplikacji</a>
</div>
</div> <!-- div all -->
</body>
</html>
<?php
}
}
?>


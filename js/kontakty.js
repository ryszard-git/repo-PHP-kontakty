function zapytaj(idkontakt,akcja)
{
	switch(akcja)
	{
		//1 - usuniecie
		case 1:
			let wynik = confirm("Usunąć ten kontakt ?");
			if (wynik == true)
			{
				window.location.href = "usun_kontakt.php?id_kontakt=" + idkontakt;
			}
		break;
		
		//0 - edycja
		case 0:
			window.location.href = "edytuj_kontakt.php?id_kontakt=" + idkontakt;
		break;
		
		default:
			alert("Niewłaściwa opcja");
	}
}
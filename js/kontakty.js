function zapytaj(idkontakt)
{
	let wynik = confirm("Usunąć ten kontakt ?");
	if (wynik == true)
	{
		window.location.href = "usun_kontakt.php?id_kontakt=" + idkontakt;
	}
}

function edycja(idkontakt)
{
	window.location.href = "edytuj_kontakt.php?id_kontakt=" + idkontakt;
}
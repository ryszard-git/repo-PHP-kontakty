create database kontakty character set 'utf8' collate 'utf8_polish_ci';
use kontakty;

create table logins
(
id_login int unsigned not null auto_increment primary key,
login char(25) not null,
haslo char(40) not null
);

create table znajomi
(
id_kontakt int unsigned not null auto_increment primary key,
imie char(30) not null,
nazwisko char(50) not null,
adres char(70) not null,
miejscowosc char(70) not null,
email char(50) not null,
telefon char(20) not null,
data_urodzenia date not null
);


grant select, insert, update, delete on kontakty.*
to 'adres'@'localhost' identified by 'skok2012';


INSERT INTO logins (login, haslo)
	VALUES ("admin", sha1("admin"));


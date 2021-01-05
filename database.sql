drop database if exists examen;

create database examen;

use examen;

create table usertype(	
	medewerker_admin int not null AUTO_INCREMENT,
	type varchar(255) not null,
	created_at datetime not null,
	updated_at datetime not null,
	primary key(medewerker_admin)
);

create table medewerker(
	medewerkerscode int not null AUTO_INCREMENT,
	medewerker_admin int not null,
	voorletters varchar(20) not null,
	voorvoegsels varchar(7),
	achternaam varchar(25) not null,
	gebruikersnaam varchar(255) not null UNIQUE,
	wachtwoord varchar(255) not null UNIQUE,
	primary key(medewerkerscode),
	foreign key(medewerker_admin) references usertype(medewerker_admin)
);

create table leverancier(
	lev_code int not null AUTO_INCREMENT,
	leverancier varchar(255) not null,
	telefoon int not null,
	primary key(lev_code)

);

create table artikel(
	productcode int not null AUTO_INCREMENT,
	product varchar(255) not null,
	type varchar(255) not null,
	lev_code int not null,
	inkoopprijs decimal(10,0) not null,
	verkoopprijs decimal(10,0) not null,
	primary key(productcode),
	foreign key(lev_code) references leverancier(lev_code)
);

create table locatie(
	locatiecode int not null AUTO_INCREMENT,
	locatie varchar(255) not null,
	primary key(locatiecode)
);

create table voorraad(
	locatiecode int not null,
	productcode int not null,
	aantal int not null,
	foreign key(locatiecode) references locatie(locatiecode),
	foreign key(productcode) references artikel(productcode)
);

INSERT INTO artikel (`productcode`, `product`, `type`, `lev_code`, `inkoopprijs`, `verkoopprijs`) 
VALUES (NULL, 'vishengel', 'hengel', '1', '225,0', '250,0');

INSERT INTO `artikel` (`productcode`, `product`, `type`, `lev_code`, `inkoopprijs`, `verkoopprijs`) 
VALUES (NULL, 'visstoel', 'stoel', '2', '50,0', '60,0');

INSERT INTO `voorraad` (`locatiecode`, `productcode`, `aantal`) 
VALUES ('1', '2', '10'), ('1', '3', '15');

ALTER TABLE artikel DROP CONSTRAINT artikel_ibfk_1;

INSERT INTO usertype (`medewerker_admin`, `type`, `created_at`, `updated_at`) VALUES (NULL, 'klant', '2021-01-04 16:11:54', '2021-01-04 16:11:54');

create table klant(
	klantcode int not null AUTO_INCREMENT,
	medewerker_admin int not null,
	voorletters varchar(20) not null,	
	achternaam varchar(25) not null,
	gebruikersnaam varchar(255) not null UNIQUE,
	wachtwoord varchar(255) not null UNIQUE,
	primary key(klantcode),
	foreign key(medewerker_admin) references usertype(medewerker_admin)
);

create table reservaties(
	reservatiecode int not null AUTO_INCREMENT,
	productcode int not null,
	klantcode int not null,	
	achternaam varchar(25) not null,
	notes varchar(255),
	reservatie_datum date not null,
	reservatie_tijd time not null,
	primary key(reservatiecode),
	foreign key(productcode) references artikel(productcode),
	FOREIGN key(klantcode) REFERENCES klant(klantcode)
);

ALTER TABLE `reservaties` DROP `achternaam`;

ALTER TABLE reservaties DROP CONSTRAINT reservaties_ibfk_2;
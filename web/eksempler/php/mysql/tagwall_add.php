<?php
	# ----------------------------------------------------
	# Tilf�jer en besked til tabellen
	# ----------------------------------------------------

	# Inkluderer filen config.php der indeholder databasens navn, brugernavn og
	# password til databasen, det g�r det meget lettere at rette senere
	include("config.php");

	# �bner en forbindelse til MySQL serveren
	# Hvis det mislykkes skrives "Could not connect"
	$messages_link = mysql_connect(_MYSQL_SERVER_NAME, _MYSQL_USER_NAME, _MYSQL_PASSWORD) 
		or die ("Could not connect\n");

	# Hvis det foreg�ende mislykkedes s� skriv en fejlmedelse...
	if($messages_link == 0) {
		print "<hr>Det lykkedes ikke at �bne databasen\n";
	}

	# V�lger den rigtige database
	# Hvis det mislykkes skrives "Could not select database"
	mysql_select_db("FTAV", $messages_link)
		or die ("Could not select database\n");

	# S�tter indholdet af formen ind i tabellen
	$SQLQuery = "insert into Messages (Brug, Message) VALUES ('$user', '$message')";
	$result = mysql_query($SQLQuery);

	# Hvis det foreg�ende mislykkedes s� skriv en fejlmedelse...
	if($result == 0) {
		print "<hr>Skrivning til databasen mislykkedes\n";
	}

	# Lukker forbindelsen til databasen
	mysql_close($messages_link);

	# Nulstiller de forskellige variabler
	$user = "1";
	$message = "1";

	# Sender brugeren tilbage til besked siden...
	header("Location: tagwall.php");
?>

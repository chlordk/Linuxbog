<?php
	# Sletter en besked fra tabellen, beskedens ID overf�res via URL'en

	# Inkluderer filen config.php der indeholder databasens navn, brugernavn og
	# password til databasen, det g�r det meget lettere at rette senere
	include("config.php");

	# �bner en forbindelse til MySQL serveren
	# Hvis det mislykkes skrives "Could not connect"
	$messages_link = mysql_connect(_MYSQL_SERVER_NAME, _MYSQL_USER_NAME, _MYSQL_PASSWORD) 
		or die ("Could not connect\n");

	# V�lger den rigtige database
	# Hvis det mislykkes skrives "Could not select database"
	mysql_select_db ("FTAV") 
		or die ("Could not select database\n");

	# G�r en SQL foresp�rgsel klar.
	$query = "DELETE FROM Messages WHERE ID = '$ID'";

	# SQL foresp�rgslen udf�res og resultat gemmes i $result
	$result = mysql_query($query);

	# Unders�ger om foresp�rgslen fejlede
	if($result == 0) {
		print "Sletning mislykkedes\n";
	}

	# Forbindelsen til MySQL serveren lukkes
	mysql_close($messages_link);

	# Sender brugeren tilbage til besked siden...
	header("Location: tagwall.php");
?>

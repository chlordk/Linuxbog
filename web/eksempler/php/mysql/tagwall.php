<html>
<head>
<title>PHP sammen med MySQL</title>
</head>
<body>

<h1>Tagwall</h2>

<h2>Indtast ny besked</h2>
<FORM ACTION="tagwall_add.php" METHOD="GET" NAME="login">
	<table>
		<tr>
			<td width = "100"></td>
			<td></td>
		</tr>
		<tr>
			<td>Brugernavn:</td>
			<td><INPUT TYPE="text" NAME="user"></td>
		</tr>
		<tr>
			<td>Besked:</td>
			<td><INPUT TYPE="text" NAME="message"></td>
		</tr>
		<tr>
			<td></td>
			<td><INPUT TYPE="submit" VALUE="Tilf&oslash;j besked" NAME="submit"></td>
		</tr>
	</table>
</FORM>

<br>

<h2>Besked arkivet</h2>

<?php
	# ----------------------------------------------------
	# Viser listen af beskeder fra tabellen
	# ----------------------------------------------------

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
	$query = "SELECT Brug, Message, ID FROM Messages ORDER BY ID";

	# SQL foresp�rgslen udf�res og resultat gemmes i $result
	$result = mysql_query ($query)
		or die ("Query failed\n");

	# Tr�kker antallet af r�kker ud fra foresp�rgslen
	$number_of_rows = mysql_num_rows($result);

	# Starter den tabel der skal indeholde beskederne fra databasen
	print "<table>\n";
	print "	<tr>\n";
	print "		<th width = \"100\"><strong>Bruger</strong></th>\n";
	print "		<th width = \"15\">&nbsp; </th>\n";
	print "		<th width = \"400\"><strong>Besked</strong></th>\n";
	print "	</tr>\n";

	$i = 0;

	# Gentages s� l�nge der er flere beskeder i databasen
	while ($i < $number_of_rows) {
		# Er egentligt ikke n�dvendig, men g�r den senere kode lettere at l�se
		$bruger_navn = mysql_result($result, $i, 'Brug');
		$message     = mysql_result($result, $i, 'Message');
		$id          = mysql_result($result, $i, 'ID');

		# Tilf�jer en besked til tabellen	
		print "	<tr>\n";
		print "		<td>$bruger_navn</td>\n";
		print "		<td><a href='tagwall_remove.php?ID=$id'>x</a></td>\n";
		print "		<td>$message</td>\n";
		print "	</tr>\n";

		# R�kket�lleren �ges med 1
		$i++;
	}

	# Tabellen afsluttes
	print "</table>\n";
	print "<br>\n";

	# Forbindelsen til MySQL serveren lukkes
	mysql_close($messages_link);
?>

</body>
</html>

<html>
<?php

/*
smart.php / index.php
af Hans Schou <chlor@sslug.dk>
$Id$

Her skulle v�re et smart eksempel p� hvad PHP er god til,
og hvorfor s� ikke bare vise det script vi bruger til
at vise eksemplerne p� programmeringssprogene.
I virkligheden hedder denne fil "index.php" og der
er s� bare lavet en symbolic link til "smart.php".

 Der er f�lgende scripts:
  index.php - Denne fil
  proglang.inc - Listen med alle programmeringssprog
  show.php - Hvis kildetekst til et prog-sprog eksisterer
  list.php - List samlet en type eksempler, fx "hello"
  all.php - Hvis alle eksempler for et programmeringssprog eksisterer

 Ovenst�ende filer kan ses i din browser med kommandoen:
  http://www.sslug.dk/linuxbog/program/eksempler/udskriv.php?filnavn=show.php
*/

/* Tilf�j nye program-sprog i filen herunder */
require("proglang.inc");

?><h2><?php
	echo count($proglang);
?>
 Programeksempler til "Linux - Friheden til at programmere".
</h2>
Download tar-ball
<a href="linuxbog-program-eksempler.tgz">linuxbog-program-eksempler.tgz</a>
med alle eksempler.
<p>
Der findes mange flere sprog til Linux end de viste,
s� listen d�kker kun hvad vi selv har kunnet skrive.
Mangler dit favorit-sprog, modtager vi det gerne.
<p>
Der er f�lgende restriktioner til sprog der kan medtages:
<ul>
<li>Sproget skal findes til Linux</li>
<li>Sproget skal findes som "fri software" (Open Source)</li>
<li>Sproget skal indeholde kontrol-strukture al� if/for/while/goto</li>
<li>Sproget skal kunne porteres til en anden CPU-arkitektur</li>
</ul>

I listen er der sprog hvor der ikke er eksempler.
Kan du skrive lignende eksempler, modtager vi dem gerne.
Sprog med '?' er tvivlsomme om de findes til Linux.
<p>

Klik f.eks. p� "hello"-kolonnen for at f� alle eksempler med det klasiske
"Hello, world!" program.<br>
Klik p� et programsprognavn for at f� en samlet side med de tre
eksempler samlet.
<p>

<table border="1" cellspacing="0" cellpadding="3">
<tr bgcolor="#F0F0F0">
 <th>Navn</th>
<?php
  reset($examp);
  while (list($file) = each($examp))
    echo "<th><a href=\"list.php?file=$file\">$file</a>\n";
?>
</tr>
<?php
  // Genneml�b array for alle prog-sprog
  while (list($ext,$lang) = each($proglang)) {
    // Start et row og skriv et programsprog
    echo "<tr>\n";
		// T�l op om alle eksempler er der
    reset($examp);
		$gotall = 0;
    while (list($file) = each($examp))
			if (file_exists("$file.$ext"))
				$gotall++;
    echo " <td>";
		if ($gotall == count($examp))
    	echo "<a href=\"all?lang=$ext\"><b>$lang[navn]</b></a>";
		else
    	echo "$lang[navn]";
		echo "</td>\n";
    // Spol eksempel-navn listen tilbage
    reset($examp);
    // For alle eksempel-navne (der er 3)
    while (list($file) = each($examp)) {
      echo " <td>";
      // Lav en link til eksemplet, hvis det eksistere
      if (file_exists($f = "$file.$ext"))
        echo "<a href=\"show.php?file=$file&amp;ext=$ext\">$f</a>";
      else
        echo "$f";
      echo "</td>\n";
    }
    echo "</tr>\n";
  }
?>
</table>
<br>

Siden vedligeholdes af Hans Schou 
&lt;<a href="mailto:chlor@sslug.dk">chlor@sslug.dk</a>&gt;.

</html>

<?php
   // $Id$
   // F�rste version af Hans Schou.

   /* top.phtml sets 
      <!--DOCTYPE ....
      .....
      ... [topmenu] ...
   */
   $htmltitle_en="SSLUG - Liberty of searching in sgml files";
   $htmltitle_da="SSLUG - Friheden til at s�ge i b�ger";
   $htmltitle_sv="SSLUG - Friheden til at s�ke i b�cker";
   $bodyarg=" background=\"/grafix/linux-back-1.gif\" ";
   $maintain_name = "Hans Schou";       // Skriv dit navn her
   $maintain_email = "chlor@sslug.dk";  // Skriv din email adresse her
   @include($DOCUMENT_ROOT."/includes/top.phtml");

   list($width,$height) = getimagesize("front.png");
?>

<img src="front.png" alt="Friheden til at skrive b�ger"
 align="right" width="<? echo $width ?>" height="<? echo $height ?>">
<h1>SSLUG - Friheden til at s�ge i sgml-filer</h1>

<form action="<?php echo $PHP_SELF ?>" method="get">
S�g efter:
<input type="text" name="q" value="<?php echo $q ?>" size="40">
<input type="submit" name="s" value="Submit">
<br>
<font size="-1">Der s�ges med <a href="friheden/bog/joker-redir-pipe.html#REGEXP">regul�re udtryk</a> - case insentive</font>
</form>

<?php

function htmllink( $sgmlfil ) {
  return "<a href=\"$sgmlfil\">$sgmlfil</a>";
}

function searchinfile( $file, $q ) {
  $count = 0;
  $line = 0;
  $fd = fopen($file, "r");
  while (!feof($fd)) {
    $str = fgets($fd, 1024);
    $line++;
    if (preg_match("|$q|i", $str)) {
      if (!$count) echo "<p>\n";
      $count++;
      echo "<b>vi ".htmllink($file)." +$line</b>\n";
      echo "<br>&nbsp;\n";
      echo "<i>".htmlentities($str)."</i><br>\n";
      flush();
    }
  }
  fclose($fd);
  if ($count) echo "</p>\n\n";
  return $count;
}

function searchdir( $dir, $q ) {
  $count = 0;
  $d = dir($dir);
  while ($fil = $d->read())
    if (is_file($dir."/".$fil) && preg_match("|^[a-z0-9-_]+.+\.sgml$|", $fil))
      $count += searchinfile("$dir/$fil", $q);
  $d->close();
  return $count;
}

/*
 Man er doven. Vi gider kun at s�ge i de filer der
 ligger i de sub-dir der i dette sub-dir.
*/

if ($q) {
  flush();
  $d = dir(".");
  while ($dir = $d->read())
    if (is_dir($dir) && preg_match("|^[a-z0-9]+$|", $dir))
      $count += searchdir($dir, $q);
  $d->close();
}

?>

<p>
<!-- Text slut -->
<!-- Husk din email-adresse: -->
<?php
  @include($DOCUMENT_ROOT."/includes/bottom.phtml");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
       "http://www.w3.org/TR/html40/loose.dtd">
<HTML>
<HEAD>
<?php

  $htmlmeta[] = "HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=iso-8859-1\"";
  if (is_array($htmlmeta))
    while (list(,$value) = each($htmlmeta))
      echo "<META $value>\n";

?>
<title>Friheden til at vælge</title>

<style type="text/css">
/* From PhpWiki: <a href> looks like buttons */
a.button {
  /*color: black;*/
  text-decoration: none;
  font-weight: bold;
  font-size: 11pt;
  border-top:  1px solid #c6d2de;
  border-left: 1px solid #c6d2de;
  border-bottom: 2px solid #494f5d;
  border-right:  2px solid #494f5d;
  padding-top:    0.2ex;
  padding-bottom: 0.1ex;
  padding-left:  .5em;
  padding-right: .5em;
}
</style>
</HEAD>


<body>
<?php
   // $Id$
   // Første version af Hans Schou. Dernæst har Peter og Hans rettet i et væk.

   /* top.phtml sets 
      <!--DOCTYPE ....
      .....
      ... [topmenu] ...
   */
   /*
   Når 'register_globals' er slået fra så gå ind i VI og:
   :%s/\$t\>/$_GET["t"]/g
   if (!ini_get('register_globals')) {
   	echo "<h1>'register_globals' er slået fra i /etc/php.ini</h1><hr>\n";
   	phpinfo(32);
   }
   */
   $htmltitle_en="Liberty of writing books";
   $htmltitle_da="Friheden til at skrive bøger";
   $htmltitle_sv="Friheden til at skrive böcker";
   $bodyarg=" background=\"/grafix/linux-back-1.gif\" ";
   $maintain_name = "Hans Schou";       // Skriv dit navn her
   $maintain_email = "chlor@schou.dk";  // Skriv din email adresse her
   if (file_exists($DOCUMENT_ROOT."includes/top.phtml")) {
     include($DOCUMENT_ROOT."includes/top.phtml");
   } else if (file_exists($f="top.php")) {
     include($f);
   }
   if (file_exists($f = "configureoptions.php")) {
     include($f);
     if (preg_match("/enable-submitbox/", $configureoptions)) {
       $USE_SUBMIT_INC = ".php";
     } else {
       $USE_SUBMIT_INC = "";
     }
   }

   list($width,$height) = getimagesize("front.png");

if (!$_GET["b"] && !$_GET["t"] && !$_GET["matrix"]) { ?>
<img src="front.png" alt="Friheden til at skrive bøger"
 align=right width="<? echo $width ?>" height="<? echo $height ?>">
<?php } ?>

<h1>Friheden til at skrive bøger</h1>
 
<?php

// Funktion til at lave hyperlink
function href($url,$desc) {
  return "<a href=\"$url\">$desc</a>";
}

// Returner filstørrelse i human readable format
function fsize_text( $filename ) {
  // ISO filstørrelser, ex: 5.5 MB
  $ISO = array("","k","M","G","T","P");
  $filesize = filesize($filename);
  if (! $filesize) {
      return "000 B";  // file_exists() skulle være checket her
  } else {
    $base = floor(floor(log10($filesize))/3);
    $num3 = $filesize/pow(1024,$base);
    return substr($num3,0,(strpos($num3,".")>2?3:4))." ".$ISO[$base]."B";
  }
}

// Funktion til at formatere filnavne.
// Online versionen skal kun have bookname een gang
// "Ændringer" skal navnet med to gange, men uden version
function form_filename( $bookname, $format ) {
  global $books;
  switch ($format[online]) {
    case 1:
      // Onlinebøger, Eks: admin/bog/index.html
      return "$bookname/$format[first]/$format[last]";
      break;
    case 2:
      // Ændringer, Eks: admin/bog/admin-apprevhist.html
      return "$bookname/$format[first]/$bookname-$format[last]";
      break;
    case 3:
      // Onlinebøger, Eks: admin/todo.html
      return "$bookname/$format[last]";
      break;
    default:
      $last = $format[last];
      /* UNDTAGELSE: søg efter "samling" */
      if ($bookname == "samling" && $last == ".pdf.gz") {
	echo "\n<!-- SIKKE NOGET GRIS. SÅDAN BØR MAN IKKE PROGRAMMERE. (chlor) -->\n\n";
        $last = ".tar.gz";
      }
      # Eks: admin/linuxbog-admin-ps-5.6.tar.gz
      #      admin      /  linuxbog-      admin     -ps-          5.6                        .tar.gz
      return $bookname."/".$format[first].$bookname.$format[form].$books[$bookname][version].$last;
  }
}

function visbog( $short ) {
  global $books, $packs;
  $desc = $books[$short];
  echo "<a name=\"$short\"></a><h3>$desc[title] \n";
  echo "</h3>\n<i>$desc[comment]</i><p>";

  if ($desc[dato])
    echo $desc[dato];
  if ($desc[version])
    echo " - version $desc[version]";
  if ($desc[sideantal])
    echo " - Antal sider: ".$desc[sideantal];

  echo "<p>";
  echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"#F8F8E0\">\n<tr>\n";
  echo "<th>Bøger</th>\n";
  echo "<th>Link</th>\n";
  echo "<th>Dato</th>\n";
  echo "<th>Bytes</th>\n";
  echo "</tr>\n";
  // Her kommer listen over filtyper
  reset($packs);
  while (list($type,$attr) = each($packs)) {
    echo "<tr>\n";
    $filename = form_filename($short, $attr );
    echo "<td><b>$type</b></td>\n";
    if (file_exists($filename)) {
      echo "<td>".href($filename,"<b>$filename</b>")."</td>\n";
      $date = date("Y-m-d",filemtime($filename));
      $filesize = fsize_text($filename);
    } else {
      echo "<td><i>$filename</i></td>\n";
      $date = " - ";
      $filesize = " - ";
    }
    echo "<td align=\"center\">$date</td>\n";
    if ($attr[online])
      echo "<td>&nbsp;</td>\n";
    else
      echo "<td align=\"right\">$filesize</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
  echo "<p><hr align=\"left\" width=\"70%\">\n";
}

function vistype($type) {
  global $books, $packs;
  $attr = $packs[$type];
  echo "<a name=\"".rawurlencode($type)."\"></a><h3>$type</h3>\n";
  echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"#E0F8F8\">\n<tr>\n";
  echo "<th>Bøger</th>\n";
  echo "<th>Link</th>\n";
  echo "<th>Dato</th>\n";
  echo "<th>Bytes</th>\n";
  echo "</tr>\n";
  reset($books);
  while (list($short,$desc) = each($books)) {
    echo "<tr>\n";
    $filename = form_filename($short, $attr );
    echo "<td><b>$short</b></td>\n";
    if (file_exists($filename)) {
      echo "<td>".href($filename,"<b>$filename</b>")."</td>\n";
      $date = date("Y-m-d",filemtime($filename));
      $filesize = fsize_text($filename);
    } else {
      echo "<td><i>$filename</i></td>\n";
      $date = " - ";
      $filesize = "00 B";
    }
    echo "<td>$date</td>\n";
    if ($attr[online])
      echo "<td>&nbsp;</td>\n";
    else
      echo "<td align=\"right\">$filesize</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
}

if (!file_exists("titleabstract.php")) {
	echo "<h1>Panic! 'titleabstract.php' does not exist.</h1>"; /* $books mangler */
	exit;
} else {
	require("titleabstract.php");
	if (count($books) < 1) {
		echo "<h1>Ingen title i 'titleabstract.php'<h1>";
	}
}

  $ext_files = array(
    "version" => "/version.sgml",
    "sideantal" => "/sideantal.txt",
    "dato" => "/dato.sgml"
  );

  // Sæt versionsnumre, sideantal og dato på bøgerne
  $totalsideantal = 0;
  reset($books);
  while (list($bookname) = each($books)) {
    reset($ext_files);
    while (list($ftype,$fname) = each($ext_files)) {
      if (file_exists($bookname.$fname)) {
        $num = file($bookname.$fname);
        $books[$bookname][$ftype] = trim($num[0]);
        if ($ftype == "sideantal")
          $totalsideantal += trim($num[0]);
      }
    }
  }


  // Bogpakker pakket på forskellige måder
  // <first><$books><last>
  $packs = array(
    // Eks: frihed/bog/index.html
    "Online" => array(
      first => "bog",
      form => "",
      last => "index.html".$USE_SUBMIT_INC,
      online => 1  // Hvis bognavn kun skal med een gang
    ),
    // Eks: frihed/bog/index.html
    "Ændringer" => array(
      first => "bog",
      form => "",
      last => "apprevhist.html".$USE_SUBMIT_INC,
      online => 2  // "Ændringer" har bognavn med to gange
    ),
    // Eks: frihed/linuxbog-friheden-html-4.0.tar.gz
    "HTML" => array(
      first => "linuxbog-",
      form => "-html-",
      last => ".tar.gz"
    ),
    "PNG billeder" => array(
      first => "linuxbog-",
      form => "-png-",
      last => ".tar.gz"
    ),
    "HTML u/billeder" => array(
      first => "linuxbog-",
      form => "-htmlub-",
      last => ".tar.gz"
    ),
    /* Eks: linuxbog-frihed-pdf-5.7.pdf.gz */
    "PDF" => array(
      first => "linuxbog-",
      form => "-pdf-",
      /* UNDTAGELSE: søg efter "samling" */
      last => ".pdf.gz"
    ),
    "Palm Pilot" => array(
      first => "linuxbog-",
      form => "-palm-",
      last => ".tar.gz"
    ),
    "Eksempler" => array(
      first => "linuxbog-",
      form => "-eksempler-",
      last => ".tar.gz"
    ),
    "Kildetekst" => array(
      first => "linuxbog-",
      form => "-dist-",
      last => ".tar.gz"
    ),
    "TODO" => array(
      first => ".",
      form => "",
      last => "todo.html",
      online => 3  
    )
  );

  $bgcolor = array("#FFFFFF","#E8E8E8");

  // Vis en bog med alle dens typer
  if (strlen($_GET["b"]) && is_array($books[$_GET["b"]]) ) {
    visbog($_GET["b"]);
    echo "<p>\n";
  }

  // Vis en type med alle dens bøger
  if (strlen($_GET["t"]) && is_array($packs[$_GET["t"]])) {
    vistype($_GET["t"]);
    echo "<p>\n";
  }

  // Vis alle bøger
  if ($_GET["all"] == "b") {
    echo "<h2>Bøger</h2>\n";
    // Liste over alle bøger. Alle bøger har hver sin tabel med forskellig filtyper
    reset($books);
    while (list($short,$desc) = each($books)) {
      visbog($short);
    }
  }

  // Vis alle filtyper
  if ($_GET["all"] == "t") {
    echo "<h2>Filtyper</h2>\n";
    // Liste over filtype. Hver filtype har sin egen tabel med alle bogtitler.
    reset($packs);
    while (list($type,$attr) = each($packs)) {
      vistype($type);
    }
  }

if ($_GET["matrix"]) { ?>
<hr>
<a name="matrix"></a>
<h2>Samlet bogoversigt</h2>
<table border="1" cellspacing="0" cellpadding="3">
<tr bgcolor="#F0F0FF"><th>Bøger/filtype</th>
<?php

  // Stor tabel med alle bøger og filtyper samlet.
  reset($packs);
  list($type) = current($packs);
  while (list($type) = each($packs)) {
    echo " <th>$type</th>\n";
  }
  echo "</tr>\n";
  
  $notexists = array();
  $fcount = 0;
  $c = 0;
  reset($books);
  while (list($short,$desc) = each($books)) {
    echo "<tr bgcolor=\"".$bgcolor[++$c & 1]."\">\n <td valign=\"top\">";
    echo "<b>$desc[title]</b><font size=\"-1\"><br>$desc[comment]</font></td>\n";
    reset($packs);
    while (list($type,$attr) = each($packs)) {
      $fcount++;
      echo " <td valign=\"top\"><font size=\"-1\">";
      $filename = form_filename( $short, $attr );
      $filetext = "$short $type";
      if (!file_exists($filename)) {
	$notexists[] = $filename;
        echo "<i>$filetext</i>";
        $date = " - ";
      } else {
        $filesize = fsize_text($filename);
        $date = date("Y-m-d",filemtime($filename));
        echo "ver $desc[version] ";

        //echo "<br>$date<br>$filesize";
	$linktext = "$date<br>$filesize";
        echo href($filename,$linktext);
      }
      echo "</font></td>\n";
    }
    echo "</tr>\n";
  }
?>
</table>
<p>
<?php }

if (count($notexists))
	echo "<p>Ud af $fcount filer, er der ".count($notexists)." der ikke findes online.</p>\n";

?>

<a class="button" href="http://www.linuxbog.dk/">Seneste udgaver</a>
<a class="button" href="http://cvs.linuxbog.dk">Beta-upgaver</a>
<a class="button" href="laese-vejledning.html">Læsevejledning</a>
<a class="button" href="hjaelpe.html">Om at hjælpe</a>
<a class="button" href="http://cvs.linuxbog.dk/cvs2html/cvs_crono.html">Følg ændringer</a>
<p>
<a class="button" href="?matrix=1">Samlet bogoversigt</a>
<a class="button" href="?all=b">Alle bøger</a>
<a class="button" href="?all=t">Alle filtyper</a>
<a class="button" href="search.php">Søg</a>

<?php
	if (file_exists("idx-a.html")) {
		echo "<p><a name=\"stikord\">Stikord</a>:\n";
		for ($c=65; $c < 91; $c++) {
			if (file_exists($f = "idx-".chr($c+32).".html")) {
				echo "<a href=\"$f\">&nbsp;".chr($c)."&nbsp;</a>\n";
			}
		}
		if (file_exists($f = "idx-symboler.html")) {
			echo "<a href=\"$f\">Symboler</a>\n";
		}
		echo "</p>\n";
	}
?>

<h2>Vi har følgende bøger</h2>
<p>
Filtyper: 
<?php

  // Kort liste over filtyper: "Online, HTML..."
  reset($packs);
  while (list($type) = each($packs)) {
    // Nogle filtyper har mellemrum i navnet, derfor bruges rawurlencode()
    $raw = rawurlencode($type);
    echo "<a href=\"?t=$raw\">$type</a>\n";
  }

?>
<p>
<ul>
<?php

  // Pæn bullet liste over alle bog-titler med kort beskrivelse
  reset($books);
  while (list($short,$desc) = each($books)) {
    echo "<li><b>$short:</b> ";
    $raw = rawurlencode($short);
    echo href("?b=$raw","<b>$desc[title]</b>");
    if ($desc[sideantal])
      echo ", $desc[sideantal] sider";
    echo "<br> $desc[comment]";
    echo "</li>\n";
  }
?>
</ul>

<p>
Det samlede sideantal for alle bøger er <b><?php echo $totalsideantal ?></b> sider.
</p>

<hr>
<?php
  include("sidsteudgave.incl");
?>

<hr>

<p>
Bøgerne er udgivet under 
<a href="licens.html">Åben dokumentlicens</a>
hvilket gør at forfatterne ikke er involveret i de trykte udgaver.
</p>

<p>
Bøgerne redigeres af Peter Toft, Jacob Sparre Andersen, Hans Schou,
Gitte Wange, Peter Makholm, Mads Dydensborg og
Donald Axel - 
&lt;<a href="mailto:linuxbog@sslug.dk">linuxbog@sslug.dk</a>&gt;.
<br>
Indholdet af bøgerne diskuteres på
&lt;<a href="mailto:sslug-bog@sslug.dk">sslug-bog@sslug.dk</a>&gt;.
</p>

<p>
Vil du følge med i hvad der sker med vores kilde-kode (SGML/dist-filerne), 
<a href="http://cvs.linuxbog.dk/">så se her</a>.
</p>

<p>
Hvis du har noget du søger efter, så skal du nok starte i
<a href="#stikord">stikordregisteret øverst på siden</a>.
</p>

<p>
Se også vores konkurrenters bøger ;-)
</p>

<ul>
<li>"Debianguiden" på <a href="http://www.debianguiden.dk/">www.debianguiden.dk/</a></li>
<li><a href="http://www.skibhist.dk/download/download.html">Håndbog i OpenOffice.org</a></li>
</ul>

<p>
Vil du hjælpe med, så læs <a href="hjaelpe.html">mere her</a>.
</p>

<a href="licens.html"><img src="licens/licens.png" alt="ÅDL"></a>


<p>
<!-- Text slut -->
<!-- Husk din email-adresse: -->
<!--<?php
	if (file_exists($DOCUMENT_ROOT."includes/bottom.phtml")) {
		include($DOCUMENT_ROOT."includes/bottom.phtml");
	} else if (file_exists($f="bot.php")) {
		include($f);
	}
?>-->

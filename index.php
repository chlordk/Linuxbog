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
<title>Friheden til at v�lge</title>
</HEAD>


<body>
<?php
   // $Id$
   // F�rste version af Hans Schou. Dern�st har Peter og Hans rettet i et v�k.

   /* top.phtml sets 
      <!--DOCTYPE ....
      .....
      ... [topmenu] ...
   */
   $htmltitle_en="Liberty of writing books";
   $htmltitle_da="Friheden til at skrive b�ger";
   $htmltitle_sv="Friheden til at skrive b�cker";
   $bodyarg=" background=\"/grafix/linux-back-1.gif\" ";
   $maintain_name = "Hans Schou";       // Skriv dit navn her
   $maintain_email = "chlor@sslug.dk";  // Skriv din email adresse her
   if (file_exists($DOCUMENT_ROOT."includes/top.phtml")) {
     include($DOCUMENT_ROOT."includes/top.phtml");
   } else if (file_exists($f="top.php")) {
	include($f);
   }

   list($width,$height) = getimagesize("front.png");

if (!$b && !$t && !$matrix) { ?>
<img src="front.png" alt="Friheden til at skrive b�ger"
 align=right width="<? echo $width ?>" height="<? echo $height ?>">
<?php } ?>

<h1>Friheden til at skrive b�ger</h1>
 
<?php

// Funktion til at lave hyperlink
function href($url,$desc) {
  return "<a href=\"$url\">$desc</a>";
}

// Returner filst�rrelse i human readable format
function fsize_text( $filename ) {
  // ISO filst�rrelser, ex: 5.5 MB
  $ISO = array("","k","M","G","T","P");
  $filesize = filesize($filename);
  if (! $filesize) {
      return "000 B";  // file_exists() skulle v�re checket her
  } else {
    $base = floor(floor(log10($filesize))/3);
    $num3 = $filesize/pow(1024,$base);
    return substr($num3,0,(strpos($num3,".")>2?3:4))." ".$ISO[$base]."B";
  }
}

// Funktion til at formatere filnavne.
// Online versionen skal kun have bookname een gang
// "�ndringer" skal navnet med to gange, men uden version
function form_filename( $bookname, $format ) {
  global $books;
  switch ($format[online]) {
    case 1:
      // Onlineb�ger, Eks: admin/bog/index.html
      return "$bookname/$format[first]/$format[last]";
      break;
    case 2:
      // �ndringer, Eks: admin/bog/admin-apprevhist.html
      return "$bookname/$format[first]/$bookname-$format[last]";
      break;
    case 3:
      // Onlineb�ger, Eks: admin/todo.html
      return "$bookname/$format[last]";
      break;
    default:
      # Eks: admin/linuxbog-admin-ps-5.6.tar.gz
      #      admin      /  linuxbog-      admin     -ps-          5.6                        .tar.gz
      return $bookname."/".$format[first].$bookname.$format[form].$books[$bookname][version].$format[last];
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
  echo "<th>B�ger</th>\n";
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
  echo "<th>B�ger</th>\n";
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

  // B�ger
  $books = array(
    // Bognavn, forkortet
    "friheden" => array(
      title => "Linux - Friheden til at v�lge installation",
      comment => "Hj�lp til installation",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "unix" => array(
      title => "Linux - Friheden til at l�re Unix",
      comment => "L�r element�r Unix",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "wm" => array(
      title => "Linux - Friheden til at v�lge grafisk brugergr�nseflade",
      comment => "L�r hvilken grafisk brugergr�nseflade som passer dig bedst",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "applikationer" => array(
      title => "Linux - Friheden til at v�lge programmer",
      comment => "V�lg programmer til Linux",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "kontorbruger" => array(
      title => "Linux - Friheden til at v�lge kontorprogrammer",
      comment => "Basal anvendelse af Linux kontorprogrammer",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "admin" => array(
      title => "Linux - Friheden til systemadministration",
      comment => "Administrer din egen Linux-server",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "program" => array(
      title => "Linux - Friheden til at programmere",
      comment => "Programmering p� Linux",
      auth => array(
        "Jacob Sparre Andersen" => "sparre@sslug.dk",
        "Peter Toft" => "pto@linuxbog.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "c" => array(
      title => "Linux - Friheden til at programmere i C",
      comment => "Programmering i C",
      auth => array(
        "Donald Axel" => "donald_j_axel@get2net.dk"
      )
    ),
    "java" => array(
      title => "Linux - Friheden til at programmere i Java",
      comment => "Programmering i Java",
      auth => array(
        "Christian Damsgaard" => "damsgaard@image.dk"
      )
    ),
    "sikkerhed" => array(
      title => "Linux - Friheden til sikkerhed p� internettet",
      comment => "Sikkerhed omkring din Linux-boks",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "web" => array(
      title => "Linux - Friheden til egen webserver",
      comment => "Web og databaser",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "dokumentation" => array(
      title => "Linux - Friheden til at skrive dokumentation",
      comment => "Skrive dokumentation under Linux",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "kontorbruger" => array(
      title => "Linux - Friheden til at v�lge kontorprogrammer",
      comment => "Basal anvendelse af Linux-programmer",
      auth => array(
        "Peter Toft" => "pto@linuxbog.dk"
      )
    ),
    "forsker" => array(
      title => "Linux - Friheden til at studere og forske",
      comment => "En begynderbog for samfundsfagfolk og humanister",
      auth => array(
        "Janus Sandsgaard" => "janus@janus.dk"
      )
    ),
    "signatur" => array(
      title => "Linux - Friheden til at v�lge digital signatur",
      comment => "Digital signatur p� Linux",
      auth => array(
        "Mads Bondo Dydensborg" => "madsdyd@challenge.dk"
      )
    ),
    "itplatform" => array(
      title => "Linux - Friheden til at v�lge IT-l�sning",
      comment => "Valg af Linux-baseret IT-l�sning",
      auth => array(
        "Michael Rasmussen" => "mir@miras.org",
        "Peter Toft" => "pto@linuxbog.dk"
     )
    ),
   "samling" => array(
     title => "Linux - Friheden til at skrive b�ger",
     comment => "En pakke med alle b�gerne i",
     auth => array(
       "Peter Toft" => "pto@linuxbog.dk",
       "Henrik Christian Grove" => "grove@sslug.dk",
       "Hans Schou" => "chlor@sslug.dk"
     )
    ),
   "alle" => array(
     title => "Linux - Friheden til at skrive b�ger",
     comment => "Alle b�gerne samlet til �n stor bog",
     auth => array(
       "Peter Toft" => "pto@linuxbog.dk",
       "Hans Schou" => "chlor@sslug.dk"
     )
   )
  );

  $ext_files = array(
    "version" => "/version.sgml",
    "sideantal" => "/sideantal.txt",
    "dato" => "/dato.sgml"
  );

  // S�t versionsnumre, sideantal og dato p� b�gerne
  $totalsideantal = 0;
  reset($books);
  while (list($bookname) = each($books)) {
    reset($ext_files);
    while (list($ftype,$fname) = each($ext_files)) {
      if (file_exists($bookname.$fname)) {
        $num = file($bookname.$fname);
        $books[$bookname][$ftype] = trim($num[0]);
        if ($ftype == "sideantal" && $bookname != "alle")
          $totalsideantal += trim($num[0]);
      }
    }
  }


  // Bogpakker pakket p� forskellige m�der
  // <first><$books><last>
  $packs = array(
    // Eks: frihed/bog/index.html
    "Online" => array(
      first => "bog",
      form => "",
      last => "index.html",
      online => 1  // Hvis bognavn kun skal med een gange
    ),
    // Eks: frihed/bog/index.html
    "�ndringer" => array(
      first => "bog",
      form => "",
      last => "apprevhist.html",
      online => 2  // "�ndringer" har bognavn med to gange
    ),
    // Eks: frihed/linuxbog-friheden-html-4.0.tgz
    "HTML" => array(
      first => "linuxbog-",
      form => "-html-",
      last => ".tar.gz"
    ),
    "HTML zip" => array(
      first => "linuxbog-",
      form => "-html-",
      last => ".zip"
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
    // Eks: linuxbog-frihed-pdf.zip
    "PDF" => array(
      first => "linuxbog-",
      form => "-",
      last => ".pdf"
    ),
    "PalmPilot" => array(
      first => "linuxbog-",
      form => "-palm-",
      last => ".zip"
    ),
    "eksempler" => array(
      first => "linuxbog-",
      form => "-eksempler-",
      last => ".tar.gz"
    ),
    "SGML" => array(
      first => "linuxbog-",
      form => "-sgml-",
      last => ".tar.gz"
    ),
    "SGMLzip" => array(
      first => "linuxbog-",
      form => "-sgml-",
      last => ".zip"
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
  if (strlen($b) && is_array($books[$b])) {
    visbog($b);
    echo "<p>\n";
  }

  // Vis en type med alle dens b�ger
  if (strlen($t) && is_array($packs[$t])) {
    vistype($t);
    echo "<p>\n";
  }

  // Vis alle b�ger
  if ($all == "b") {
    echo "<h2>B�ger</h2>\n";
    // Liste over alle b�ger. Alle b�ger har hver sin tabel med forskellig filtyper
    reset($books);
    while (list($short,$desc) = each($books)) {
      visbog($short);
    }
  }

  // Vis alle filtyper
  if ($all == "t") {
    echo "<h2>Filtyper</h2>\n";
    // Liste over filtype. Hver filtype har sin egen tabel med alle bogtitler.
    reset($packs);
    while (list($type,$attr) = each($packs)) {
      vistype($type);
    }
  }

if ($matrix) { ?>
<hr>
<a name="matrix"></a>
<h2>Samlet bogoversigt</h2>
<table border="1" cellspacing="0" cellpadding="3">
<tr bgcolor="#F0F0FF"><th>B�ger/filtype</th>
<?php

  // Stor tabel med alle b�ger og filtyper samlet.
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

<font size="-1">
[<a href="http://www.linuxbog.dk/">Seneste udgaver</a>]
[<a href="http://cvs.linuxbog.dk">Beta-upgaver</a>]
[<a href="laese-vejledning.html">L�sevejledning</a>]
[<a href="hjaelpe.html">Om at hj�lpe</a>]
[<a href="http://cvs.linuxbog.dk/cvs2html/cvs_crono.html">F�lg �ndringer</a>]
<br>
[<a href="?matrix=1">Samlet bogoversigt</a>]
[<a href="?all=b">Alle b�ger</a>]
[<a href="?all=t">Alle filtyper</a>]
[<a href="search.php">S�g</a>]
[<a href="alle/bog/stikord.html">Stikord</a>]
</font>

<h2>Vi har f�lgende b�ger</h2>
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

  // P�n bullet liste over alle bog-titler med kort beskrivelse
  reset($books);
  while (list($short,$desc) = each($books)) {
    echo "<li><b>$short:</b> ";
    $raw = rawurlencode($short);
    echo href("?b=$raw","<b>$desc[title]</b>");
    echo "<br> $desc[comment]";
    if ($desc[sideantal])
      echo ", $desc[sideantal] sider.";
    echo "</li>\n";
  }
?>
</ul>

<p>
Det samlede sideantal for alle b�ger er <b><?php echo $totalsideantal ?></b> sider.
</p>

<hr>
<?php
  include("sidsteudgave.incl");
?>

<hr>
<p>
Benyt vores "Et-klik-service" for at downloade og l�se b�gerne,
eller k�b en indbundet udgave hos f�lgende forretninger:
<ul>
<!-- <li><a href="http://www.pn-tryk.dk/html/linux.html">PN-tryk</a></li> -->
<li><a href="http://linuxpusher.dk/docs/linuxboeger.php">SC-Birker�d i samarbejde med www.linuxpusher.dk</a></li>
</ul>
<p>
B�gerne er udgivet under 
<a href="licens.html">�ben dokumentlicens</a>
hvilket g�r at forfatterne ikke er involveret i de trykte udgaver.
</p>


<FORM METHOD="GET" ACTION="http://www.netmind.com/cgi-bin/uncgi/url-mind">
Vil du modtage E-post om nye versioner af bogen?
<BR>Skriv din E-post adresse her
<BR><INPUT TYPE=TEXT SIZE=40 NAME="required-email">
<BR><INPUT TYPE=HIDDEN VALUE="http://www.linuxbog.dk" NAME="url">
<INPUT TYPE=SUBMIT VALUE="Indsend form"></FORM>

<p>
B�gerne redigeres af Peter Toft, Jacob Sparre Andersen, Hans Schou,
Donald Axel og flere andre - 
&lt;<a href="mailto:linuxbog@sslug.dk">linuxbog@sslug.dk</a>&gt;.
<br>
Indholdet af b�gerne diskuteres p�
&lt;<a href="mailto:sslug-bog@sslug.dk">sslug-bog@sslug.dk</a>&gt;.
</p>

<p>
Vil du f�lge med i hvad der sker med vores kilde-kode (SGML-filerne), 
<a href="http://cvs.linuxbog.dk/">s� se her</a>.
</p>


<p>
Hvis du har noget du s�ger efter, s� skal du nok <a
href="alle/bog/stikord.html">starte i vores samlede
indeks-register.</a>
</p>

<p>
Se ogs� vores konkurrenters b�ger ;-)
</p>

<ul>
<li>"Linux i skolen" p� <a href="http://www.gnuskole.dk">www.gnuskole.dk</a></li>
<li>"Debianguiden" p� <a href="http://www.debianguiden.dk/">www.debianguiden.dk/</a></li>
</ul>

<p>
Vil du hj�lpe med, s� l�s <a href="hjaelpe.html">mere her</a>.
</p>

<a href="licens.html"><img src="licens/licens.png" alt="�DL"></a>


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

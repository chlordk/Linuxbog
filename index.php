<?php
   // $Id$
   // F�rste version af Hans Schou. Dern�st har Peter og Hans rettet i et v�k.

   /* top.phtml sets 
      <!--DOCTYPE ....
      .....
      ... [topmenu] ...
   */
   $htmltitle_en="SSLUG - Liberty of writing books";
   $htmltitle_da="SSLUG - Friheden til at skrive b�ger";
   $htmltitle_sv="SSLUG - Friheden til at skrive b�cker";
   $bodyarg=" background=\"/grafix/linux-back-1.gif\" ";
   $maintain_name = "Hans Schou";       // Skriv dit navn her
   $maintain_email = "chlor@sslug.dk";  // Skriv din email adresse her
   @include($DOCUMENT_ROOT."/includes/top.phtml");

   list($width,$height) = getimagesize("front.png");

if (!$b && !$t && !$matrix) { ?>
<img src="front.png" alt="Friheden til at skrive b�ger"
 align="right" width="<? echo $width ?>" height="<? echo $height ?>">
<?php } ?>

<h1>SSLUG - Friheden til at skrive b�ger</h1>

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
    default:
      // Eks: admin/linux-admin-1.0.ps.gz
      return "$bookname/$format[first]$bookname-".$books[$bookname][version]."$format[last]";
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
      title => "Linux - Friheden til at v�lge",
      comment => "En god begynderbog",
      auth => array(
        "Peter Toft" => "pto@sslug.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "applikationer" => array(
      title => "Linux - Friheden til at v�lge programmer",
      comment => "V�lg programmer til Linux",
      auth => array(
        "Peter Toft" => "pto@sslug.dk",
        "Jacob Sparre Andersen" => "sparre@sslug.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "kontorbruger" => array(
      title => "Linux - Friheden til at v�lge kontorprogrammer",
      comment => "Basal anvendelse af Linux-programmer",
      auth => array(
        "Peter Toft" => "pto@sslug.dk"
      )
    ),
    "itplatform" => array(
      title => "Linux - Friheden til at v�lge IT-l�sning",
      comment => "Valg af Linux-baseret IT-l�sning",
      auth => array(
        "Michael Rasmussen" => "mir@miras.org"
      )
    ),
    "admin" => array(
      title => "Linux - Friheden til systemadministration",
      comment => "Administrer din egen Linux-server",
      auth => array(
        "Peter Toft" => "pto@sslug.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "program" => array(
      title => "Linux - Friheden til at programmere",
      comment => "Programmering p� Linux",
      auth => array(
        "Peter Toft" => "pto@sslug.dk",
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
    "sikkerhed" => array(
      title => "Linux - Friheden til sikkerhed p� internettet",
      comment => "Sikkerhed omkring din Linux-boks",
      auth => array(
        "Peter Toft" => "pto@sslug.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "web" => array(
      title => "Linux - Friheden til egen webserver",
      comment => "Web og databaser",
      auth => array(
        "Peter Toft" => "pto@sslug.dk",
        "Hans Schou" => "chlor@sslug.dk"
      )
    ),
    "docbook" => array(
      title => "Linux - Friheden til at skrive i SGML/DocBook",
      comment => "Skrive b�ger i DocBook",
      auth => array(
        "Peter Toft" => "pto@sslug.dk"
      )
    ),
    "kontorbruger" => array(
      title => "Linux - Friheden til at v�lge kontorprogrammer",
      comment => "Basal anvendelse af Linux-programmer",
      auth => array(
        "Peter Toft" => "pto@sslug.dk"
      )
    ),
    "kontorplatform" => array(
      title => "Linux - Friheden til at v�lge IT-l�sning",
      comment => "Valg af Linux-baseret IT-l�sning",
      auth => array(
        "Michael Rasmussen" => "mir@miras.org"
      )
    ),
   "alle" => array(
     title => "Linux - Friheden til at skrive b�ger",
     comment => "Samling af alle b�gerne",
     auth => array(
       "Peter Toft" => "pto@sslug.dk",
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
  reset($books);
  while (list($bookname) = each($books)) {
    reset($ext_files);
    while (list($ftype,$fname) = each($ext_files)) {
      if (file_exists($bookname.$fname)) {
        $num = file($bookname.$fname);
        $books[$bookname][$ftype] = trim($num[0]);
      }
    }
  }

  // Bogpakker pakket p� forskellige m�der
  // <first><$books><last>
  $packs = array(
    // Eks: frihed/bog/index.html
    "Online" => array(
      first => "bog",
      last => "index.html",
      online => 1  // Hvis bognavn kun skal med een gange
    ),
    // Eks: frihed/bog/index.html
    "�ndringer" => array(
      first => "bog",
      last => "apprevhist.html",
      online => 2  // "�ndringer" har bognavn med to gange
    ),
    // Eks: frihed/linux-frihed-4.0.tgz
    "HTML" => array(
      first => "linux-",
      last => ".html.tar.gz"
    ),
    "HTML zip" => array(
      first => "linux-",
      last => "_html.zip"
    ),
    "PNG billeder" => array(
      first => "linux-",
      last => ".png.tar.gz"
    ),
    "HTML u/billeder" => array(
      first => "linux-",
      last => ".html-ub.tar.gz"
    ),
    "PostScript" => array(
      first => "linux-",
      last => ".ps.gz"
    ),
    // Eks: linux-frihed-pdf.zip
    "PDF" => array(
      first => "linux-",
      last => "_pdf.zip"
    ),
    "PalmPilot" => array(
      first => "linux-",
      last => ".palm.zip"
    ),
    "eksempler" => array(
      first => "linux-",
      last => "_eksempler.tar.gz"
    ),
    "SGML" => array(
      first => "linux-",
      last => ".sgml.tar.gz"
    ),
    "SGMLzip" => array(
      first => "linux-",
      last => ".sgml.zip"
    )
  );

  $bgcolor = array("#FFFFFF","#E8E8E8");

  // Vis en bog med alle dens typer
  if (is_array($books[$b])) {
    visbog($b);
    echo "<p>\n";
  }

  // Vis en type med alle dens b�ger
  if (is_array($packs[$t])) {
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
  
  $c = 0;
  reset($books);
  while (list($short,$desc) = each($books)) {
    echo "<tr bgcolor=\"".$bgcolor[++$c & 1]."\">\n <td valign=\"top\">";
    echo "<b>$desc[title]</b><font size=\"-1\"><br>$desc[comment]</font></td>\n";
    reset($packs);
    while (list($type,$attr) = each($packs)) {
      echo " <td valign=\"top\"><font size=\"-1\">";
      $filename = form_filename( $short, $attr );
      $filetext = "$short $type";
      if (!file_exists($filename)) {
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
<?php } ?>

<font size="-1">
[<a href="?matrix=1">Samlet bogoversigt</a>]
[<a href="?all=b">Alle b�ger</a>]
[<a href="?all=t">Alle filtyper</a>]
[<a href="search">S�g i sgml-filer</a>]
[<a href="">S�g i sgml-filer</a>]
[<a href="hjaelpe.html">Om at hj�lpe</a>]
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
    echo "</li>\n";
  }
?>
</ul>

<hr>
<?php
  include("sidsteudgave.incl");
?>

<hr>
<p>
Benyt vores "Et-klik-service" for at downloade og l�se b�gerne,
eller k�b en indbundet udgave hos f�lgende forretninger:
<ul>
<li><a href="http://www.printxpress.dk/linuxbog/">PrintXpress</a></li>
<li><a href="http://www.pn-tryk.dk/html/linux.html">PN-tryk</a></li>
<li><a href="http://www.sc-birkeroed.dk/htm-sider/Linux.htm">SC-Birker�d</a></li>
</ul>
B�gerne er udgivet under <a href="opl.shtml">OpenContent License</A>
hvilket g�r at SSLUG ikke er involveret i de trykte udgaver.
</p>

<p>
V�rkt�j til konvertering af b�gerne til Word 95 format kan findes her.
<a href="http://www.klid.dk/~cs/proj/">www.klid.dk/~csi/proj/</a>.
</p>

<p>
�ndringerne der er sket med b�gerne kan ses p�
<a href="http://cvs.sslug.dk/linuxbog/cvs2html/">http://cvs.sslug.dk/linuxbog/cvs2html/</a>.
</p>

<p>
<FORM METHOD="GET" ACTION="http://www.netmind.com/cgi-bin/uncgi/url-mind">
Vil du modtage E-post om nye versioner af bogen?
<BR>Skriv din E-post adresse her
<BR><INPUT TYPE=TEXT SIZE=40 NAME="required-email">
<BR><INPUT TYPE=HIDDEN VALUE="http://www.sslug.dk/linuxbog" NAME="url">
<INPUT TYPE=SUBMIT VALUE="Indsend form"></FORM>
</p>

<p>
B�gerne redigeres af Peter Toft, Jacob Sparre Andersen og Hans Schou
&lt;<a href="mailto:linuxbog@sslug.dk">linuxbog@sslug.dk</a>&gt;.
<br>
Indholdet af b�gerne diskuteres p�
&lt;<a href="mailto:sslug-bog@sslug.dk">sslug-bog@sslug.dk</a>&gt;.
</p>

<p>
Der er altid ting vi gerne vil have skrevet om, eller skrevet mere p�.<br>
Du er meget velkommen til at komme med forslag. 
<a href="todo.html">Vi efterlyser bla. disse ting</a>.
</p>

<p>
Vil du f�lge med i hvad der sker med vores kilde-kode (SGML-filerne), 
<a href="http://cvs.sslug.dk/linuxbog/">s� se her</a>.
</p>

<p>
Hvis du har noget du s�ger efter, s� skal du nok <a
href="alle/bog/stikord.html">starte i vores samlede
indeks-register.</a>
</p>

<p>
Har du fundet fejl i en bog, s� send venligst dette til 
&lt;<a href="mailto:sslug-bog@sslug.dk">sslug-bog@sslug.dk</a>&gt;.<br>
Er du ikke medlem af SSLUG, s� skriv til
&lt;<a href="mailto:linuxbog@sslug.dk">linuxbog@sslug.dk</a>&gt;.<br>
Vi foretr�kker direkte rettelser foretaget i SGML-kildeteksten, men
kan ogs� rette ud fra pr�cise angivelser for hvor rettelserne skal
foretages.

<p> 
Bogen udvikles l�bende med opdateringer et par gange om ugen.
Vores arbejdskopi kan findes p� <a
href="http://cvs.sslug.dk/linuxbog/">http://cvs.sslug.dk/linuxbog/</a>,
men indtil endelig release, kan der v�re graverende fejl i den.  
</p>

<p>
<!-- Text slut -->
<!-- Husk din email-adresse: -->
<?php
  @include($DOCUMENT_ROOT."/includes/bottom.phtml");
?>

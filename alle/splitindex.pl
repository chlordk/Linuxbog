#!/usr/bin/perl
# $Id$
# Script der splitter stiksordsregister op i en fil for hvert
# bog, s� stikord.html er ikke er s� stor.
# Link til prim�rfilen: idx.html

my $state = 0;
my $headerX = "";
my $file = "";
my $filetitle = "";

my $header1 = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<HTML>
  <HEAD>
    <TITLE>";
my $header2 = "</TITLE>
    <META name=\"GENERATOR\" content=\"splitindex.pl\">
    <LINK rel=\"STYLESHEET\" type=\"text/css\" href=\"linuxbog.css\">
    <META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">
  </HEAD>

  <BODY class=\"SETINDEX\">
  <H1>Stikordsregister - Friheden Til At V�lge - Alle b�ger</H1>
  <p>[<a href=\"idx.html\">Bogstaver</a>] [<a href=\"stikord.html\">Samlet liste af stikord</a>] [<a href=\"index.html\">Indhold</a>]</p>
";

open(FILE,">/dev/null");
open(IDX,">idx.html") || die "Can not write to idx.html";
# Det her t�lle virker ikke..
$totalcountentry = 0;
$lastcountentry = 0;
$lastfile = "";
$lastfiletitle = "";
while (<>) {
    # K�mpestart
    if ($state == 0) {
	if (m/indexdiv/) {
#	    print STDERR "1: Match p� : $_";
	    $state = 1;
	    print IDX $header1."Oversigt".$header2."\n<p>\n";
	} else {
#	    print STDERR "1: Ej Match p� : $_";
	    $headerX .= $_;
	}
    }
    # �bning af fil?
    if ($state == 1) {
	# Matcher nyt "bogstav".
	if (m/<H2 CLASS="indexdiv"><A NAME="[a-zA-Z0-9��]+"><\/A>([a-zA-Z]+)<\/H2>/i) {
#	    print STDERR "2: Match p� : $_";

	    # Regn ud hvad vi nu besk�ftiger os med.
	    $filetitle = $1;
	    $file = "idx-".lc $filetitle.".html";

	    # Fiks idx.html filen.
	    if ("" ne $lastfile) {
		print_a_letter($lastfile,$lastfiletitle,$lastcountentry);
	    }
	    $lastfile = $file;
	    $lastfiletitle = $filetitle;
            $totalcountentry += $lastcountentry;
	    $lastcountentry = 0;
	    
	    # Det her virker tilsyneladende, selvom FILE ikke er sat.
	    print FILE "</body></html>";
	    close(FILE);

	    # �ben den n�ste.
	    open(FILE,">$file") || die "Can not write to $file";
	    print FILE $header1.$filetitle.$header2;
	    print FILE "<h1>$filetitle</h1>\n";
	} else {
	    if (m/\<DT\>/) { $lastcountentry++; }
#	    print STDERR "2: Ej Match p� : $_";
	    print FILE $_;
	}

    }
}


if ("" ne $lastfile) {
    print_a_letter($lastfile,$lastfiletitle,$lastcountentry);
}
print IDX "</p>\n";
print IDX "<p>Klik p� et bogstav for at se listen med ord der begynder med dette.</p>\n";
print IDX "<p>Samlet er der $totalcountentry stikord.</p>\n";
print IDX "</body></html>";
close(IDX);

print FILE "</body></html>";
close(FILE);

sub print_a_letter {
	my $lastfile = shift;
	my $lastfiletitle = shift;
	my $lastcountentry = shift; # ...men antal bruger vi ikke
	print IDX "[<a href=\"$lastfile\">$lastfiletitle</a><!-- $lastcountentry -->]\n";
}


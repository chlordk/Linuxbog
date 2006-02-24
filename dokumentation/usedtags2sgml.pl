#! /usr/bin/perl
# af Hans Schou
# $Id$
#
# Anvendes sammen med usedtags.pl til at lave en tabel
# hvor der ogs� er links til docbook.org og oreilly.com .

print "<table id=\"anvendte-elementer-tabel\">
<title>Anvendte elementer i denne bog</title>
<tgroup cols=\"4\" align=\"char\">
<thead><row>
<entry>Antal</entry>
<entry>Navn</entry>
<entry>DocBook.org</entry>
<entry>O'reilly</entry>
</row></thead><tbody>\n";

while (<>) {
	chomp;
	if (/^([0-9]+)\s([a-z0-9]+)$/) {
		$count = $1;
		$name = $2;
		print "<row>";
		printf("<entry>%4d</entry>", $count);
		$found = `egrep -i 'sect[1-3] *id=\"docbook-$name\"' docbook.sgml`;
		if ((length $found) eq 0) {
			print "<entry>$name</entry>";
		} else {
			print "\n<!-- '$found' -->\n";
			print "<entry><link linkend=\"docbook-$name\">$name</link></entry>";
		}
		print "<entry><ulink url=\"http://www.docbook.org/tdg/en/html/$name.html\">docbook.org $name</ulink></entry>";
		print "<entry><ulink url=\"http://www.oreilly.com/catalog/docbook/chapter/book/$name.html\">O'reilly $name</ulink></entry> ";
		print "</row>\n";
	}
}

print "</tbody></tgroup></table>\n";


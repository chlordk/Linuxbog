#!/usr/bin/perl -w -i -p
 
# Script af Hans Schou (og Ole Tange har ogs� hjulpet lidt med et par "sm�ting").
# $Id$

# Afvikling:
#  ./httpsplit *.sgml

# Hyperlinks i PostScript og PDF giver s� lange tekster at
# links g�r ud over kanten af papiret, og kan s� ikke l�ses.
# Scriptet her fjerner <ULINK> og s�tter spacer f�r '/' i URLer.

# Filerne bliver �ndret, s� dette script m� kun k�res p� en kopi.

undef ($/);

 # Lav mailto om
 s!<ulink\s+url="mailto:(.*?)">.*?</ulink>!<ComMand>&lt;$1&gt;</ComMand>!gsi;
 
 # Lav http og ftp URLer om
 s!<ulink\s+url="     # <ulink url="
    (.tt?p://|file:)  # http://, ftp:// eller file:
    (.*?)">           # www.sslug.dk/linuxbog">
    .*?</ulink>       # www.sslug.dk/linuxbog</ulink>
  !
    $http=$1;$path=$2;$path =~ s@/@/@g;
    "$http$path"
  !gsixe;             # Global SingleString IgnoreCase eXtentedComments Expression


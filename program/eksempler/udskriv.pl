#!/usr/bin/perl

# udskriv.pl
# af Ole Tange <ole@tange.dk>
# $Id$

# Afvikling:
#  1. chmod +x udskriv.pl
#  2. ./udskriv.pl filnavn

# -e = eksisterer?
# $ARGV[0] = filen angivet p� kommandolinje
# ellers
# stop med en fejl
-e $ARGV[0] or die("$ARGV[0] eksisterer ikke");

# for hver linje
while(<>) {
    # udskriv linienr og selve linjen
    print $.," ",$_;
}

# l�s fra standard input
while(<STDIN>) {
    # Hvis input er Q, s� stop
    $_ eq "Q\n" and exit;
}

#! /usr/bin/perl -w

# Script der laver en kmd-ca certifikat foresp�rgsel.

# F�lgende oplysninger kr�ves fra brugeren:
# � � � eller ae oe aa i certifikatet?
# givenName
# surname
# email
# keyusage
# om den private n�gle skal enkrypteres
# eventuelt password for denne n�gle

# NB: Dette script er ikke t�nkt som et "rigtigt" slutbrugerprogram, men som en
# illustration af hvordan en foresp�rgsel kan opbygges og laves. S� vidt vides
# indeholder programmet ingen fejl og har den fulde funktionalitet til at lave
# en certifikat foresp�rgesel til brug for KMD-CA.

# Dette script frigives valgfrit under "�ben dokumentlicens" (�DL) eller 
# Gnu Public Licensen (GPL )v. 2.

# Oprindeligt lavet af Mads Bondo Dydensborg (madsdyd@challenge.dk)
# Tilf�jelser af Klavs Klavsen og Christian Boesgaard
# Copyright 2002 Mads Bondo Dydensborg.

################################################################################
# Check at brugeren ikke gav nogen argumenter
$arg = shift(@ARGV);

if (defined $arg && $arg ne "") {
    print STDERR "Brug: $0\n\tInteraktivt certifikat foresp�rgelses skaber program for KMD-CA\n";
    exit 1;
}

################################################################################
# Check at man kan k�re openssl

$openssl = `which openssl`;
if ("" eq $openssl) {
    print STDERR "Fejl: Du skal have installeret openssl programmet\n";
    print STDERR "og det skal v�re tilg�ngeligt i din PATH (udf�rbart)\n";
    print STDERR "Dette program kan f�s fra http://www.openssl.org/\n";
    print STDERR "De fleste distributioner inkluderer dog dette program\n";
    exit 1;
}

################################################################################
# Vi bruger ReadLine til at l�se ting ind med - for at give en rimelig
# indl�sningsgr�nseflade

use Term::ReadLine;
$term = new Term::ReadLine 'Certifikat';
$prompt = "> ";

################################################################################
# Lidt info til brugeren

print "Dettte program vil lave en certifikat foresp�rgsel til KMD-CA for dig.\n\n";
print "Du skal indtaste en r�kke oplysninger\n";
print "Efter indtastning af hver oplysning skal du trykke p� enter/return\n";
print "For oplysninger hvor der gives flere valg, kan du som regel trykke enter for den\n";
print "mest almindelige v�rdi. Denne vil v�re illusteret med et stort bogstav.\n\n";

################################################################################
# L�s de n�dvendige v�rdier
do {
    
    $prompt = "Indtast dit fornavn(e) : ";
    $givenName= $term->readline($prompt);

    $prompt = "Indtast dit efternavn : ";
    $surname = $term->readline($prompt);

    $prompt = "Indtast din emailadresse : ";
    $emailAddress = $term->readline($prompt);

    print "\nCertifikatet kan bruges til enkryptering, signering eller begge dele\n";
    print "Hvad skal det bruges til? [E]nkryptering, [S]ignering eller [B]egge?\n";
    $prompt = "Angiv hvad certifikatet skal bruges til [e/s/B] : ";
    $keyUsage = $term->readline($prompt);
    
    if ("e" eq $keyUsage || "E" eq keyUsage) {
	$keyUsage = "Data Enchiperment";
    } else { 
	if ("s" eq $keyUsage || "S" eq $keyUsage) {
	    $keyUsage = "Digital Signature";
	} else {
	    $keyUsage = "Digital Signature, Data Enchiperment";
	}
    }

    print "\nNogle Certifikat udstedere underst�tter ikke ��� (s�som KMD) og skal\n";
    print "du bruge certifikatet med en s�dan udsteder skal du svare J her, s�\n";
    print "konverteres �, �, �, til henholdsvis Ae, Oe, Aa.\n";
    $prompt = "�nsker du at f� konverteret ���? [J/n] : ";
    $convert = $term->readline($prompt);

    #Konverter ��� og ��� hvis der blev valgt J ovenfor.
    if ($convert eq "J" || "" eq $convert)  # do ��� conversions
    {
       foreach ($givenName,$surname,$emailAddress)
       {
	  #printf "Konverterer ���...\n";
          $_ =~ s/�/Ae/g;  # 
          $_ =~ s/�/Oe/g;  # 
          $_ =~ s/�/Aa/g;  # 
          $_ =~ s/�/ae/g;  # 
          $_ =~ s/�/oe/g;  # 
          $_ =~ s/�/aa/g;  # 
       }
    }

    printf "\nDet anbefales at man beskytter sin private n�gle med en adgangskode\n";
    printf "Indtast en s�dan - bem�rk at den vil blive skrevet til sk�rmen!\n";
    $prompt = "Angiv adgangskode : ";
    $output_password = $term->readline($prompt);
    
################################################################################
# Bekr�ft 
    
    print "\nHer er de informationer du indtastede.\n\n";
    print "NB. Bem�rk at hvis du svarede J til Konverter ���, vil alle ��� og\n";
    print "    ��� v�re repr�senteret som Ae,Oe,Aa og ae, oe, aa nedenfor.\n\n";
    
    print "Fornavn       : $givenName\n";
    print "Efternavn     : $surname\n";
    print "Email         : $emailAddress\n";
    print "N�glebrug     : $keyUsage\n";
    print "Konverter ��� : $convert\n";
    print "Adgangskode   : $output_password\n\n";
    
    $prompt = "Er informationerne korrekte? [J/n] : ";
    $OK = "J";
    $OK = $term->readline($prompt);
    
    if ("j" eq $OK || "" eq $OK) {
	$OK = "J";
    }
} while ($OK ne "J");

################################################################################
# Informationerne er OK - lav foresp�rgselsfilen

# Vi skal bruge en midlertidig fil, som skal v�re l�sebeskyttet for andre
# Jeg ved ikke rigtigt hvordan man laver det smart i Perl.
$filename = "request.config.$$";
open (CONFIG, ">$filename") ||
    die "Kunne ikke �bne filen $filename - kr�vet for at fors�tte\n";
chmod 0600, $filename ||
    die "Kunne ikke s�tte rettigheder p� $filename - kr�vet for at fors�tte\n";

# Opbyg et par oplysninger
$fileprefix = $givenName."_".$surname;
$fileprefix =~ s/\s/\_/g;
$keyfile = $fileprefix."_N�gle.pem";
$reqfile = $fileprefix."_Anmod.req";

$CN = "$givenName $surname // PID:xxxxxxxxx";


# Skriv oplysninger til fil 
print CONFIG 
"[ req ]
prompt                 = no
default_bits           = 1024

default_keyfile        = $keyfile
output_password        = $output_password

distinguished_name     = req_distinguished_name

[ req_distinguished_name ]
C                      = DK
O                      = Ingen organisatorisk tilknytning
CN                     = $CN
emailAddress           = $emailAddress
givenName              = $givenName
surname                = $surname
keyUsage               = $keyUsage
serialNumber           = 9208-2001-1-xxxxxxxxx
";
close (CONFIG) || 
    die "Kunne ikke lukke $filename - kr�vet for at fors�tte\n";

################################################################################
# OK, nu er vi klar til at k�re openssl. 
print "Information: Starter openssl (god ting)\n";
$status = 
system("openssl req -newkey rsa:1024 -config $filename -out $reqfile -outform DER");

# Slet config filen
$del = unlink ($filename);
if (1 != $del) {
    print STDERR "Advarsel: Det var ikke muligt at slette $filename\n";
} else {
    print "Information: Den midlertidige fil er blevet slettet (god ting)\n";
}

if (0 != $status) {
    print STDERR "Fejl: Der var en fejl under udf�relsen af openssl kommandoen\n";
    print STDERR "En anmodningsfil er _ikke_ blevet lavet\n";
    exit 1;
}

print "\nSucces!\n";
print "\nTo filer er blevet lavet:\n\n";

print "\"$reqfile\" er den anmodningsfil KMD-CA skal have\n";
print "\"$keyfile\" er din private n�gle\n";
print "\nNB: Du m� ikke lade andre f� adgang til din private n�gle!\n";

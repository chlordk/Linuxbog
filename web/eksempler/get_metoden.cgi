#!/usr/bin/perl

#Filnavn: get_metoden.cgi

sub URLdekrypt
#Udf�rer url dekryption p� en streng.
{
    my ($input)=(@_);

    my ($variabel,$indtastning);
    my %data;

#Klip input strengen ved all '&' karaktere

    my @query=split /&/, $input;

#loop gennem tabellen af "variabel=indtastning" strenge

    foreach (@query)
      {

#Klip ved '='

        ($variabel,$indtastning)=split /=/, $_;

#fix '+' f�r %xy!

        $variabel    =~ tr/+/ /;
        $indtastning =~ tr/+/ /;

#null karaktere u�nskede!
        $variabel    =~ s/%00//g;
        $indtastning =~ s/%00//g;

#substituter %xy med den tilsvarende karakter..

        $variabel    =~ s/%([0-9A-Fa-f]{2})/pack("c",hex($1))/ge;
        $indtastning =~ s/%([0-9A-Fa-f]{2})/pack("c",hex($1))/ge;

#Hvis flere felter har samme navn s� konkateneres deres indhold
#separeret af en '|' karakter.

        if ($data{$variabel})
            { $data{$variabel}=$data{$variabel}."|".$indtastning; }
          else
            { $data{$variabel}=$indtastning; } 
      }

   return %data;
}

my %data=URLdekrypt($ENV{'QUERY_STRING'});

#udskriv data
print "Content-Type: text/plain\r\n\r\n";
print "Jeg modtog f�lgende data:\n";

foreach (sort keys %data)
    {
       print "   $_ = \"$data{$_}\"\n";
    }

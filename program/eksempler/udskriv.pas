{
  udskriv.pas
  af Michael Rasmussen <mir@miras.org>
  $Id$

  Overs�t med: gpc udskriv.pas -o udskriv

  Afvikling: ./udskriv
}

program udskriv;

var
 fil, {variabel til at lagre filens navn}
 txt : string[255]; {variabel til at lagre en tekstlinie}

 fptr : text; {variabel til at skabe adgang til en fil p� disken}
 i : integer; {variabel som t�lles op med 1 for hver ny linie}

begin
   {f�rste linie}
   i := 1;
   {hent filnavnet fra kommandolinien: 0=programmet, 1=f�rste
    parameter efter programmets navn}
   fil := paramstr(1);
   {skab forbindelse til filen p� disken med det p�g�ldende navn}
   assign (fptr,fil);
   {$i-}
   {sl� automatisk fejlbehandling fra: skriv din egen rutine;
    fejlmeddelelser fra sidste I/O rutine kan l�ses i variablen IOResult}
   reset (fptr); {�ben filen for l�sning fra f�rste linie}
   {$i+} {automatisk fejlbehandling sl�s til}
   {hvis resultatet af fors�get p� at �bne filen resulterede i en
    fejl, vil variablen IOResult indeholde en v�rdi <> 0.}
   if IOResult <> 0 then
   begin
     {var der en fejl?}
     writeln('Filen findes ikke, programmet afsluttes.');
     exit; {stop programmet}
   end;
   writeln;
   writeln ('Indhold af filen: ',fil,'.');
   writeln;
   writeln ('linie tekst');
   writeln ('----- -----');
   {s� l�nge der er linier i filen, fors�tter vi med at l�se}
   while not eof(fptr) do
   begin
      {l�s en linie fra filen som fptr peger p�,
       og l�g resultat i variablen txt. Skift til n�ste linie}
      readln(fptr,txt);
      {skriv linienummeret - start i femte kolonne - og
       indholdet af variablen txt p� sk�rmen}
      writeln(i:5,' ',txt);
      {t�l variablen i op med 1}
      inc(i);
   end;
end.

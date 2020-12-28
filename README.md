Dette er README filen til bøgerne "Linux -- Friheden til at vælge".

Indholdet er flyttet over i:
http://www.linuxbog.dk/dokumentation/bog/ftav.html
(dokumentation/ftav.sgml)

















Herunder slettes snart (chlor):
-------------------------------------------------------------------------------
* Indhold

1) Hvad er "Linux -- Friheden til at vælge" (FTAV)
2) Bygning af bøgerne fra kildekode
3) Adgang til kildekoden fra CVS
4) Mere om bygning af bøgerne fra CVS

-------------------------------------------------------------------------------
1) Hvad er Linux -- Friheden til at vælge (FTAV)

"Linux -- Friheden til at vælge" er en række bøger om Linux, Unix og
andre relaterede emner, som er skrevet af en gruppe frivillige,
fortrinsvis medlemmer af Linux brugergruppen "Skåne-Sjælland Linux
User Group" (SSLUG; http://www.sslug.dk/).

FTAV udgives med jævne mellemrum på http://www.linuxbog.dk/ . Her kan
du downloade færdige udgaver af bøgerne, i flere forskellige
formater. Nogen formater er velegnet til online brug, f.eks. HTML,
andre egner sig bedre til tryk, f.eks. PDF.
Endeligt er kildeteksten til bøgerne, svarende til
de færdige udgaver også tilgængeligt her.

Udviklingen af bøgerne -- rettelser, opdateringer, mv -- foregår på
http://cvs.linuxbog.dk/ . Her finder du i princippet det samme som på
http://www.linuxbog.dk/, men bøgerne indeholder de sidste rettelser
(og fejl, unøjagtigheder, mv) som forfatterne har tilføjet. Her findes
også information om hvordan du kan komme i kontakt med
forfatterne/udviklerne på bøgerne.


-------------------------------------------------------------------------------
2) Bygning af bøgerne fra kildekode

Der er relativt få grunde til at bygge bøgerne selv fra kildekode. Her
opsummerer vi et par af de årsager der kan være.

- Du ønsker at skrive et afsnit til bøgerne, eller at rette noget der
  allerede er skrevet og vil derfor gerne kunne bygge dem selv.

- Du ønsker at bygge bøgerne på en anden måde (f.eks. anden sidebredde
  til fast tryk) end de bliver bygget på linuxbog.dk.

Hvis ikke du har et af ovennævnte behov, burde du kunne "nøjes" med de
færdigbyggede bøger på cvs.linuxbog.dk, eller www.linuxbog.dk. Hvis du
vil bygge bøgerne selv, bliver det i det følgende beskrevet hvordan
dette gøres.

For at kunne bygge bøgerne, skal du have en del forskellige programmer
installeret. Det drejer sig om værktøjer der kan oversætte sgml koden
(som bøgerne er skrevet i) til de forskellige formater (html, pdf, ps,
palm pilot, mv) som bøgerne kan oversættes til.

Fremgangsmåden for at bygge bøgerne er lidt forskellig, afhængigt af
hvordan du har fået fat i kildeteksterne. Hvis du har fået CVS adgang
til kildekoden, skal du starte med at læse afsnittet nedenfor kaldet
"Adgang til kildekoden fra CVS". I det følgende beskrives hvordan en
enkelt bog, downloadet som en tar.gz fil, oversættes.

Først skal du udpakke bogen, f.eks. med kommandoen

$tar zxvf linuxbog-applikationer-dist-*.tar.gz

Derefter skal du skifte til kataloget er der blevet oprettet, og
konfigurere bogen:

$cd applikationer
$./configure --help

Ved at skrive --help vil du få en oversigt over de argumenter
configure kan tage. Som udgangspunkt burde det ikke være nødvendigt at
anvende nogen argumenter, men der kan være situationer hvor det er ønskværdigt.

Du kan nu konfigurere bygning af bogen, med f.eks.

$./configure --enable-softlink

Herefter vil configure undersøge dit system for at finde ud af om du
har de nødvendige værktøjer til at bygge bogen med. Hvis configure
finder ud af at du mangler centrale værktøjer, vil den afbryde med en
fejl. Det kan f.eks. være at du mangler programmet "jade" som kan læse
sgml filer, eller programmet "jw" som er en frontend til jade. Disse
programmer er altid krævet for overhovedet at kunne oversætte
bøgerne. For de forskellige formater bøgerne skal oversættes til,
kræves desuden nogle forskellige værktøjer. F.eks. kræves programmet
"db2html" for at kunne lave en udgave af bøgerne i html
format. Configure programmet vil ikke afbryde hvis disse værktøjer
mangler, men blot konfigurere oversættelsen således at disse ikke kan
bygges. Til slut vil configure udskrive en oversigt over hvilke
formater der kan oversættes til. Det kan f.eks. se sådan her ud:

configure: Oversigt over hvilke moduler der kan laves
configure:  Kan SGML bygges    : ja
configure:  Kan PALM bygges    : nej
configure:  Kan PDF bygges     : ja
configure:  Kan HTML bygges    : ja
configure:  Kan PAKHTML bygges : ja

Hvis det format du gerne vil bygge til, ikke understøttes, kan du
kigge i det configure har skrevet for at finde årsagen.

Hvis du mangler nogen værktøjer må du installere dem, og køre
configure igen. Hvis du har vanskeligt ved at finde ud af hvorfor du
ikke kan bygge et bestemt format, kan du kigge i filen "config.log"
hvor configure skriver detaljeret information om hvad det foretager
sig.

Når du har fået support for de formater du gerne vil have, kan du
skrive f.eks.

$make html

for at lave html udgaven. Eller, make pdf for at lave pdf udgaven,
osv.


-------------------------------------------------------------------------------
3) Adgang til kildekoden fra CVS

For at få adgang til bøgerne via CVS, skal du vise at du vil være
aktiv og kan arbejde ansvarligt med bøgerne :) Få login på
tyge.sslug.dk ved at skrive til Peter Toft <pto@linuxbog.dk> og gør
dernæst følgende.

Installer OpenSSH på din maskine og følg 
vejledningen på 
http://cvs.linuxbog.dk/sikkerhed/bog/sikker-net-trafik.html
http://cvs.linuxbog.dk/sikkerhed/bog/opsaetning-af-openssh.html

Gør følgende (Linier med * udføres kun første gang)

  ssh DITLOGIN@tyge.sslug.dk
* mkdir public_html   
* chmod a+rx ~ ~/public_html
  cd public_html
* cvs -d /usr/local/CVSROOT checkout linuxbog
  cd linuxbog
  ./configure               <<--- NB, se afsnit længere ned om byggesystemet.
  cd BOGNAVN
  make html

Så kan du køre
Netscape http://cvs.sslug.dk/~DITLOGIN/linuxbog/java/bog

Og teksten kommer på web hver morgen
http://cvs.linuxbog.dk

configure --help fortæller om nogle options, blandt andet
--med-alle, som får make til at generere et arkiv og en
samlet html-version af alle bøgerne.

Du kan også køre ./configure i de enkelt bog-directories.
cd BOGNAVN &&  ./configure -- men det er kun nødvendigt,
hvis du selv har ændret i bootstrap.subdir.

Hvis du gerne vil have filerne hjem, så kan du sætte
følgende miljø-variabel (environment-variable)

export CVS_RSH=ssh

og skrive 
cvs -d DITLOGIN@tyge.sslug.dk:/usr/local/CVSROOT checkout linuxbog

hvorefter du bør få bogen checket ud.

Hvis du kun vil have en enkelt bog checket ud, bruger du top-navnet:
cvs -d DITLOGIN@tyge.sslug.dk:/usr/local/CVSROOT checkout linuxbog/friheden

Den samlede størrelse af CVS-checkout af alle bøger er ca. 30MB

De enkelte bøger varierer fra < 1.0 MB (dokumentation) til 
                                8.8 MB (applikationer)

Læs http://cvs.linuxbog.dk/program/bog/vaerktoej.html#VAERKTOEJ-CVS

-----------------------------------------

Hvis nogen af jer har lyst til at få email hver gang 
der ryger en fil ind i CVS-arkivet (cvs commit), så kan 
I tilmelde jer <linuxbog-commit@tyge.sslug.dk> ved 
at skrive til <linuxbog-commit-subscribe@tyge.sslug.dk>

I som har konto på tyge kan bruge 

 $ cd katalog
 $ cvs watch add 

til at følge de filer I er interesserede i.

Hvordan ser de mails så ud man kan modtage på den nye 
liste? De vil indeholde info om hvem som lavede 
ændringen (pto), hvilket projekt der er 
ændret (linuxbog) og hvilke filer som er ændret (her 
kun hjaelpe.html) og man få den log-besked vi skriver 
hver gang (kan ses i 
http://cvs.linuxbog.dk/cvs2html/ ->
http://cvs.linuxbog.dk/cvs2html/cvs_crono.html)
Man får således ikke selve ændringen at se kun 
log-beskeden og hvad der er ændret - og af hvem.

--------------------------------------------

Vejledning i at skrive

Læs http://cvs.linuxbog.dk/dokumentation/bog/docbook.html

Hver bog er organiseret gennem bog.sgml og indhold.sgml
pga. integration med "alle-upgaven" (alle bøgerne 
samlet) så er det smart at jeg i bog.sgml anfører 
filernes kobling til et SGML-tag - eksempler

 <!entity java-indledning SYSTEM "indledning.sgml">
 <!entity java-indhold SYSTEM "indhold.sgml">
 <!entity java-appendixRevHist SYSTEM "apprevhist.sgml">
 <!entity java-forord SYSTEM "forord.sgml">

og at jeg sidst i filen bog.sgml siger at NU kommer 
java-indhold - i praksis skriver du &java-indhold;
og dermed kopieres filen indhold.sgml ind (jfr. 
ovenstående).

Hvis du så kigger i indhold.sgml så vil jeg i bunden 
bruge din indhold.sgml ved at skrive

 &java-forord;      <---- defineret i bog.sgml
 &java-indledning;  <---- do.
 &java-appendixRevHist; <---- do. versionshistorien.
 
Dermed kommer de tre filer ind i følgende rækkefølge

 forord.sgml
 indledning.sgml
 apprevhist.sgml

Når I har indført en ny fil VM.sgml og den skal med i 
f.eks. java bogen, så skal I indføje en linie i bog.sgml

 <!entity java-VM SYSTEM "VM.sgml">

og i indhold.sgml skal I på passende sted referere 
 
 &java-VM;

Dernæst skal I tilføje en linie under den rette bog i 
linuxbog/alle/bog.sgml - samme som i bog.sgml for den 
enkelte bog - men med ../../../ sat foran stien. På den
måde vil alle-bogen også være opdateret.

 <!entity java-VM SYSTEM "../../../VM.sgml">

---------------------------------------
Tag id

XML versionen kræver, at alle tags skrives med små bogstaver og
alle tags har formen <tag [attributer]>tagged text</tag>

Afsnit tags, fx. <sect1 id="xx-beskrivende-navn"> bliver brugt
til at give html-filerne navne, så det beskrivende navn vælges
med omhu. Det er vistnok en god ide at bruge en bogstavkode for
selve bogens navn, altså: erstat xx med den identifikation, som
benyttes for den bog, du skriver på.

---------------------------------------
Notation for skrivning

For at bøgerne ligner hinanden mest muligt i de viste
eksempler, skal der anvendes samme brugernavn og
maskinnavn i alle bøger.

Den primære bruger er 'tyge' og hans fulde navn er
'Tyge Brahe'. Denne bruger symbolisere brugeren
selv. Skal der illustreres andre brugere der 
kommunikeres med, eller der skal oprettes på et
system, bruges:
 otto 'Otto Brahe'
 axel 'Axel Brahe'

Valget af 'tyge', 'otto' og 'axel' udemærker sig ved
at de alle har 4 tegn i navnet, og giver overskuelig
liste når de bliver brugt i eksempler.

Den primære maskine som brugeren fysisk sidder ved
hedder 'hven.sslug.dk', hvor normalt brug så blot er
'hven'. Skal der kommunikeres med andre maskiner,
hedder de:
 saltholm.sslug.dk
 peberholm.sslug.dk

E-mail eksempler:
 "Tyge Brahe" <tyge@sslug.dk>
 "Tyge Brahe" <tyge@hven.sslug.dk>
 "Otto Brahe" <otto@saltholm.sslug.dk>
 "Axel Brahe" <axel@peberholm.sslug.dk>

En normal-prompt (bash) i homedir bliver så:
 [tyge@hven ~]$

Det er ikke på forhånd givet at 'otto' og 'axel'
har login på 'hven.sslug.dk', det skal fremgå af
eksemplet.

Baggrund: Tyge Brahe (latinsk: Tycho) er en kendt
dansk astronom der blev født på Sjælland og voksede
op i Skåne. Sidst bosat på Hven. Otto er hans far
og Axel er hans bror.

Valget af Tyge Brahe som eksempel betyder ikke at
alle medlemmer af LinuxBog-redaktionen anerkender
det Tychoniske-verdensbillede (jorden i midten).
Jvf. afsnittet om tidszoner, tror heller ikke alle
at jorden er flad, det ville dog have gjort det
hele lidt nemmere.


-------------------------------------------------------------------------------
4) Mere om bygning af bøgerne fra CVS

Dette afsnit er til dig der har fået CVS adgang, eller dig der har
haft det igennem et stykke tid, men gerne vil vide hvordan
byggesystemet fungerer (eller forsøger at fungere).

Først beskrives hensigten med det nuværende system, derefter beskrives
hvordan det virker, og hvilke muligheder man har med det.

Når man checker bøgerne ud fra CVS, vil jeg referere til det katalog
der hedder "linuxbog" som "toplevel" og de enkelte bøgers kataloger
(f.eks. applikationer, sikkerhed, mv) som subdir.

* Hensigten med byggesystemet.

Hensigten med det byggesystem vi bruger i CVS og i de enkelte subdirs,
er at 

a) Det skal være nemt for folk at bidrage til de enkelte bøger, _uden_
   at have skriveadgang til CVS.

b) Alle bøger skal kunne bygges til html, mv, afhængigheder
   skal fungere korrekt

c) Alle bogen skal kunne bygges

d) Der skal være install, uninstall, mv, targets

e) På sigt skal man kunne bygge bøgerne under andre platforme end Linux
   (f.eks. FreeBSD)

Motivation og uddybning af disse punkter:

a) Tidligere har det været vanskeligt at bidrage til bøgerne af to
   hovedårsager: Den eneste måde at få adgang til _alle_ de nødvendige
   filer, var ved at få adgang til CVS udgaven af bøgerne & tests for
   de nødvendige værktøjer var uigennemskuelig og flettet ind i
   makefilerne. 
   At få adgang via CVS var problematisk af to årsager. For det første
   skulle der skriveadgang til, samt login på CVS serveren. For det
   andet er det ikke alle der er fortrolige med CVS.
   De tests der har været for de forskellige værktøjer var tidligere
   dels inkomplette, dels lagt ind i selve makefilerne. Det betød at
   det var vanskeligt for brugeren at finde ud af hvorfor
   oversættelsen gav problemer. I praksis var det en langvarig
   "trial-and-error" proces, indtil man opnåede det ønskede
   resultat.
   Med autotools udgaven af bøgerne (hvor autotools bruges til at lave
   de nødvendige makefiler, mv) forsøges disse problemer
   løst. Problemet med tests er løst ved at lade autotools (autoconf)
   håndtere nødvendige tests. Det har den fordel at brugeren kan køre
   ./configure og få en oversigt over hvilke værktøjer der
   mangler. Når disse er installeret burde brugeren have en høj grad
   af sikkerhed for at testene virke. Test gennemføres kun i subdirs,
   da det er disse der skal bygges (som de enkelte bøger). Problemet
   med distribution af kildekoden er klaret ved at lave et nyt
   toplevel target "dist", der laver distributioner af de enkelte
   bøger. De pakker man får ud af dette, er i princippet "stand-alone"
   og har ikke nogen afhængigheder til resten af kildekoden.

b) Ikke noget overraskende i dette. Imidlertid vil det nye system (når
   det er færdigt) forhåbentligt have mere orthogonale afhængigheder,
   og de problemer der pt. er med at f.eks. html og pdf rører ved
   "hinandens" filer, vil være fjernet. Der mangler en del her.

c) Det nye i forhold til det eksisterende system vil være at man kan
   lave en "personlig" alleudgave af bøgerne, der indeholder præcist
   de bøger man er interesseret i. Dette fungerer stort set - dog er
   der problemer med ps udgaven i øjeblikket.

d) På sigt skal der implementeres install og uninstall targets, så en
   systemadministrator f.eks. kan vælge at installere bøgerne for en
   større gruppe af brugere på en struktureret måde.

e) Med udgangspunkt i autotools, skal der findes alternativer til de
   platforme der ikke har f.eks. jw i en tilstrækkelig ny
   udgave. (pto?)

* Hvordan virker byggesystemet

Byggesystemet er (pt) en blanding af autotools til subdirs, og
håndskrevne scripts til toplevel. 

Den ultrakorte udgave af "hvordan man gør", efter udcheck fra CVS for
at bygge bogen "BOGNAVN" som html er som følger:

./configure <options>
cd BOGNAVN
make html

Men, det er langt fra hele historien.

Ideen med configure scriptet i toplevel er at det skal anvendes til at
opsætte hvilke (og eventuelt hvordan) bøger man ønsker bygget på sin
maskine. Dette bestemmes ved at give en række argumenter til
configure. Hvis du kører ./configure --help, vil du se følgende:

----------------------------------
`configure' opsætter hvilke af "Linux - friheden til at vælge bøgerne" der skal
laves på dit system.

Brug: ./configure [OPTIONS] [-- SUBDIROPTIONS]

Hvor OPTIONS kan være een eller flere af
  -h, --help             Viser denne hjælp og afslutter
  -m, --med "BØGER"      Bestemmer hvilke bøger der medtages, hvor BØGER
                         er en liste af bøger. Kombiner:
                         "itplatform friheden unix kontorbruger applikationer wm signatur admin sikkerhed program web c java dokumentation forsker"
  -a, --med-alle         Medtag alle (een stor bog) bogen
  -u, --bogurl URL       Hvilken url bøgerne skal bruge. [cvs.linuxbog.dk]

Hvor SUBDIROPTIONS kan være
      --enable-softlink  Bruger softlinks for HTML targets
Eksempel: ./configure --med "friheden applikationer" -- --enable-softlink
----------------------------------

I det følgende vil disse argumenter blive uddybet.

--med "BØGER" argumentet, bruges til at fortælle _hvilke_ bøger du
gerne vil have bygget på dit system. ./configure --help vil udskrive
en liste af dem der mulige (se ovenfor). Så, hvis du f.eks. gerne vil
have bygget de bøger der anbefales når du vil lære hvordan man bruger
en Linux maskine til at programmere med, kan du give configure
argumentet --med "friheden unix program c java". Så vil configure
sørge for at opsætte systemet, således at disse bøger vil blive
oversat. Hvis du ikke angiver noget --med argument, vil alle bøger
blive medtaget.

--med-alle argumentet bruges til at angive at man vil have bygget den
særlige "alle" bog. Det er en udgave hvor de forskellige bøger flettes
ind i hinanden. --med argumentet bruges af --med-alle; hvis du har
angivet et --med argument, bruges kun de bøger i dette argument til
--med-alle. Rækkefølgen er også som i --med, hvis det er angivet.

--bogurl bruges fortrinsvist når vi udgiver en bog på nettet - så
bygges bøgerne så interne (explicitte) referencer til &linuxbogurl;
refererer til www.linuxbog.dk - normalt er det cvs.linuxbog.dk.

SUBDIROPTIONS er argumenter der ikke bruges af configure programmet
selv, men gives videre til configure i de forskellige subdirs. Se
afsnittet om at bygge fra kildekode tidligere i denne fil.

Her er en liste af filer der anvendes direkte af byggeprocessen i toplevel.

configure        dette script kopierer en række filer til de forskellige
                 subdirs, og kører "bootstrap", samt "configure" i disse
                 subdirs.

Makefile.in      denne fil laves af configure om til Makefile i
                 toplevel.

faelles-filer/*  disse filer kopieres af configure til de forskellige
                 subdirs, som subdir/*

bootstrap.subdir denne fil kopieres til de forskellige subdirs, som 
                 subdir/bootstrap 

Makefile.subdir  denne fil kopieres af configure til de forskellige
                 subdirs som Makefile.am, som af subdir/bootstrap
                 laves om til Makefile

configure.ac.subdir kopieres af configure til de forskellige subdirs
                    som subdir/configure.ac

Endvidere kopieres scriptet "misc/insertimagesize" til subdir/misc/,
samt de forskellige palm relaterede filer fra misc til
subdir/palm-faelles/

Makefile.alle    Denne fil kopieres af configure til alle/Makefile.am,
                 hvis --med-alle argumentet blev angivet.

Når configure er færdig med at kopiere, kører configure
subdir/bootstrap og subdir/configure SUBDIROPTIONS for alle de bøger
der skal opsættes. Bootstrap kører de forskellige autotools værktøjer
der skal til for at opsætte de forskellige subdirs. Bemærk at det
således er nødvendigt at have autotools værktøjerne for at bygge
bøgerne fra CVS, men det er _ikke_ nødvendigt for at bygge
individuelle bøger hentet i *dist* pakker.

Den centrale del af det videre byggeforløb ligger i filerne
"Makefile.subdir" (der via toplevel/configure og subdir/configure
bliver til subdir/Makefile). Denne fil er den centrale at modificere
for at rette de tilbageværende problemer i byggeprocessen.

splitstikord.pl
---------------

Scriptet 'splitstikord.pl' der sørger for at blande alle
stikord.html filerne sammen fra alle bøgerne, og dernæst lave
en fil for hvert bogstav, kræver at perl-pakken HTML::TreeBuilder
er installeret. Det er ikke alle der har den default.

På tyge.sslug.dk der kører Red Hat 9.0 (for tiden), er pakken
http://dag.wieers.com/packages/perl-HTML-Tree/perl-HTML-Tree-3.17-0.dag.rh90.noarch.rpm
blevet installeret. Original-pakken findes på
http://search.cpan.org/dist/HTML-Tree/


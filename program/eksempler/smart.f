C     Program smart.f
C     af Peter Gylling <gylling@danbbs.dk>
C     $Id$
C
C     Har du nogensinde undret dig over, hvilken acceleration
C     de vitale motordele i din bil/motorcykel/knallert/etc 
C     uds�ttes for ved forskellige omdrejnings tal? 
C
C     En unders�gelse af plejlstangs mekanismen vil v�re en 
C     opgave, der tidligere typisk ville blive skrevet i 
C     fortran. Idag vil det blive gjort i det programmerings 
C     sprog, som passer den enkelte bedst.
C
C     Plejlstangs mekanismen oms�tter den roterende bev�gelse,
C     som krumtap akselen uds�ttes for til stemplets bev�gelse 
C     op/ned gennem cylinderen. Som f�lge af dette er stemplet
C     udsat for accelleration selvom motoren k�rer med konstant
C     omdrejningstal. Det vil f�re for vidt her, at gennemg� 
C     de teoretiske overvejelser, der er n�dvendige for en 
C     tilbunds g�ende unders�gelse.
C
C     Som input til programmet, s� skal brugeren indtaste v�rdien
C     af de vigtigste variable:
C
C     N --> Omdrejningstal (omdr/min)
C     R --> Krumtappens radius (m)
C     L --> L�ngde af plejlstangen (m)
C
C     Som output skrives en datafil med f�lgende v�rdier:
C
C     Kolonne 1 --> Vinkel reference (l�ber fra 0 til 359 grader)
C     Kolonne 2 --> Stemplets position (m)
C     Kolonne 3 --> Stemplets hastighed (m/s)
C     Kolonne 4 --> Stemplets accelleration (m/s�)
C
C     Data kan s� vises f.eks. ved brug af gnuplot eller octave
C
C     Skrevet af Peter Gylling
C     ---------------------------------------------------------------

      program acc
      implicit none

C     -------------------------------------------------------------
C     Det farlige ved fortran er, at der er en hel masse
C     forud definerede variable. Dette betyder, at programmet
C     fint kan kompilere selvom et par variable ikke er defineret.
C     Under brug kan det derfor v�re vanskeligt at finde ud af,
C     hvorfor der opst�r underlige resultater.
C
C     Derfor benyttes her 'implicit none', der har som resultat,
C     at ingen variable m� tages fra den forud definerede liste.
C     --------------------------------------------------------------


C     Definition af variable
C     ----------------------

      integer in_unit        ! Standard l�sning fra tastatur
      integer out_unit       ! Standard skrivning til sk�rm
      integer fil_out_unit   ! Standard skrivning til fil

      integer i              ! T�ller 
      integer n_rpm          ! Omdrejnings tal (rpm)
      
      real*4 pi              ! Pi er her sat til 3.1416
      real*4 alpha           ! Vinkel reference (radianer)
      real*4 r_krum          ! Radius af krumtappen (m)
      real*4 l_plejl         ! L�ngde af plejlstangen (m)
      real*4 w               ! Rotationshastighed (radianer/sekund)
      real*4 kvad            ! Kvadrarods udtryk 
      real*4 data(4,360)     ! 4 kollonner af l�ngde 360
      

C     Start p� program
C     ----------------

      in_unit = 5
      out_unit = 6
      fil_out_unit = 16
      pi = 3.1416

C     Indl�sning af data fra bruger
C     -----------------------------
      write(out_unit,100) '>> Indtast omdrejningstal (omdr/min)' 
 100  format(3x,a)
      write(out_unit,105) '>> '
 105  format(3x,a3,$)           !$--> ingen linieskift
      read(in_unit,110) n_rpm
 110  format(i6)
      write(out_unit,100) '>> Indtast krumtap radius (m)'
      write(out_unit,105) '>> '
      read(in_unit,115) r_krum
 115  format(f8.6)
      write(out_unit,100) '>> Indtast plejlstangs l�ngde (m)'
      write(out_unit,105) '>> '
      read(in_unit,115) l_plejl

C     Beregning af position,hastighed,accelleration.
C     Dette kunne liges� godt skrives direkte ud i en fil,
C     men det bliver her vist ved, at oprette en matrice 
C     indeholdende de relevante data.
C
C     Som det vil kunne ses i datafilen, s� er postitionen
C     af stemplet regnet som positiv i top d�d punktet (TDP),
C     hvorfor det afstedkommer en negativ accellerations kurve
C     samme sted.
C     ----------------------------------------------------

      w = (n_rpm/60)*2*pi

      do i=1,360
         data(1,i) = i-1
         alpha = i*(pi/180)
         kvad = sqrt(l_plejl*l_plejl - 
     &        0.5*r_krum*r_krum*(1-cos(2*alpha)) )
         data(2,i) = r_krum*cos(alpha) + kvad - l_plejl + r_krum
         data(3,i) = -w*( r_krum*sin(alpha) + 
     &        (r_krum*r_krum*sin(2*alpha))/(2*kvad))
         data(4,i) = -w*w*(r_krum*cos(alpha) +
     &        ( ((2*r_krum*r_krum*cos(2*alpha)*kvad) +
     &        (r_krum*r_krum*r_krum*r_krum*
     &        (1-cos(4*alpha))) / 4*kvad) /(4*kvad))
     &        )
      end do

C     Udskrivning af resultater til fil.
C     Der udskrives med kommentalinier i toppen af 
C     datafilen, s� der er overblik over den.
C     Da programmer som gnuplot og octave l�ser
C     '%' tegn som starten p� en kommentar linie v�lges
C     dette tegn til at indikere kommentarer.
C     -------------------------------------------------

      open(fil_out_unit,file='datafil.m')
      
      write(fil_out_unit,150) '% datafil udskrevet fra acc.f'
 150  format(a)
      write(fil_out_unit,150) '%'
      write(fil_out_unit,150) 
     & '% Vinkel      Position      Hastighed      Accelleration'
      write(fil_out_unit,150)
     & '% Grader           (m)          (m/s)            (m/s^2)'  

      do i=1,360
         write(fil_out_unit,160) i,data(2,i),
     &        data(3,i),data(4,i)
      end do
 160  format(5x,i3,3x,e12.6,3x,e12.6,5x,e12.6)

      close(fil_out_unit)

C     Afslutning af program
      end

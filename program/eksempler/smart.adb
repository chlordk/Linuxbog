------------------------------------------------------------------------------
--
--  procedure Smart (body)
--
--  Ada er temmelig praktisk til at skrive programmer der skal lave flere ting
--  p� en gang ("tasking"), og s� kan det h�ndtere maskinn�re ting som
--  bitm�nstre p� en abstrakt m�de, s� det er lettere at overskue hvad
--  programmet laver.
--
--  Programmet er afpr�vet med GNAT (GNU Ada), men b�r virke med alle
--  Ada-overs�ttere.
--
--  Overs�ttelse:
--    gnatmake udskriv -cargs -gnatv -gnati1 -gnatf -gnato
--
--  Afvikling:
--    ./udskriv [fil]+
--
------------------------------------------------------------------------------
--  Opdateringslog:
--
--  2000.01.09 (Jacob Sparre Andersen)
--    Skrevet.
--
------------------------------------------------------------------------------
--  Standardpakker:

with Ada.Text_IO;

------------------------------------------------------------------------------

procedure Smart is

   ---------------------------------------------------------------------------
   --  task Hils_P�_Verden
   --
   --  En tr�d der k�rer parallelt med resten af programmet.
   --  For at det ikke skal g� alt for hurtigt udskriver den kun et tegn i
   --  sekundet.

   task Hils_P�_Verden;

   task body Hils_P�_Verden is
      use Ada.Text_IO;
      Hilsen : constant String := "Hej Verden";
   begin
      for Indeks in Hilsen'Range loop
         Put (Hilsen (Indeks));
         delay 1.0;
      end loop;
      New_Line;
   end;

   ---------------------------------------------------------------------------
   --  Vi skal lige styre et lyssignal:
   --
   --  Vi har en 8-bit udgang:
   --    0-3 - ikke brugt
   --    4   - gr�n
   --    5   - gul
   --    6   - r�d
   --    7   - ikke brugt

   type Lyssignal_Lamper is
      record
         R�d, Gul, Gr�n : Boolean;
      end record;

   for Lyssignal_Lamper use
      record
         R�d  at 0 range 6 .. 6;
         Gul  at 0 range 5 .. 5;
         Gr�n at 0 range 4 .. 4;
      end record;

   for Lyssignal_Lamper'Size use 8;

   type Lyssignal is
      record
         T�ndt : Lyssignal_Lamper;
         Tid   : Duration;
      end record;

   Tidsserie : array (1 .. 4) of Lyssignal :=
                 (1 => (T�ndt => (R�d =>  True, Gul => False, Gr�n => False),
                        Tid   => 3.0),
                  2 => (T�ndt => (R�d =>  True, Gul =>  True, Gr�n => False),
                        Tid   => 0.5),
                  3 => (T�ndt => (R�d => False, Gul => False, Gr�n =>  True),
                        Tid   => 3.0),
                  4 => (T�ndt => (R�d => False, Gul =>  True, Gr�n => False),
                        Tid   => 0.5));

   ---------------------------------------------------------------------------
   --  procedure S�t_Port:
   --
   --  For at vi kan se hvad der sker, skriver vi bitm�nsteret til sk�rmen i
   --  stedet for at sende det ud p� en port.

   procedure S�t_Port (T�ndt : in     Lyssignal_Lamper) is

      type Byte is mod 2**8;
      for Byte'Size use 8;

      package Byte_Text_IO is new Ada.Text_IO.Modular_IO (Byte);

      use Ada.Text_IO;
      use Byte_Text_IO;

      Bitm�nster : Byte;
      for Bitm�nster'Address use T�ndt'Address;

   begin
      New_Line;
      Put (Item  => " -- S�tter porten til ");
      Put (Item  => Bitm�nster,
           Base  => 2,
           Width => 11);
      Put (Item  => " -- ");
      New_Line;
   end S�t_Port;

   ---------------------------------------------------------------------------

begin
   for Indeks in Tidsserie'Range loop
      S�t_Port (T�ndt => Tidsserie (Indeks).T�ndt);
      delay Tidsserie (Indeks).Tid;
   end loop;
end Smart;

--  af Peter Stubbe <stubbe@bitnisse.dk>
--  $Id$

--  2000.01.09: Skrevet om/rettet til af Jacob Sparre Andersen <sparre@nbi.dk>

--  Programmet er afpr�vet med GNAT (GNU Ada), men b�r virke med alle
--  Ada-overs�ttere.
--
--  Overs�ttelse:
--    gnatmake udskriv -cargs -gnatv -gnati1 -gnatf -gnato
--
--  Afvikling:
--    ./udskriv [fil]+

with Ada.Characters.Handling;
with Ada.Command_Line;
with Ada.Strings.Fixed;
with Ada.Text_IO;

procedure Udskriv is

   ---------------------------------------------------------------------------

   package Count_Text_IO is new Ada.Text_IO.Integer_IO (Ada.Text_IO.Count);

   ---------------------------------------------------------------------------

   procedure Vent_P� (Tegn : in     String) is

      use Ada.Strings.Fixed;
      use Ada.Text_IO;

      Tast    : Character;
      Trykket : Boolean;

   begin
      New_Line;
      loop
         Get_Immediate (Item      => Tast,
                        Available => Trykket);
         exit when Index (Source  => Tegn,
                          Pattern => (1 => Tast)) > 0;
      end loop;
      New_Line;
   end Vent_P�;

   ---------------------------------------------------------------------------

   use Ada.Command_Line;

begin
   for Indeks in 1 .. Argument_Count loop

   Udskriv_Fil:
      declare
         use Ada.Characters.Handling;
         use Ada.Text_IO;
         use Count_Text_IO;

         Navn  : String renames Argument (Indeks);
         Fil   : File_Type;
         Tegn  : Character;
      begin
         Open (File => Fil,
               Name => Navn,
               Mode => In_File);

      L�s_Linier:
         while not End_Of_File (Fil) loop
            Put (Item  => Line (Fil),
                 Width => 5);
            Set_Col (10);

            while not End_Of_Line (Fil) loop
               Get (File => Fil,
                    Item => Tegn);

               if Is_Graphic (Tegn) then
                  Put (Item => Tegn);
               else
                  Put (Item => "�");
               end if;

               exit L�s_Linier when End_Of_File (Fil);
            end loop;

            Skip_Line (File => Fil);
            New_Line;
         end loop L�s_Linier;

         Close (File => Fil);

         Vent_P� ("QqNn");
      exception
         when Name_Error =>
            Put_Line ("Filen " & Navn & " findes ikke!");
      end Udskriv_Fil;
   end loop;
end Udskriv;

------------------------------------------------------------------------------
--
--  procedure Barselsorlovsl�ngde (body)
--
--  Eksempel p� brug af betingede strukturer:
--
--    Programmet unders�ger hvor lang tids barselsorlov en person har ret til
--    udfra vedkommendes CPR-nummer.
--
--  Overs�ttelse og pr�vek�rsel:
--
--    Hvis du har installeret GNU Ada p� dit system og denne fil har navnet
--    "barselsorlovsl�ngde.ada" kan du overs�tte programmet med kommandoen:
--
--      gnatchop -w barselsorlovsl�ngde.ada; gnatmake barselsorlovsl�ngde
--
--    Dern�st kan du pr�vek�re det med kommandoen:
--
--      ./barselsorlovsl�ngde
--
--  Filen indeholder udover selve programeksemplet ogs� en pakke med nogle
--  definitioner der er specifikke for eksemplet, samt den generelt meget
--  nyttige pakke UStrings der kommer David A. Wheelers AdaCGI-pakke.
--
------------------------------------------------------------------------------
--  Standard packages:

with Ada.Text_IO;

------------------------------------------------------------------------------
--  Other packages:

with CPR_numre;

------------------------------------------------------------------------------

procedure Barselsorlovsl�ngde is

   use Ada.Text_IO; --  S� vi kan skrive resultatet ud.
   use CPR_numre;   --  S� vi kan rode med CPR_numre.

begin --  Barselsorlovsl�ngde
   if Er_Lige (CPR_nummerets_l�benummer) then
      Put_Line ("Hun har ret til seks m�neders barselsorlov.");
   else
      Put_Line ("Han han kun ret til to ugers barselsorlov.");
   end if;
exception
   when Ikke_et_CPR_nummer =>
      Put_Line ("Der blev ikke indtastet et CPR-nummer. Et CPR-nummer ");
      Put_Line ("best�r af seks ciffre (en f�dselsdato) efterfulgt af en");
      Put_Line ("bindestreg og fire ciffre (et l�benummer).");
end Barselsorlovsl�ngde;

------------------------------------------------------------------------------
--
--  package CPR_numre (spec)
--
--  Hj�lperoutiner til barselsorlovsl�ngdeeksemplet.
--
------------------------------------------------------------------------------

package CPR_numre is

   ---------------------------------------------------------------------------
   --  Fejltyper:

   Ikke_et_CPR_nummer : exception;

   ---------------------------------------------------------------------------
   --  subtype L�benumre:

   subtype L�benumre is Integer range 0000 .. 9999;

   ---------------------------------------------------------------------------
   --  function CPR_nummerets_l�benummer:

   function CPR_nummerets_l�benummer return L�benumre;

   ---------------------------------------------------------------------------
   --  function Er_Lige:

   function Er_Lige (Tal : in L�benumre) return Boolean;

   ---------------------------------------------------------------------------

end CPR_Numre;

------------------------------------------------------------------------------
--
--  package CPR_numre (body)
--
--  Hj�lperoutiner til barselsorlovsl�ngdeeksemplet.
--
------------------------------------------------------------------------------
--  Standard packages:

with Ada.Strings.Unbounded;
with Ada.Text_IO;

------------------------------------------------------------------------------
--  Other packages:

with UStrings;

------------------------------------------------------------------------------

package body CPR_numre is

   ---------------------------------------------------------------------------
   --  function CPR_nummerets_l�benummer:

   function CPR_nummerets_l�benummer return L�benumre is

      use Ada.Strings.Unbounded;
      use Ada.Text_IO;
      use UStrings;

      CPR_nummer : UString;

   begin --  CPR_nummerets_l�benummer
      Put (Item => "Indtast venligst personens CPR-nummer efterfulgt af et " &
                   "linieskift: ");
      Get_Line (Item => CPR_nummer);
      New_Line;

      if Length (CPR_nummer) = 6 + 1 + 4 then
         return L�benumre'Value (Slice (Source => CPR_nummer,
                                        Low    => 6 + 1 + 1,
                                        High   => 6 + 1 + 4));
      else
         raise Ikke_et_CPR_nummer;
      end if;
   exception
      when others =>
         raise Ikke_et_CPR_nummer;
   end CPR_nummerets_l�benummer;

   ---------------------------------------------------------------------------
   --  function Er_Lige:

   function Er_Lige (Tal : in L�benumre) return Boolean is

   begin
      return Tal mod 2 = 0;
   end Er_Lige;

   ---------------------------------------------------------------------------

end CPR_Numre;
with Text_IO, Ada.Strings.Unbounded;
use  Text_IO, Ada.Strings.Unbounded;

package Ustrings is

  -- This package provides a simpler way to work with type
  -- Unbounded_String, since this type will be used very often.
  -- Most users will want to ALSO with "Ada.Strings.Unbounded".
  -- Ideally this would be a child package of "Ada.Strings.Unbounded".
  --

  -- This package provides the following simplifications:
  --  + Shortens the type name from "Unbounded_String" to "Ustring".
  --  + Creates shorter function names for To_Unbounded_String, i.e.
  --    To_Ustring(U) and U(S).  "U" is not a very readable name, but
  --    it's such a common operation that a short name seems appropriate
  --    (this function is needed every time a String constant is used).
  --    It also creates S(U) as the reverse of U(S).
  --  + Adds other subprograms, currently just "Swap".
  --  + Other packages can use this package to provide other simplifications.

  -- Developed by David A. Wheeler; released to the public domain.

  subtype Ustring is Unbounded_String;

  function To_Ustring(Source : String)  return Unbounded_String
                                         renames To_Unbounded_String;
  function U(Source : String)           return Unbounded_String
                                         renames To_Unbounded_String;
  function S(Source : Unbounded_String) return String
                                         renames To_String;

  -- "Swap" is important for reuse in some other packages, so we'll define it.

  procedure Swap(Left, Right : in out Unbounded_String);

  -- I/O Routines.
  procedure Get_Line(File : in File_Type; Item : out Unbounded_String);
  procedure Get_Line(Item : out Unbounded_String);

  procedure Put(File : in File_Type; Item : in Unbounded_String);
  procedure Put(Item : in Unbounded_String);

  procedure Put_Line(File : in File_Type; Item : in Unbounded_String);
  procedure Put_Line(Item : in Unbounded_String);

end Ustrings;
package body Ustrings is

  Input_Line_Buffer_Length : constant := 1024;
    -- If an input line is longer, Get_Line will recurse to read in the line.


  procedure Swap(Left, Right : in out Unbounded_String) is
    -- Implement Swap.  This is the portable but slow approach.
    Temporary : Unbounded_String;
  begin
    Temporary := Left;
    Left := Right;
    Right := Temporary;
  end Swap;

  -- Implement Unbounded_String I/O by calling Text_IO String routines.


  -- Get_Line gets a line of text, limited only by the maximum number of
  -- characters in an Unbounded_String.  It reads characters into a buffer
  -- and if that isn't enough, recurses to read the rest.

  procedure Get_Line (File : in File_Type; Item : out Unbounded_String) is

    function More_Input return Unbounded_String is
       Input : String (1 .. Input_Line_Buffer_Length);
       Last  : Natural;
    begin
       Get_Line (File, Input, Last);
       if Last < Input'Last then
          return   To_Unbounded_String (Input(1..Last));
       else
          return   To_Unbounded_String (Input(1..Last)) & More_Input;
       end if;
    end More_Input;

  begin
      Item := More_Input;
  end Get_Line;


  procedure Get_Line(Item : out Unbounded_String) is
  begin
    Get_Line(Current_Input, Item);
  end Get_Line;

  procedure Put(File : in File_Type; Item : in Unbounded_String) is
  begin
    Put(File, To_String(Item));
  end Put;

  procedure Put(Item : in Unbounded_String) is
  begin
    Put(Current_Output, To_String(Item));
  end Put;

  procedure Put_Line(File : in File_Type; Item : in Unbounded_String) is
  begin
    Put(File, Item);
    New_Line(File);
  end Put_Line;

  procedure Put_Line(Item : in Unbounded_String) is
  begin
    Put(Current_Output, Item);
    New_Line;
  end Put_Line;

end Ustrings;

------------------------------------------------------------------------------
--
--  procedure Supermarkeds�bningstider (body)
--
--  Eksempel p� brug af betingede strukturer:
--
--    Programmet unders�ger hvor l�nge det lokale supermarked har �bent
--    afh�ngigt af hvilken dag i ugen det er.
--
--  Overs�ttelse og pr�vek�rsel:
--
--    Hvis du har installeret GNU Ada p� dit system, og denne fil har navnet
--    "supermarkeds�bningstider.ada", kan du overs�tte programmet med
--    kommandoerne:
--
--      gnatchop -w supermarkeds�bningstider.ada
--      gnatmake supermarkeds�bningstider
--
--    Dern�st kan du pr�vek�re det med kommandoen:
--
--      ./supermarkeds�bningstider
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

with �bningstider;

------------------------------------------------------------------------------

procedure Supermarkeds�bningstider is

   use Ada.Text_IO;   --  S� vi kan skrive resultatet ud.
   use �bningstider;  --  S� vi kan rode med ugedage, �bningstider m.m.

begin --  Supermarkeds�bningstider
   case I_dag is
      when Mandag | Tirsdag | Onsdag | Fredag =>
         Put_Line ("I dag er der �bent fra 9 til 18.");
      when Torsdag =>
         Put_Line ("I dag er der �bent fra 9 til 20.");
      when L�rdag =>
         Put_Line ("I dag er der �bent fra 10 til 17.");
      when S�ndag =>
         Put_Line ("I dag er der lukket hele dagen.");
   end case;
exception
   when Ikke_en_ugedag =>
      Put_Line ("Der blev ikke indtastet en ugedag.");
end Supermarkeds�bningstider;

------------------------------------------------------------------------------
--
--  package �bningstider (spec)
--
--  Hj�lperoutiner til supermarkeds�bningstidseksemplet.
--
------------------------------------------------------------------------------

package �bningstider is

   ---------------------------------------------------------------------------
   --  Fejltyper:

   Ikke_en_ugedag : exception;

   ---------------------------------------------------------------------------
   -- type Ugedage:

   type Ugedage is (Mandag, Tirsdag, Onsdag, Torsdag, Fredag, L�rdag, S�ndag);

   ---------------------------------------------------------------------------
   --  function I_dag:

   function I_dag return Ugedage;

   ---------------------------------------------------------------------------

end �bningstider;

------------------------------------------------------------------------------
--
--  package �bningstider (body)
--
--  Hj�lperoutiner til supermarkeds�bningstidseksemplet.
--
------------------------------------------------------------------------------
--  Standard packages:

with Ada.Strings.Unbounded;
with Ada.Text_IO;

------------------------------------------------------------------------------
--  Other packages:

with UStrings;

------------------------------------------------------------------------------

package body �bningstider is

   ---------------------------------------------------------------------------
   --  function I_dag:

   function I_dag return Ugedage is

      use Ada.Strings.Unbounded;
      use Ada.Text_IO;
      use UStrings;

      Ugedag_som_tekst : UString;

   begin --  I_dag
      Put (Item => "Hvilken ugedag er det i dag (afslut med linieskift)? ");
      Get_Line (Item => Ugedag_som_tekst);
      New_Line;

      return Ugedage'Value (To_String (Ugedag_som_tekst));
   exception
      when others =>
         raise Ikke_en_ugedag;
   end I_dag;

   ---------------------------------------------------------------------------

end �bningstider;

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

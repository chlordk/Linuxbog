(*
  udskriv.sml
	af Henning Niss <hniss@diku.dk>
  $Id$

	K�r programmet:
	1. Start mosml
	2. "udskriv.sml"
	3. main (["udskriv.sml"])
*)


(* [Fejl besked] exception der rejses n�r der opst�r fejl. *)
exception Fejl of string

(* [lase_filnavn ()] checker kommandolinien for netop et argument
   og bruger det som filnavn. Dern�st checkes om filen kan l�ses, og
   hvis dette er tilf�lde l�ses hele molevitten, og strengen opdeles
   i en liste af strenge - et element for hver linie i filen.
*)
fun laes_filnavn args =
    let (* find filnavnet *)
        val filnavn = hd args
                      handle List.Empty =>
                          raise Fejl("Kald programmet med et filnavn\n")
        (* check om filen kan l�ses *)
        val _ = if FileSys.access (filnavn,[]) then ()
                else raise Fejl("Filen " ^ filnavn ^ " kan ikke l�ses\n")
        (* l�s hele molevitten - ombryd ved linieskift *)
        val is = TextIO.openIn filnavn
    in  String.tokens (fn #"\n" => true | _ => false) (TextIO.inputAll is)
        before
        TextIO.closeIn is
    end

(* [udskriv_linier linier] udskriver listen af linier. Hver linie 
   foranstilles med et linienummer (h�jrejusteret i et felt p� 3).
*)
fun udskriv_linier linier =
    let val cifre = (Real.ceil o Math.log10 o Real.fromInt o length) linier
        fun hver_linie (linie, (nummer,acc)) = 
            ( nummer+1,
              StringCvt.padLeft #" " cifre (Int.toString nummer) ^ ": " ^
              linie ^ "\n" :: acc
            )
        val (_, linier) = List.foldl hver_linie (1,[]) linier
    in  app print (rev linier)
    end

(* [vent ()] l�ser fra standard input indtil der tastes Q <ENTER>. *)
fun vent () =
    let fun loop () = 
        if TextIO.inputLine TextIO.stdIn = "Q\n" then ()
        else loop ()
    in  loop ()
    end

(* [main ()] kombinerer laes_filnavn, udskriv_linier og vent. *)
fun main args = 
    (vent (udskriv_linier (laes_filnavn args)))
    (* der opstod en fejl - udskriv fejlmeddelelsen *)
    handle Fejl besked => TextIO.output(TextIO.stdErr, besked)

val _ = main (CommandLine.arguments())

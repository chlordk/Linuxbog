/* file krypter2.c - brugbar krypterings-funktion version 2.
 */
/* denne version af krypterings funktionen behandler
 * linieskift bedre. 
 * Output burde kun v�re, som i version 1, characters med v�rdier
 * fra 33 til 126.  Det er 93 v�rdier, men vi vil gerne have
 * repr�senteret 95 v�rdier, nemlig newline (10) og space (32)
 * samt 33-126.  Desuden vil vi ikke have, at linieskift
 * forbliver som i originalteksten, og spaces m� heller ikke
 * forblive spaces.
 *
 * Hvis ikke vi vil lave om p� selve programflowet, hvor en
 * oprindelig character mappes til en krypteret character (det
 * vil sige, hvis vi vil fastholde vores funktions-opdeling) s�
 * m� vi alts� skaffe 2 printable characters mere eller lade
 * nogle andre tegn udg� (fx. # og ` kunne v�re kandidater.)
 * 
 * Men i denne version v�lger vi alligevel at bruge space,
 * newline mapper vi til character 31, s� m� vi se, hvordan det
 * g�r ...
 */

static char *keystring = "Under traeerne var der stille og roligt.";
static int inuse;
static char *mv;
static int keylen;

int krypter(int inpchar)
{
   if (!inuse) {
       inuse = 1;
       mv = keystring;
       keylen = strlen(keystring);
   }
   if (mv - keystring > keylen)
       mv = keystring;
   if (inpchar < ' ') {
       if (inpchar == '\n')
          inpchar = 31;
       else
          inpchar = '.';
   } else if (inpchar > 126)
       inpchar = '.';
   return (inpchar + *mv++) % 96 + 31;
}



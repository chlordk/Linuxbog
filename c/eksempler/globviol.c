
/* fil 1. Demonstration af uheldig praksis. */

/* programmet kompilerer uden warnings - ikke engang option
 * -Wall, som ellers betyder "giv mig alle warnings", f�r gcc til
 * at reagere p� denne konstruktion! Men benyttes g++  f�r man
 * heldigvis en fejl p� denne uheldige fremgangsm�de.
 */

#include <stdio.h>

int min_globale_variabel; /* ok, det b�r enhver compiler acceptere. */

int min_globale_variabel; /* uha - burde udl�se en warning! */
int min_globale_variabel; /* uha - burde udl�se en warning! */
int min_globale_variabel; /* uha - burde udl�se en warning! */

int main()
{
     printf("V�rdien af min_globale_variabel er %d\n", min_globale_variabel);
     return 0;
}



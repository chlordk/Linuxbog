
/* file dollar3b.c - for lettere at holde styr p�, hvilke
 * eksempelfiler, der h�rer sammen.
 * kr2dollar, function som f�r kroner og returnerer dollar. */

static double kurs = 8.65; /* skulle sl� den op p� nettet! */

double kr2dollar(double k)
{
    return k / kurs;
}




/*file ordtael.c */

#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

typedef long long _int64;

/* taellere: char, words, num, andet, lines */
_int64 nc, nw, nn, na, nl; 

enum status_t { HVID, ALFA, TAL, PUNC };

enum status_t status;

int main()
{
    int c;
    while ( (c=getchar()) != EOF) {
        ++nc;
        if (c == '\n')
            ++nl;
        if (isspace(c)) {
            status = HVID;
        } else {
            if (status == HVID) {
                if (isalpha(c)) {
                    status = ALFA;
                    ++nw;
                } else if (isdigit(c)) {
                    status = TAL;
                    ++nn;
                } else ++na;
            } else if (status == ALFA) {
                if (!isalpha(c) && !isdigit(c))
                    ++na;
            } else if (status == TAL) {
                if (!isdigit(c) && !isalpha(c))
                    ++na;
            }
        }
    }
    printf("Antal: chr %Ld, ord: %Ld, tal %Ld, andet %Ld, lin. %Ld\n",
            nc, nw, nn, na, nl);
    return 0;
}

/* OBS: Der er lidt flere braces i ovenst�ende eksempel end 
 * n�dvendigt, det er i h�b om bedre l�selighed.
 */

/* end of file ordtael.c */



/* modular programmering - fil nr. 1, kryptio.c */
/* main l�ser fra tastatur (eller omdirigeret fil)
 * og skriver det krypterede bogstav ud p� sk�rmen.
 */

#include <stdio.h>
main()
{
    int c;
    while ( (c = getchar()) != EOF) {
        c = krypter(c);
        putchar(c);
    }
}



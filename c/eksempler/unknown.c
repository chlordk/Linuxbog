/* unknown.c typedef'er en pointer til ukendt entitet og
 * demonstrer dermed, at det er tilladt at bruge en pointer til
 * et objekt af ukendt st�rrelse.
 */

#include <stdio.h>

typedef struct __dummy *POINTER;

main()
{
	POINTER *p;

	p = fopen("asd0.sgml", "r");
	if (p == NULL)
		printf("Kan ikke �bne asd0.sgml\n");
	else
		printf("Har �bnet asd0.sgml\n");
	return 0;
}

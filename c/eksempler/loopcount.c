/* Demonstration af ad-hoc variabel. */

#include <stdio.h>

char *thisprog;

int main(int argc, char *argv[])
{
    thisprog = argv[0];
    printf("Program %s er startet ... \n", thisprog);
    {
        int jj = 0;
        while(jj++ < 10)
            printf("For %d. gang: Ih hvor vi k�rer\n", jj);
    }
    printf("Program %s exiter graci�st!\n", thisprog);
    return 0;
}




/* char2tal.c */

#include <stdio.h>

int main()
{
    int c;
    while ( (c=getchar()) != EOF) {
      printf("Decimal-v�rdi %3d, hexadecimal v�rdi 0x%2x ", c, c);
      if (c > 31 && c < 127)
          printf("%c\n", c);
      else
          printf(".\n");
    }
    return 0;
}

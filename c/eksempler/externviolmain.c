
/* program, som afpr�ver gcc's reaktionsevne p� duplicate
 * external definitions samt p� erkl�ring af externs, der ikke
 * har nogen tilsvarende "definition".
 */


int main()
{
    extern int globint1;
    extern int globint2;

    printf("globint1 er %d, globint2 er %d\n", globint1, globint2);

    return 0;
}

/* Unsigned integer promotion.
 * Med 32 bit (std. for 386++ og mange andre CPUer ::
 * En unsigned integer kan v�re 0 - 4 mia.
 * En signed int kan v�re       -2mia - +2mia.
 * Hvad sker der, n�r man blander dem?
 */

main()
{
    signed int tal;
    unsigned joke;

    joke = 1;
    tal = 2;

    if (joke - tal > 42)
        printf("Kan du forklare, hvorfor %d - %d er st�rre end 42?\n"
               "eller vil du hellere ned og k�be en ny CPU?\n", joke, tal);

    tal = -2;

    if (tal + joke > 42)
        printf("Det kan v�re sv�rt at finde fejl\n"
               "n�r man blander signed og unsigned!\n");

    return 0;
}



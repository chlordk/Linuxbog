/* floating point version af bogpris1 */
/* til alle praktiske form�l den fornuftigste version! */
/* til teoretiske form�l kan det v�re generende, at vi */
/* ikke ved, om der gemmer sig nogle betydende decimaler */
/* efter de f�rste 6 */

main()
{
    double bogpris = 360;
    double afgift = 1.0/3.0;

    printf("Afgift %f\n",bogpris * afgift);
    return 0;
}


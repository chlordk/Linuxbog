/* dirlist2.c l�ser og gemmer alle filnavne i et directory */

#include <stdio.h>
#include <stdlib.h>
#include <dirent.h>

int showdir(char *dirname);

main(int argc, char *argv[])
{
	char *dirname;
	if (argc<2)
		dirname = ".";
	else
		dirname = argv[1];

	return showdir(dirname);
}

/* et liste element indeholder en pointer til n�ste element samt data eller
 * som her en pointer til allokeret blok der kan variere i st�rrelse.
 * Som navnet antyder, kunne man save flere oplysninger om hver directory
 * entry, men her er det kun navnet, som saves. For et mere fuldkomment
 * eksempel p�, hvordan man gemmer alle oplysninger om en fil, se fileutils
 * source - eller nogle af de talrige andre eksempler, som kan findes p�
 * nettet (fx. metalab.unc.edu - s�g efter ydir).
 */

struct dirsav_t {
	char * name;
	struct dirsav_t * next;
};

static struct dirsav_t *createnew();
void insert_list(struct dirsav_t *head, char *data, int len);
void show_list(struct dirsav_t *head);

int showdir(char *name)
{
	DIR *d;
	struct dirent *dent;
	struct dirsav_t *head;

	if ((d=opendir(name)) == NULL)
		return 1;
	head = createnew();
	head->next = NULL;
	while (dent = readdir(d))
		insert_list(head, dent->d_name, strlen(dent->d_name));
	closedir(d);
	show_list(head);
	return 0;

}

/* ---------------------------8<----- cut here ----------------- */


/* createnew() stopper programmet, hvis der ikke er mere ram. Det er den
 * enkleste og mest relevante fejl h�ndtering (her): Succes eller d�.
 * Et mere sigende navn kunne v�re mustCreatenew(); */

static struct dirsav_t * createnew()
{
	void *p2l;
	p2l = malloc(sizeof(struct dirsav_t));
	if (!p2l) {
		perror("Could not get memory for listmember");
		exit(254);
	}
	return p2l;
}

/* denne insert funktion er s� simpel som mulig. Se evt. dirlist3.c (kommer
 * snart ... */

void insert_list(struct dirsav_t *ptr, char *obj, int len)
{
	struct dirsav_t *e;
				/* der reserveres hukommelse til det
				 * listeelement, som skal inds�ttes. */
	e = createnew();
	e->next = NULL;
				/* listen genneml�bes for at finde sidste
				 * element. Alternativt kunne man stoppe,
				 * n�r man n�ede det sted, hvor det nye
				 * element passede ind, alfabetisk sorteret. */
	while (ptr->next)
		ptr = ptr->next;
				/* nu inds�ttes det nye element i den next
				 * pointer, som f�r var NULL. */
	ptr->next = e;
				/* s� allokeres mere memory til den string
				 * eller andre data, som skal gemmes. */
	e->name = malloc(len+1);
				/* til sidst flyttes data over. Det er
				 * callers ansvar, at len er korrekt. */
	memcpy(e->name,obj,len);
}

/* l�b gennem listen, spring f�rste element over (for nemheds skyld bliver
 * det f�rste element aldrig brugt til andet end til at pege p� det n�ste)
 * */

void show_list(struct dirsav_t *ptr)
{
	while(ptr = ptr->next)
		printf("Filename: %s\n",ptr->name);

}


#! /bin/bash
# vim: ts=4 :
set -e

if ! which pandoc > /dev/null
then
	echo Fejl, pandoc ikke installere.
	echo sudo apt install pandoc
	exit 1
fi

if [ ! -e bog.sgml ]
then
	echo "Fejl, der skal være en 'bog.sgml' fil i dette subdir."
	exit 2
fi

if [ ! -d adoc/ ]
then
	mkdir adoc
fi

for S in *.sgml
do
	A=adoc/${S/.sgml/}.adoc
	if [[ $S =~ dato.sgml|magic.sgml|stikord.sgml|version.sgml ]]
	then
		echo \- $S
	else
		echo $S \> $A
		pandoc --wrap=none -f docbook -t asciidoc $S > $A
	fi
done

#!/bin/csh
# User .login file (/bin/csh initialization).

# S�tter max-st�rrelser p� programmer under tcsh
limit coredumpsize 0
limit stacksize 140000
limit datasize 140000
limit memoryuse 140000
limit filesize 2000000

# S�tter forskellige milj�-variable
setenv EDITOR emacs  # Emacs er min editor
setenv CVS_RSH ssh   # CVS k�res med ssh mellem klient og server
setenv GZIP "-9 -v"  # H�rdeste komprimering med gzip
setenv LESS "-MM -e -q"      # Ops�tning af less
setenv LESSCHARSET latin1    # Ops�tning af less
setenv PILOTRATE H115200     # Hurtigste overf�rsel til Palm pilot sync

# Dansk keyboard setup
setenv LC_ALL da_DK
setenv LANG da

# Man-sider findes her
setenv MANPATH /usr/local/man:/usr/man/preformat:/usr/share/man:/usr/X11/man

# S�tter min CVSROOT op til der hvor CVS har sine filer
setenv CVSROOT  tyge.sslug.dk:/usr/local/CVSROOT

# S�tter pager-variablen
if ( ! $?PAGER ) then
  setenv PAGER less
endif

# Kun hvis jeg logger ind i runlevel 3 i terminal 1, s� start X
# Kun relevant hvis default runlevel er 3.
if (`echo $tty` == tty1) startx

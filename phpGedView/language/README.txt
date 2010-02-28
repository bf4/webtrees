How to edit language files

1) Download and install POEdit from www.poedit.net

2) Configure as follows:

File->Preferences->Parsers->PHP->Edit

Language: PHP
List of extensions: *.php
Parser command: xgettext --force-po -o %o %C %K %F -L php
An item in keywords list: -k%k
An item in input files list: %f
Source code charset: --from-code=%c

3) Check-out or download the source tree

4) SVN edit/lock .PO and .MO

5) Open .PO file with POEdit.

6) Catalog->Update from sources

7) Edit!

8) File->Save (Updates the .MO as well)

9) Check-in or upload

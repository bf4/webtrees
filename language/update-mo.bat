REM Update all the .MO files from their .PO sources

SET PATH="c:\program files\poedit\bin";%PATH%

FOR %%L IN (ar ca cs da de el en en_GB es es_419 et fi fr he hu id it lt nl nn pl pt ro ru sk sl sr sv tr vi zh) DO msgfmt --output-file=%%L.mo %%L.po



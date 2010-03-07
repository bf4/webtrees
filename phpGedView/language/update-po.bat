REM Update all the .PO from the .PHP sources

SET PATH="c:\program files\poedit\bin";%PATH%

FOR %%L IN (ar ca cs da de el en en_GB es es_419 et fi fr he hu id it lt nl nn pl pt ro ru sk sl sr sv tr vi zh) DO DIR /B /S ..\*.php | xgettext --no-wrap --force-po --join-existing --sort-by-file --output=%%L.po --language=php --add-comments=I18N --from-code=UTF-8 --keyword=translate --keyword=plural --keyword=noop --files-from=-


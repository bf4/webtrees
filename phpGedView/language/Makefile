PACKAGE=webtrees
POT=$(PACKAGE).pot
PO=$(wildcard *.po)
MO=$(patsubst %.po,%.mo,$(PO))
PHP=$(shell find .. -path ../library -prune -o -name '*.php' -print)

default: $(MO)

################################################################################
# Compile the .PO files into .MO files
################################################################################
%.mo: %.po
	msgfmt --output-file=$@ $<

################################################################################
# Merge new msgid strings into the existing .PO files
################################################################################
%.po: $(POT)
	msguniq --output-file=$@ --unique --use-first --no-wrap $@
	msgmerge --update --no-wrap --silent --backup=none $@ $(POT)
	touch $@

################################################################################
# Update the PO Template file from the sources.  This file is used:
# (a) to create a .PO file for a new language
# (b) to update an existing .PO file with new msgid strings
################################################################################
$(POT): $(PHP) Makefile
	xgettext --package-name=$(PACKAGE) --package-version=1.0 --msgid-bugs-address=i18n@webtrees.net --output=$@ --no-wrap --language=PHP --add-comments=I18N --from-code=utf-8 --keyword --keyword=translate --keyword=plural --keyword=noop $(PHP)

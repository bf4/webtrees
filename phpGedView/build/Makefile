SHELL=bash
PACKAGE=webtrees
SVNVERSION=$(shell svnversion .. | tr -d 'M')
LABEL=$(PACKAGE)-$(SVNVERSION)
POT=$(PACKAGE).pot
PO=$(wildcard *.po)
MO=$(patsubst %.po,%.mo,$(PO))
PHP=$(shell find .. -path ../library -prune -o -path ../build -prune -o -name '*.php' -print)

default: $(PHP) $(POT) Makefile
	rm -Rf $(LABEL)*
	svn export .. $(LABEL)
	rm -R $(LABEL)/library/Zend/{Acl,Amf,Application,Auth,Barcode,Captcha,CodeGenerator,Console,Controller,Config,Crypt,Currency,Date,Db,Debug,Dojo,Dom,Feed,File,Filter,Form,Gdata,Http,InfoCard,Json,Layout,Ldap,Log,Mail,Markup,Measure,Memory,Mime,Navigation,Oauth,OpenId,Paginator,Pdf,ProgressBar,Queue,Reflection,Rest,Search,Serializer,Service,Session,Soap,Tag,Test,Text,TimeSync,Tool,Uri,Validate,Version,View,Wildfire,XmlRpc}*
	rm -R $(LABEL)/build
	rm -R $(LABEL)/language/*.po
	zip -qr $(LABEL).zip $(LABEL)
	tar cf $(LABEL).tar $(LABEL) && bzip2 -k $(LABEL).tar
	tar cf $(LABEL).tar $(LABEL) && gzip $(LABEL).tar
	rm -Rf $(LABEL)
	@echo
	@echo Upload webtrees.pot to https://translations.launchpad.net/webtrees/trunk/+translations-upload
	@echo
	@echo Upload $(LABEL).zip/gz/.bz2 to https://
	@echo

################################################################################
# Update the PO Template file from the sources.
################################################################################
$(POT): $(PHP) Makefile
	xgettext --package-name=$(PACKAGE) --package-version=1.0 --msgid-bugs-address=i18n@webtrees.net --output=$@ --no-wrap --language=PHP --add-comments=I18N --from-code=utf-8 --keyword --keyword=translate:1 --keyword=translate_c:1c,2 --keyword=plural:1,2 --keyword=noop:1 $(PHP)

issue list na githubu
---
blog:
----
	Bude sloužit jako jakýsi textový názor na produkt
články o zdraví (témata):
------------------------
	v článku budou odkazy na stávající produkty
komponenta plusy-mínusy:
------------------------
	* váhové vyjádření produktu
	* mělo by sloužit jako oblíbené hodnocení produktu
vyhledávání:
-----------
	hledání v katalogu produktů, ale i v článcích
prohledávač článků o produktu:
-----------------------------
	* bude nějaký zdroj dat (google/seznam ...) který se bude prohledávat, seznam článků bude uložen do db a bude pak hodnocen z administrace.
	podle toho bude sestavovaný hlavní článek.
	* z toho důvodu bude nutné upravit stávající rozdělení sloupců databáze.
sledování cen u prodejce:
------------------------
	* bude se aktualizovat cena od prodejce, ta se uloží v db, ale bude nutné ji nejprve schválit (aby se nestáhl nějaký nesmysl)
	tady se využije událost, pokud bude nová cena, přijde email.
požadavky:
---------
	* login uživatele
	* generátor site.xml (bude prováděn cronem, aby se osvojila tato technika)
statistiky:
----------
	* v administraci přehled na stránce Dashboard
	* přístupy z google-analytics
	* jiné statistiky

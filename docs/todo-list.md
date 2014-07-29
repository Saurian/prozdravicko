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
komponenty ostatní:
------------------
	* grid factory do samostatného souboru
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
	* bude možné sledovat vývoj cen, rozdělí se tedy na další tabulku.
požadavky:
---------
	* login uživatele
	* generátor site.xml (bude prováděn cronem, aby se osvojila tato technika)
statistiky:
----------
	* v administraci přehled na stránce Dashboard
	* přístupy z google-analytics
	* jiné statistiky
generování souborů z console nové příkazy:
-----------------------------------------
	* generování formulářů
	* generování repozitářů
refaktoring:
-----------
	* katalog entity utvořit jinak.
	item -> product
	* formuláře vyhodit z namespace admin
	* repozitáře vyhodit z namespace admin
	* model -> models namespace

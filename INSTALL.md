
# Cara Install Boilerplate ADMS Level 4

1. Install CI4 fresh: composer create-project codeigniter4/appstarter
2. Copy folder app/ dari zip ini ke project CI4 kamu (overwrite)
3. Import adms.sql ke database hosting
4. Edit app/Config/Database.php
5. Edit app/Config/Filters.php -> exclude csrf untuk iclock/*
6. Copy Routes dari app/Config/Routes-ADDTHIS.php ke Routes.php
7. Akses /admin -> Dashboard GUI
8. Setting mesin absen ADMS Server = domain kamu, Port 443

Test curl:
curl -X POST https://domainkamu.com/iclock/cdata?SN=TEST123&table=ATTLOG -d "1	2026-07-23 20:00:00	0	1	0	0"

Harus balik OK dan masuk ke adms_attlog.

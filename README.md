# Simple CMS

Tento projekt vznikol pôvodne ako záverečný programátorký test na predmet Programovanie webových aplikácii. Následne bol rozšírený pre potreby Návrhových vzorov. Špecifikácia pôvodného projektu sa nachádza [tu](https://webik.ms.mff.cuni.cz/nswi142/semestral-work/specification-cs.html).

## Rozšírenie

Pridal som abstrakciu nad databázovým driverom pomocou návrhového vzoru Factory method. Klientsky kód interaguje iba s abstrakciou, ktorá je [tu](db-provider/interface.php). Konkrétna implementácia sa získa zavolaním statickej metódy `DbProviders::get_provider($provider)` podľa požadovanej databázi. Factory method nám umožňuje zmeniť konkrétnu databázu zmenou iba na jednom mieste v kóde.

### Spustenie

Sú podporované 2 databáze, [MySQL](db-provider/mysqli/provider.php) (MariaDB) a [PostgresSQL](db-provider/pgsql/provider.php). Menia sa zmenou v [docker compose](compose.yaml), zmenou premenných prostredia v `.env` (dajú sa použiť hodnoty z [example](.env.example)) - `DB_PORT` = 5432 pre postgres, 3306 pre mysql, `DB_PROVIDER` = pgsql pre postgres, mysqli pre mysql.

Pre spustenie stačí použiť `docker compose up --build` a následne otvoriť [localhost](http://localhost:8080).

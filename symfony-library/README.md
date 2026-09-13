# Bibliotek — Symfony + Doctrine ORM CRUD

Detta paket innehåller färdig kod för en "Bibliotek"-modul som du droppar in
i ett Symfony-projekt. Den täcker alla krav: landningssida, navbar-länk,
CREATE/READ ONE/READ MANY/UPDATE/DELETE mot en `books`-tabell, GET+POST enligt
kraven, samt en valfri `/library/reset`-route.

## Filstruktur i paketet

```
src/Entity/Book.php               Entitet + Doctrine-mappning
src/Repository/BookRepository.php Repository med save()/remove()
src/Form/BookType.php             Formulär för skapa/uppdatera
src/Controller/LibraryController.php  Alla /library-routes
src/DataFixtures/BookFixtures.php Valfria fixtures (3 startböcker)
migrations/Version20260913000000.php  Migration som skapar books-tabellen
templates/base.html.twig          Bas-layout med navbar (länk "Bibliotek")
templates/library/index.html.twig     Landningssida
templates/library/list.html.twig      READ MANY (tabell)
templates/library/show.html.twig      READ ONE (detaljsida)
templates/library/new.html.twig       CREATE (formulär)
templates/library/edit.html.twig      UPDATE (formulär)
```

## 1. Skapa Symfony-projektet

```bash
composer create-project symfony/skeleton library-app
cd library-app

# Webserver + Twig + formulär + validering
composer require symfony/webapp-pack

# Doctrine ORM (fristående projekt, integreras via detta paket)
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle

# Valfritt: fixtures för exempeldata
composer require --dev doctrine/doctrine-fixtures-bundle
```

## 2. Konfigurera databasen (MariaDB/MySQL)

Redigera `.env` (eller `.env.local`):

```
DATABASE_URL="mysql://ANVANDARE:LOSENORD@127.0.0.1:3306/library_db?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```

Skapa databasen:

```bash
php bin/console doctrine:database:create
```

## 3. Kopiera in filerna

Kopiera hela `src/`, `templates/` och `migrations/`-innehållet från detta
paket in i ditt Symfony-projekt (motsvarande mappar finns redan där —
lägg bara till/skriv över filerna). Om du redan har en egen `base.html.twig`,
lägg istället bara till navbar-länken till Bibliotek:

```twig
<a class="nav-link" href="{{ path('library_index') }}">Bibliotek</a>
```

## 4. Kör migrationen (skapar tabellen `books`)

```bash
php bin/console doctrine:migrations:migrate
```

Om du hellre vill att Symfony genererar migrationen automatiskt utifrån
entiteten (istället för att använda den bifogade migrationsfilen):

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## 5. Lägg in tre böcker

**Alternativ A — via fixtures:**

```bash
php bin/console doctrine:fixtures:load
```

**Alternativ B — via webbläsaren:** starta servern (se steg 6) och besök
`http://127.0.0.1:8000/library/reset`, vilket seedar samma tre böcker
(Sagan om ringen, 1984, Harry Potter och De Vises Sten).

## 6. Starta applikationen

```bash
symfony server:start
# eller
php -S 127.0.0.1:8000 -t public/
```

Besök sedan:

- `/library` — landningssida
- `/library/books` — alla böcker (tabell)
- `/library/books/new` — lägg till bok
- `/library/books/{id}` — detaljer om en bok
- `/library/books/{id}/edit` — uppdatera bok
- `/library/reset` — återställ databasen (valfritt krav)

## Hur kraven uppfylls

| Krav | Lösning |
|---|---|
| Landningssida + navbar | `library_index`-routen + länk i `base.html.twig` |
| Databas med böcker | `books`-tabell via migration, 3 böcker via fixtures/reset |
| Bild som representerar boken | Omslag hämtas dynamiskt från Open Library Covers API via ISBN (`getCoverImageUrl()`), ingen filuppladdning krävs |
| CREATE | `library_book_new` (GET visar formulär, POST sparar) |
| READ ONE | `library_book_show` |
| READ MANY | `library_book_list`, klickbara rader länkar till detaljsidan |
| UPDATE | `library_book_edit` (GET visar ifyllt formulär, POST sparar) |
| DELETE | `library_book_delete`, endast POST + CSRF-token, med bekräftelsedialog |
| GET/POST-regel | Alla formulär använder POST vid ändring; visning sker via GET |
| Sammankopplad UX | Navbar → landningssida → lista → detalj → redigera/radera, allt länkat |
| Reset (valfritt) | `library_reset`-routen |

## Anpassa ISBN-bilderna

Om en bok saknar omslag hos Open Library visas en trasig bild. Du kan
enkelt byta till en egen bildfil per bok genom att lägga till ett
`imageFilename`-fält på entiteten och en filuppladdning i formuläret,
om ni vill kunna ladda upp egna bilder istället.

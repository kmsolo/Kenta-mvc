# Kort & kortlek – Symfony (MVC)

## Struktur

```
src/
  Enum/
    Suit.php        # Kortfärg (Model)
    Rank.php        # Kortvalör (Model)
  Model/
    Card.php        # Ett enskilt kort (Model)
    Deck.php        # Kortleken, 52 kort (Model)
  Controller/
    DeckController.php   # Hanterar routes /deck, /deck/shuffle, /deck/draw, /deck/reset (Controller)
templates/
  deck/
    index.html.twig # Visar kortleken (View)
```

## Hur det hänger ihop (MVC)

- **Model** (`Card`, `Deck`, `Suit`, `Rank`): all spellogik — att bygga en
  full lek, blanda, dra kort. Klasserna vet ingenting om HTTP, Twig
  eller sessioner.
- **View** (`deck/index.html.twig`): ren presentation. Får en lista av
  `Card`-objekt och antal kvarvarande kort, och renderar dem.
- **Controller** (`DeckController`): kopplingen mellan de två. Läser/
  skriver tillstånd (här: sessionen, i väntan på databas), anropar
  Model och skickar resultatet till View.

## Installation i ett befintligt Symfony-projekt

1. Skapa ett nytt Symfony-projekt om du inte redan har ett:
   ```bash
   composer create-project symfony/skeleton kortlek
   cd kortlek
   composer require twig session
   ```
2. Kopiera in `src/` och `templates/` från detta paket i projektroten
   (skriv över/komplettera befintliga mappar).
3. Kontrollera att `config/packages/framework.yaml` har sessioner
   aktiverade (`framework.session.enabled: true`, standard i senare
   Symfony-versioner).
4. Starta servern:
   ```bash
   symfony server:start
   # eller: php -S localhost:8000 -t public
   ```
5. Besök `http://localhost:8000/deck`.

## Testidéer

- `DeckTest`: att en ny lek innehåller 52 unika kort.
- `DeckTest`: att `draw()` minskar antalet kort med 1 och returnerar
  `null` när leken är tom.
- `CardTest`: att `getLabel()`/`__toString()` ger förväntad text.

Composer – Paket‑hanterare för PHP.

Docker – Kör isolerade miljöer (containers).

Devin – AI‑programmerare som bygger projekt åt dig.

Blend for Visual Studio – Verktyg för att designa XAML‑gränssnitt (WPF/UWP).

DevTools – Webbläsarens utvecklingsverktyg (F12).

DevToys – “Schweizisk armékniv” med småverktyg för utvecklare.

FrameView – Nvidia‑verktyg för att mäta FPS och prestanda i spel.

Node.js – JavaScript‑motor för backend och utvecklingsverktyg.

Postman – Verktyg för att testa API:er.

📘 1. Composer
Vad det gör:  
Composer är PHP:s paket‑ och beroendehanterare.
Det installerar bibliotek, uppdaterar versioner och håller koll på projektets beroenden.

Exempel:

composer install

composer update

Du använder det när:

du jobbar med PHP‑projekt

du bygger CMS som WordPress, Drupal, Grav, Automad

du behöver paket som Twig, Symfony, Laravel

Relevans för dig:  
✔ Om du jobbar med PHP‑baserade CMS
✖ Inte relevant för SvelteKit

🐳 2. Docker
Vad det gör:  
Docker kör containers, alltså isolerade mini‑system med egen server, databas, PHP, Node osv.

Varför det är bra:

samma miljö på alla datorer

inga “det funkar hos mig men inte hos dig”

perfekt för backend, databaser, API:er

Du använder det när:

du kör DDEV (som bygger på Docker)

du vill ha en ren utvecklingsmiljö

du jobbar med databaser eller CMS

Relevans för dig:  
✔ Mycket relevant om du kör DDEV eller backend
✔ Bra för SvelteKit + API‑projekt
✖ Inte nödvändigt för små projekt

🤖 3. Devin
Vad det gör:  
Devin är en AI‑utvecklare som kan:

skriva kod

bygga projekt

köra terminalkommandon

skapa Docker‑miljöer

fixa buggar

göra pull requests

Det är som att ha en junior programmerare som jobbar åt dig.

Relevans för dig:  
✔ Kan hjälpa dig bygga SvelteKit‑projekt
✔ Bra för hobbykodning
✔ Bra för att lära sig genom att se hur den löser problem

🎨 4. Blend for Visual Studio
Vad det gör:  
Blend är ett UI‑designverktyg för XAML i Visual Studio.

Används för:

WPF‑appar

UWP‑appar

Windows‑desktop UI

animeringar och layout

Relevans för dig:  
✖ Inte relevant för webbutveckling
✖ Inte relevant för SvelteKit
✔ Endast om du bygger Windows‑appar

🛠️ 5. DevTools (Chrome/Firefox)
Vad det gör:  
DevTools är webbläsarens utvecklingsverktyg, öppnas med F12.

Du kan:

inspektera HTML

ändra CSS live

debugga JavaScript

se nätverksanrop

mäta prestanda

se konsolloggar

Relevans för dig:  
⭐⭐⭐⭐⭐ Absolut nödvändigt för webbutveckling
✔ SvelteKit
✔ dbwebb‑kurser
✔ CSS‑design (passar din rosa estetik)

🧰 6. DevToys
Vad det gör:  
DevToys är en verktygslåda med massor av små funktioner:

JSON formatter

Base64 encoder/decoder

UUID‑generator

Diff‑verktyg

Regex‑tester

Markdown‑konverterare

Bildkomprimering

Relevans för dig:  
✔ Perfekt för dbwebb‑uppgifter
✔ Perfekt för webbutveckling
✔ Perfekt för hobbykodning
✔ Perfekt för CMS‑arbete

🎮 7. FrameView (Nvidia)
Vad det gör:  
FrameView är ett prestandamätningsverktyg för spel.

Det visar:

FPS

frametimes

GPU‑belastning

temperaturer

Relevans för dig:  
✔ Om du testar spel eller GPU‑prestanda
✖ Inte relevant för webbutveckling

⚙️ 8. Node.js
Vad det gör:  
Node är en JavaScript‑motor som kör JS utanför webbläsaren.

Du använder det för:

SvelteKit

npm‑paket

utvecklingsservrar

backend‑API:er

byggverktyg (Vite, Webpack, Rollup)

Relevans för dig:  
⭐⭐⭐⭐⭐ Helt centralt för SvelteKit
✔ All modern webbutveckling
✔ dbwebb‑uppgifter
✔ CLI‑kommandon (du gillar PowerShell)

🔗 9. Postman
Vad det gör:  
Postman är ett verktyg för att testa API:er.

Du kan:

skicka GET/POST/PUT/DELETE

testa headers, tokens, cookies

bygga API‑samlingar

automatisera tester

Relevans för dig:  
✔ Om du bygger backend
✔ Om du jobbar med REST eller GraphQL
✔ Om du testar externa API:er
✖ Mindre relevant för ren frontend

Verktyg Roll Varför det passar dig
Claude Free Kod, struktur, felsökning Bäst på SvelteKit, Node, CSS
Perplexity Free Fakta, dokumentation Perfekt research‑motor
Copilot Free Windows + PowerShell Du använder CLI mycket
Cursor Free Lokal kod‑AI Ser hela projektet
Codeium Autocomplete Snabbare kodning
DevToys Verktygslåda dbwebb + hobbykodning
Devv.ai Kod‑agent Devin‑liknande gratisalternati

┌──────────────────────────┐
│ Perplexity │
│ Research • Fakta • Docs │
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ Claude │
│ Kod • Struktur • Logik │
│ Felsökning • Förklaringar│
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ Cursor │
│ Lokal AI-editor • Ser │
│ hela projektet │
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ Codeium │
│ Autocomplete • Snabb kod │
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ Claude │
│ Felsökning av kod │
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ Copilot │
│ Windows • PowerShell │
│ CLI-problem • System │
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ DevToys │
│ JSON • Diff • Regex │
│ Bildverktyg • Formatter │
└──────────────┬───────────┘
│
▼
┌──────────────────────────┐
│ Devv.ai │
│ Automatisering • Generera│
│ filer • Boilerplate │
└──────────────────────────┘

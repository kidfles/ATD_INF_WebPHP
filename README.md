# 🇳🇱 ATD Webshop & Marktplaats

Welkom bij de **ATD Webshop**, een geavanceerd platform voor het kopen, verkopen, huren en veilen van professionele apparatuur. Dit project is gebouwd met moderne webtechnologieën en biedt een robuuste oplossing voor zowel particuliere gebruikers als zakelijke adverteerders.

---

## 🚀 Over de Applicatie

ATD Webshop is meer dan een standaard marktplaats. Het systeem combineert e-commerce functionaliteiten met specifieke bedrijfslogica voor verhuur en veilingen.

### Belangrijkste Functionaliteiten

*   **🛒 Marktplaats:** Een uitgebreide catalogus met geavanceerde filters (categorie, prijs, type) en zoekfunctionaliteit.
*   **🤝 Verhuur Module:** Volledig verhuursysteem met beschikbaarheidskalender (geen dubbele boekingen mogelijk), datum-gebaseerde prijsberekening en retourbeleid.
*   **🔨 Veilingen:** Real-time biedsysteem met automatische sluitingstijden.
*   **🏢 Whitelabel Bedrijfspagina's:** Zakelijke gebruikers kunnen hun eigen "minishop" creëren met aangepaste branding (kleuren, logo's), contentblokken en een eigen URL-slug.
*   **📄 Contractbeheer:** Geïntegreerde workflow voor het genereren (PDF), uploaden en goedkeuren van contracten voor zakelijke API-toegang.
*   **🌍 Meertaligheid:** Volledig ondersteund in Nederlands (NL) en Engels (EN).
*   **📱 Responsive Dashboard:** Uitgebreid dashboard voor het beheren van advertenties, bestellingen, verhuur en favorieten.

---

## 🛠️ Technische Stack

Het project is gebouwd op een solide, schaalbare basis met focus op performance en developer experience.

### Backend
*   **[Laravel 11](https://laravel.com):** Het PHP-framework voor de backend logica, routing, authenticatie en database interacties.
*   **MySQL / SQLite:** Databasemanagement.
*   **Laravel Breeze:** Lichtgewicht authenticatie-systeem.

### Frontend
*   **[Blade Templates](https://laravel.com/docs/blade):** Server-side rendering van views.
*   **[Tailwind CSS](https://tailwindcss.com):** Utility-first CSS framework voor styling en responsiviteit.
*   **[Alpine.js](https://alpinejs.dev):** Lichtgewicht JavaScript framework voor interactieve UI-componenten (dropdowns, modals, dynamische formulieren).
*   **[Flatpickr](https://flatpickr.js.org/):** Gebruiksvriendelijke datumkiezer voor verhuur beschikbaarheid.
*   **Vite:** Moderne, razendsnelle build tool voor assets.

---

## 📦 Installatie & Setup

Volg deze stappen om het project lokaal te draaien:

1.  **Clone de repository:**
    ```bash
    git clone <repository-url>
    cd ATD_INF_WebPHP
    ```

2.  **Installeer dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Omgevingsvariabelen instellen:**
    Kopieer `.env.example` naar `.env` en configureer je database gegevens.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database migraties & seeding:**
    Maak de database structuur aan en vul deze met testdata (gebruikers, advertenties, verhuurdata).
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Tip: De seeder maakt diverse testgebruikers aan, waaronder admin en zakelijke accounts.*

5.  **Start de applicatie:**
    Start de PHP server en de asset compiler in twee aparte terminals:
    ```bash
    # Terminal 1
    php artisan serve
    
    # Terminal 2
    npm run dev
    ```

De applicatie is nu bereikbaar op `http://localhost:8000`.

---

## 🧪 Test Accounts

Na het uitvoeren van de seeders kun je inloggen met de volgende accounts (wachtwoord is standaard `password`):

*   **Prive Verkoper:** `john@example.com`
*   **Zakelijke Verkoper:** `info@techhub.nl`
*   **Standaard User:** `user@example.com`

---

## 📖 Technische Documentatie

Voor een diepgaande uitleg van de implementatie, best practices en unieke features, zie:

👉 **[DOCUMENTATION.md](DOCUMENTATION.md)** - Volledige technische documentatie

Deze documentatie bevat:
- ✅ **Routes/Controllers/Middleware** - Zone-based routing, resource controllers, custom middleware
- ✅ **Migrations/Database Ontwerp** - Schema rationale, foreign keys, polymorfisme
- ✅ **Eloquent ORM** - Relationships, query scopes, business logic
- ✅ **Forms/Resources/Views** - FormRequest validation, Blade components, error handling
- ✅ **Unieke Waarde Propositie** - Whitelabel, verhuur engine, contract management, polymorfische reviews
- ✅ **Architectuur Diagrammen** - ASCII visualisaties van key features en system layers

---

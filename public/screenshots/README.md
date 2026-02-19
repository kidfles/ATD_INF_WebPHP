# Screenshots Directory

Dit directory bevat screenshots van de belangrijkste features van de ATD Webshop voor demonstratie doeleinden.

## Benodigde Screenshots

Voor de volledige documentatie moeten de volgende screenshots worden toegevoegd:

### 1. Dashboard Overzicht (`dashboard-overview.png`)
- Toon het hoofddashboard met:
  - Overzicht van eigen advertenties
  - Recente verhuuractiviteiten
  - Statistieken (aantal biedingen, actieve advertenties)
  - Navigatie sidebar

**Hoe te maken:**
1. Log in als zakelijke gebruiker (`info@techhub.nl` / `password`)
2. Navigeer naar `/dashboard`
3. Neem een full-page screenshot

---

### 2. Whitelabel Bedrijfspagina Editor (`whitelabel-builder.png`)
- Toon de page component builder met:
  - Lijst van componenten (Hero, Text, Featured Ads)
  - Drag-and-drop interface
  - Brand color picker
  - Custom URL slug veld

**Hoe te maken:**
1. Log in als zakelijke gebruiker
2. Navigeer naar `/dashboard/company/settings`
3. Scroll naar "Whitelabel Pagina Componenten" sectie
4. Neem screenshot van de component editor

---

### 3. Verhuurkalender (`rental-calendar.png`)
- Toon de agenda met:
  - Kalenderweergave (FullCalendar)
  - Verhuurperiodes (groen gemarkeerd)
  - Veiling einddatums (rood gemarkeerd)
  - Legenda

**Hoe te maken:**
1. Log in als verhuurder met actieve verhuurafspraken
2. Navigeer naar `/dashboard/agenda`
3. Neem screenshot van de kalenderweergave

---

### 4. Contract Workflow (`contract-workflow.png`)
- Toon het contract management scherm met:
  - "Download Template" knop
  - "Upload Getekend Contract" upload veld
  - Contract status (pending/approved)
  - API Token generatie sectie (disabled tot goedkeuring)

**Hoe te maken:**
1. Log in als nieuw zakelijk account (zonder goedgekeurd contract)
2. Navigeer naar `/dashboard/company/settings`
3. Scroll naar "Contract Beheer" sectie
4. Neem screenshot

---

### 5. Advertentie Aanmaken Form (`advertisement-create.png`)
- Toon het formulier met:
  - Type selector (Verkoop/Verhuur/Veiling)
  - Gerelateerde producten selector
  - Image upload preview
  - Conditionally rendered velden (expires_at voor veiling)

**Hoe te maken:**
1. Log in als adverteerder
2. Navigeer naar `/dashboard/advertisements/create`
3. Selecteer "Veiling" als type (om expires_at veld te tonen)
4. Neem screenshot

---

### 6. Marktplaats met Filters (`market-filters.png`)
- Toon de publieke marktplaats met:
  - Zoekbalk
  - Type filters (Verkoop/Verhuur/Veiling)
  - Sortering opties
  - Grid van advertenties
  - Sticky filter functionaliteit

**Hoe te maken:**
1. Navigeer naar `/market` (geen login nodig)
2. Pas enkele filters toe
3. Neem screenshot van de gefilterde resultaten

---

### 7. Polymorfische Reviews (`reviews-display.png`)
- Toon een advertentie detail pagina met:
  - Reviews op de advertentie zelf
  - Reviews op de verkoper (met link naar verkopersprofiel)
  - "Alleen geverifieerde kopers" badge

**Hoe te maken:**
1. Navigeer naar een advertentie detail pagina met reviews
2. Zorg dat zowel advertentie als verkoper reviews zichtbaar zijn
3. Neem screenshot

---

## Voorbeeld Integration in Documentatie

Na het maken van de screenshots, voeg ze toe aan `DOCUMENTATION.md`:

```markdown
### 📸 Screenshots & Demo

#### Dashboard Overzicht
![Dashboard Overzicht](../screenshots/dashboard-overview.png)
*Centraal dashboard met overzicht van advertenties, verhuur en biedingen*

#### Whitelabel Bedrijfspagina Editor
![Whitelabel Builder](../screenshots/whitelabel-builder.png)
*Drag-and-drop page builder voor zakelijke accounts met realtime preview*

#### Verhuurkalender
![Rental Calendar](../screenshots/rental-calendar.png)
*Agenda dashboard met verhuurperiodes en veiling deadlines*
```

---

## Tips voor Goede Screenshots

- **Resolutie**: Gebruik minimaal 1920x1080 voor scherpte
- **Privacy**: Gebruik test data (geen echte namen/adressen)
- **Browser**: Chrome/Firefox met developer tools voor consistent formaat
- **Annotations**: Optioneel: Voeg pijlen/tekst toe om belangrijke features te highlighten
- **Format**: PNG voor kwaliteit, JPEG voor kleinere bestandsgrootte

---

## Screenshot Tools

- **macOS**: `Cmd + Shift + 4` voor selectie, `Cmd + Shift + 3` voor volledig scherm
- **Windows**: `Win + Shift + S` voor Snipping Tool
- **Linux**: Gnome Screenshot of `scrot`
- **Browser Extensions**: Fireshot, Awesome Screenshot voor full-page captures

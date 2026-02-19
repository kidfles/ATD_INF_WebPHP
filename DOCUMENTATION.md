# 📚 ATD Webshop - Technische Documentatie

> **Best Practices & Implementatie Details**  
> Deze documentatie showcaset de technische implementatie van de ATD Webshop met focus op Laravel best practices, foutafhandeling en robuuste architectuur.

---

## 📋 Inhoudsopgave

1. [Routes, Controllers & Middleware](#1-routes-controllers--middleware)
2. [Migrations & Database Ontwerp](#2-migrations--database-ontwerp)
3. [Eloquent ORM](#3-eloquent-orm)
4. [Forms, Resources & Views](#4-forms-resources--views)
5. [Unieke Waarde Propositie](#5-unieke-waarde-propositie)

---

## 1. Routes, Controllers & Middleware

### 🎯 Routing Structuur

De applicatie hanteert een **zonegebaseerde routing strategie** voor optimale organisatie en beveiliging:

#### **Code Voorbeeld: Route Zones** (`routes/web.php`)

```php
// ZONE A: Openbare Marktplaats (Geen authenticatie vereist)
Route::get('/', function () {
    $featuredAds = \App\Models\Advertisement::latest()->take(6)->get();
    return view('pages.home', compact('featuredAds'));
})->name('home');

Route::get('/market', [MarketController::class, 'index'])->name('market.index');
Route::get('/company/{company:custom_url_slug}', [CompanyController::class, 'show'])->name('company.show');

// ZONE B: Dashboard (Auth + Verified)
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', DashboardController::class)->name('index');
    Route::resource('advertisements', AdvertisementController::class);
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    
    // API Token generatie (extra middleware: contract.approved)
    Route::post('/company/api-token', [CompanySettingsController::class, 'generateToken'])
        ->name('company.api_token')
        ->middleware('contract.approved');
});

// ZONE C: Transactie-acties (Alleen Auth)
Route::middleware('auth')->group(function () {
    Route::post('/advertisements/{advertisement}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::post('/advertisements/{advertisement}/rent', [RentalController::class, 'store'])->name('rentals.store');
    Route::post('/advertisements/{advertisement}/buy', [OrderController::class, 'store'])->name('orders.store');
});
```

#### **✅ Waarom is dit een Best Practice?**

1. **Separation of Concerns**: Publieke, beveiligde en transactie-routes zijn duidelijk gescheiden.
2. **Middleware Chains**: Gebruik van `['auth', 'verified']` garandeert dat alleen geverifieerde gebruikers toegang hebben tot het dashboard.
3. **Route Model Binding**: `{company:custom_url_slug}` gebruikt automatisch de custom slug als identifier (whitelabel URLs).
4. **Prefixing & Naming**: Dashboard routes hebben een `dashboard.` prefix voor consistente naamgeving.

---

### 🎮 Controller Implementatie

#### **Code Voorbeeld: Resource Controller met Autorisatie** (`AdvertisementController.php`)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Http\Requests\StoreAdvertisementRequest;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Toon advertenties van de ingelogde gebruiker met filters
     */
    public function index(Request $request)
    {
        $advertisements = $request->user()->advertisements()
            ->filter($request->only(['search', 'type', 'sort']))
            ->paginate(12)
            ->withQueryString();

        return view('pages.dashboard.advertisements.index', compact('advertisements'));
    }

    /**
     * Sla nieuwe advertentie op met bestandsupload
     */
    public function store(StoreAdvertisementRequest $request)
    {
        // Extra veiligheidscheck: kopers mogen geen advertenties plaatsen
        if ($request->user()->role === 'user') {
            abort(403, 'Als koper/huurder kun je geen advertenties plaatsen.');
        }

        $data = $request->validated();
        
        // Afbeelding upload via Laravel Storage
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        // Gebruik relatie voor automatische user_id toewijzing
        $advertisement = $request->user()->advertisements()->create($data);

        // Synchroniseer gerelateerde advertenties (upsells)
        if ($request->has('related_ads')) {
            $advertisement->relatedAds()->sync($request->input('related_ads'));
        }

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', __('Advertisement successfully created!'));
    }

    /**
     * Verwijder advertentie (met eigendomscheck)
     */
    public function destroy(Advertisement $advertisement)
    {
        // Ownership validation
        if ($advertisement->user_id !== auth()->id()) {
            abort(403);
        }

        $advertisement->delete();

        return redirect()->route('dashboard.advertisements.index')
            ->with('success', __('Advertisement deleted!'));
    }
}
```

#### **✅ Waarom is dit een Best Practice?**

1. **Form Request Validation**: Alle validatie gebeurt in `StoreAdvertisementRequest` (zie sectie 4).
2. **Eloquent Relationships**: `$request->user()->advertisements()->create($data)` voorkomt handmatige `user_id` toewijzing.
3. **Authorization Layer**: Dubbele check (FormRequest + Controller) voorkomt ongeautoriseerde acties.
4. **Flash Messages**: Success/error berichten via sessie voor gebruikersfeedback.
5. **Resource Controllers**: Gebruik van RESTful conventies (`store`, `update`, `destroy`).

---

### 🛡️ Custom Middleware

#### **Code Voorbeeld: Contract Goedkeuring Middleware** (`EnsureContractApproved.php`)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContractApproved
{
    /**
     * Controleer of het contract van een zakelijk account is goedgekeurd
     * Vereist voor API toegang en token generatie
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check authenticatie
        if (!$user) {
            abort(403, 'Authenticatie vereist.');
        }

        // 1. Alleen zakelijke accounts
        if ($user->role !== 'business_ad' || !$user->companyProfile) {
            abort(403, 'Alleen voor zakelijke accounts.');
        }

        // 2. Controleer contract status
        if ($user->companyProfile->contract_status !== 'approved') {
            
            // JSON response voor API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Contract nog niet goedgekeurd.'
                ], 403);
            }

            // Redirect met foutmelding voor web requests
            return redirect()->route('dashboard.company.settings.edit')
                ->with('error', 'Deze functie is pas beschikbaar nadat uw contract is goedgekeurd.');
        }

        return $next($request);
    }
}
```

#### **✅ Waarom is dit een Best Practice?**

1. **Single Responsibility**: Middleware focust op één specifieke check (contract status).
2. **Content Negotiation**: Handelt zowel JSON (API) als HTML (web) requests af.
3. **Descriptive Errors**: Duidelijke foutmeldingen voor gebruikers én developers.
4. **Early Returns**: Guards aan het begin van de functie voor leesbaarheid.

---

### 🚨 Foutafhandeling in Controllers

#### **Voorbeeld: Try-Catch Pattern voor Database Operaties**

```php
public function store(StoreRentalRequest $request, Advertisement $advertisement)
{
    try {
        // Controleer beschikbaarheid (voorkomt dubbele boekingen)
        $overlappingRental = Rental::where('advertisement_id', $advertisement->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })
            ->exists();

        if ($overlappingRental) {
            return back()->withErrors(['dates' => 'Deze data zijn al geboekt.']);
        }

        // Bereken totale prijs + slijtagekosten
        $totalPrice = $this->calculateRentalPrice($advertisement, $request->start_date, $request->end_date);

        Rental::create([
            'advertisement_id' => $advertisement->id,
            'renter_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('dashboard.rentals.index')
            ->with('success', 'Verhuur succesvol geboekt!');

    } catch (\Exception $e) {
        \Log::error('Rental booking failed: ' . $e->getMessage());
        
        return back()->withErrors([
            'error' => 'Er is een fout opgetreden. Probeer het later opnieuw.'
        ])->withInput();
    }
}
```

#### **✅ Best Practices voor Error Handling:**

- **Specifieke Validatie Errors**: Gebruik `->withErrors(['field' => 'message'])` voor veldspecifieke feedback.
- **Logging**: Gebruik `\Log::error()` voor debugging zonder gebruikers te alarmeren.
- **Graceful Degradation**: Toon generieke error berichten aan gebruikers, gedetailleerde logs voor developers.
- **Input Preservation**: `->withInput()` behoudt formulierdata na een fout.

---

## 2. Migrations & Database Ontwerp

### 🗄️ Database Schema Rationale

De database is ontworpen met focus op **data integriteit**, **polymorfisme** en **schaalbaarheid**.

#### **Code Voorbeeld: Advertisements Tabel** (`create_advertisements_table.php`)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            
            // Foreign key met cascade delete (als gebruiker verwijderd, verwijder advertenties)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Enum voor type safety (voorkomt ongeldige waarden)
            $table->enum('type', ['sell', 'rent', 'auction']);
            
            $table->string('title');
            $table->text('description');
            
            // Decimal voor financiële nauwkeurigheid (10 cijfers, 2 decimalen)
            $table->decimal('price', 10, 2);
            
            // Nullable voor veiling-eindtijd
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
```

#### **✅ Waarom is dit het Juiste Ontwerp?**

1. **Foreign Key Constraints**: `->constrained()->cascadeOnDelete()` garandeert referentiële integriteit.
2. **Enum Types**: Voorkomt data corruption (alleen `sell`, `rent`, `auction` zijn toegestaan).
3. **Decimal voor Geld**: `decimal(10, 2)` voorkomt floating-point fouten bij financiële berekeningen.
4. **Timestamps**: Automatische `created_at` en `updated_at` voor audit trails.

---

#### **Code Voorbeeld: Company Profiles met Whitelabel** (`create_company_profiles_table.php`)

```php
Schema::create('company_profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    
    // KVK validatie (exact 8 cijfers)
    $table->string('kvk_number', 8);
    
    // Hex color code voor branding
    $table->string('brand_color', 7); // #FFFFFF format
    
    // Unique slug voor whitelabel URLs (company.com/bedrijfsnaam)
    $table->string('custom_url_slug')->unique();
    
    // Contract workflow status
    $table->enum('contract_status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->string('contract_file_path')->nullable();
    
    $table->timestamps();
});
```

#### **✅ Design Beslissingen:**

1. **Unique Slug**: Voorkomt URL conflicten tussen bedrijven.
2. **Contract Workflow**: Enum maakt state machine logic mogelijk (pending → approved → rejected).
3. **Separation**: Company data apart van User tabel (normalisatie + performance).

---

#### **Code Voorbeeld: Polymorfische Reviews** (`create_reviews_table.php`)

```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    
    // Wie plaatst de review?
    $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
    
    // Polymorfisme: Review kan op User OF Advertisement zijn
    $table->morphs('reviewable'); // Maakt reviewable_id + reviewable_type kolommen
    
    $table->tinyInteger('rating')->unsigned(); // 1-5 sterren
    $table->text('comment')->nullable();
    
    $table->timestamps();
});
```

#### **✅ Waarom Polymorfisme?**

1. **DRY Principe**: Één tabel voor reviews op Users EN Advertisements (geen duplicate tables).
2. **Flexibiliteit**: Makkelijk uitbreidbaar naar nieuwe reviewable types (bv. Company Profiles).
3. **Laravel Magic**: `morphs()` genereert automatisch de juiste kolommen voor polymorfisme.

---

#### **Database Relatie Diagram**

```
users (1) ──< (N) advertisements
              │
              ├──< (N) rentals ──> (1) users (renters)
              ├──< (N) bids ──> (1) users (bidders)
              ├──< (N) orders ──> (1) users (buyers)
              └──< (N) reviews (polymorfisch)

users (1) ──< (1) company_profiles ──< (N) page_components

advertisements (N) ──< (N) favorites >── (N) users

advertisements (N) ──< ad_relations >── (N) advertisements (zelf-referentie)
```

---

## 3. Eloquent ORM

### 🔗 Model Relationships

#### **Code Voorbeeld: Advertisement Model** (`Advertisement.php`)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Advertisement extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'type', 'image_path', 'is_sold', 'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_sold' => 'boolean',
            'price' => 'decimal:2', // Automatische cast naar decimaal
        ];
    }

    /**
     * Eigenaar van de advertentie
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verhuurgeschiedenis
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Polymorfische reviews (reviewable_type = "App\Models\Advertisement")
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Zelf-refererende relatie voor upsells/cross-sells
     */
    public function relatedAds(): BelongsToMany
    {
        return $this->belongsToMany(
            Advertisement::class,
            'ad_relations',      // Pivot tabel
            'parent_ad_id',      // Foreign key voor "deze" advertentie
            'child_ad_id'        // Foreign key voor gerelateerde advertentie
        );
    }

    /**
     * Bedrijfsregel: Kan deze gebruiker een review achterlaten?
     * Alleen toegestaan als gebruiker item heeft gehuurd of gekocht
     */
    public function canBeReviewedBy(User $user): bool
    {
        $hasRented = $this->rentals()->where('renter_id', $user->id)->exists();
        $hasBought = $user->orders()->where('advertisement_id', $this->id)->exists();

        return $hasRented || $hasBought;
    }
}
```

#### **✅ Waarom is dit een Best Practice?**

1. **Type Hinting**: Return types (`BelongsTo`, `HasMany`) maken code zelf-documenterend.
2. **Casts**: Automatische type conversie (string → DateTime, int → boolean).
3. **Business Logic in Model**: `canBeReviewedBy()` centraliseert bedrijfsregels (geen duplicate checks in controllers).
4. **Self-Referencing Relations**: Elegante oplossing voor gerelateerde producten zonder extra modellen.

---

### 🔍 Query Scopes

#### **Code Voorbeeld: Filter Scope** (`Advertisement.php`)

```php
/**
 * Scope voor geavanceerde filtering van advertenties
 * Gebruikt in controllers via: Advertisement::filter($request->only(['search', 'type', 'sort']))
 */
public function scopeFilter(Builder $query, array $filters): void
{
    // Zoeken in titel en beschrijving
    $query->when($filters['search'] ?? false, fn($q, $search) => 
        $q->where(fn($sub) => 
            $sub->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
        )
    );

    // Filteren op type
    $query->when($filters['type'] ?? false, fn($q, $type) => 
        $q->where('type', $type)
    );

    // Filteren op verkoper
    $query->when($filters['seller'] ?? false, fn($q, $sellerId) => 
        $q->where('user_id', $sellerId)
    );

    // Dynamische sortering
    $query->when($filters['sort'] ?? false, function($q, $sort) {
        match ($sort) {
            'price_asc' => $q->orderBy('price', 'asc'),
            'price_desc' => $q->orderBy('price', 'desc'),
            'newest' => $q->orderBy('created_at', 'desc'),
            'oldest' => $q->orderBy('created_at', 'asc'),
            default => $q->orderBy('created_at', 'desc'),
        };
    });
}
```

#### **✅ Best Practices:**

1. **Scopes Chaining**: Scopes kunnen gecombineerd worden: `Advertisement::filter()->paginate()`.
2. **Conditional Queries**: `when()` voorkomt `if` statements in controllers.
3. **Match Expressions**: PHP 8 `match` is type-safe (strict comparison) en returnt waarden.
4. **SQL Injection Prevention**: `like "%$search%"` gebruikt parameter binding (veilig).

---

### 🚨 Foutafhandeling met Eloquent

#### **Voorbeeld: Model Not Found Exception**

```php
// Controller method
public function show($id)
{
    try {
        // findOrFail gooit ModelNotFoundException als niet gevonden
        $advertisement = Advertisement::with(['user', 'reviews'])->findOrFail($id);
        
        return view('advertisements.show', compact('advertisement'));
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        
        return redirect()->route('market.index')
            ->with('error', 'Advertentie niet gevonden.');
    }
}
```

#### **Voorbeeld: Mass Assignment Protection**

```php
// In Model: Alleen deze velden zijn fillable
protected $fillable = ['title', 'description', 'price'];

// VEILIG: Alleen fillable velden worden toegewezen
Advertisement::create($request->all());

// ONVEILIG zonder $fillable: Zou user_id kunnen overschrijven
// Mass Assignment vulnerability voorkomen door $fillable
```

---

## 4. Forms, Resources & Views

### 📝 Form Request Validation

#### **Code Voorbeeld: Store Advertisement Request** (`StoreAdvertisementRequest.php`)

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdvertisementRequest extends FormRequest
{
    /**
     * Autorisatie check (uitgevoerd voor validation)
     */
    public function authorize(): bool
    {
        if ($this->isMethod('post')) {
            return true; // Iedereen mag proberen te creëren (limit check in rules)
        }

        // Bij updates: check ownership
        $advertisement = $this->route('advertisement');
        return $advertisement && $advertisement->user_id === $this->user()->id;
    }

    /**
     * Validatie regels
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'price' => ['required', 'numeric', 'min:0'],
            
            // Custom validation: max 4 ads per type
            'type' => [
                'required', 
                'in:sell,rent,auction',
                function ($attribute, $value, $fail) {
                    $user = $this->user();
                    $query = $user->advertisements()->where('type', $value);

                    // Bij update: sluit huidige advertentie uit
                    if ($this->route('advertisement')) {
                         $query->where('id', '!=', $this->route('advertisement')->id);
                    }

                    if ($query->count() >= 4) {
                        $fail("Je mag maximaal 4 {$value} advertenties hebben.");
                    }
                }
            ],
            
            // Afbeelding validatie (dimensies + grootte)
            'image' => [
                'nullable', 
                'image', 
                'max:10240',                          // 10MB
                'dimensions:min_width=100,min_height=100'
            ],
            
            // Gerelateerde advertenties (array validation)
            'related_ads' => ['nullable', 'array'],
            'related_ads.*' => ['exists:advertisements,id'],
            
            // Conditionele validatie: expires_at verplicht bij auction
            'expires_at' => ['nullable', 'date', 'after:today', 'required_if:type,auction'],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Een titel is verplicht.',
            'description.min' => 'Beschrijving moet minimaal 20 karakters bevatten.',
            'price.min' => 'Prijs moet positief zijn.',
            'expires_at.after' => 'Einddatum moet in de toekomst liggen.',
        ];
    }
}
```

#### **✅ Waarom is dit een Best Practice?**

1. **Separation of Concerns**: Validatie logica buiten controllers.
2. **Reusability**: Zelfde FormRequest voor zowel store als update (met conditional logic).
3. **Custom Rules**: Complexe bedrijfsregels (max 4 ads) als closure validations.
4. **Type Safety**: Array validation (`related_ads.*`) voorkomt SQL injection.
5. **Authorization**: `authorize()` combineert permissions met validation.

---

### 🖼️ Blade Components

#### **Code Voorbeeld: Advertisement Create Form** (`create.blade.php`)

```blade
<x-app-layout>
    <div class="py-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-extrabold text-slate-800 mb-6">
                {{ __('Nieuwe Advertentie') }}
            </h2>

            <div class="bg-white rounded-[2rem] shadow-soft border border-slate-100 p-6 sm:p-8">

                {{-- Validation Errors Display --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl mb-6">
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Alpine.js voor dynamische UI (toon expires_at alleen bij auction) --}}
                <form action="{{ route('dashboard.advertisements.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      class="space-y-5" 
                      x-data="{ type: '{{ old('type', 'sell') }}' }">
                    @csrf
                    
                    {{-- Titel veld --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            {{ __('Titel') }}
                        </label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}" 
                               class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 text-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100/50">
                    </div>

                    {{-- Type selectie met Alpine.js binding --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            {{ __('Type') }}
                        </label>
                        <select name="type" 
                                x-model="type" 
                                class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3">
                            <option value="sell">{{ __('Verkoop') }}</option>
                            <option value="rent">{{ __('Verhuur') }}</option>
                            <option value="auction">{{ __('Veiling') }}</option>
                        </select>
                    </div>

                    {{-- Conditioneel veld (alleen zichtbaar bij auction) --}}
                    <div x-show="type === 'auction'" style="display: none;">
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            {{ __('Einddatum (Verplicht voor Veiling)') }}
                        </label>
                        <input type="date" 
                               name="expires_at" 
                               value="{{ old('expires_at') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3">
                    </div>

                    {{-- Multiple select voor gerelateerde advertenties --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            {{ __('Gerelateerde Producten (Koppelverkoop)') }}
                        </label>
                        <select name="related_ads[]" 
                                multiple 
                                class="w-full bg-slate-50 border-transparent rounded-2xl px-4 py-3 h-32">
                            @foreach($myAdvertisements as $option)
                                <option value="{{ $option->id }}" 
                                    @if(is_array(old('related_ads')) && in_array($option->id, old('related_ads'))) 
                                        selected 
                                    @endif>
                                    {{ ucfirst($option->type) }}: {{ $option->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit knop --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('dashboard.advertisements.index') }}" 
                           class="px-6 py-3 bg-slate-100 text-slate-600 rounded-full">
                            {{ __('Annuleren') }}
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-emerald-500 text-white rounded-full hover:bg-emerald-600">
                            {{ __('Opslaan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
```

#### **✅ Best Practices:**

1. **Old Input Preservation**: `old('title')` behoudt formulierdata na validatie fouten.
2. **Alpine.js Integration**: Lichtgewicht reactivity zonder volledige JS framework.
3. **Tailwind CSS**: Utility-first approach voor consistente styling.
4. **Localization**: `{{ __('Titel') }}` maakt multi-language support mogelijk.
5. **CSRF Protection**: `@csrf` token voorkomt cross-site request forgery.

---

### 🎨 Blade Components voor Hergebruik

#### **Voorbeeld: Herbruikbare Modal Component**

```blade
{{-- components/modal.blade.php --}}
@props(['name', 'show' => false])

<div x-data="{ show: @js($show) }" 
     x-show="show" 
     x-on:open-modal.window="$event.detail === '{{ $name }}' && (show = true)"
     x-on:close-modal.window="show = false"
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="display: none;">
    
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" x-on:click="show = false"></div>
    
    {{-- Modal Content --}}
    <div class="relative bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4 p-6">
        {{ $slot }}
    </div>
</div>
```

**Gebruik:**
```blade
<x-modal name="delete-confirmation">
    <h3 class="text-lg font-bold">Weet je het zeker?</h3>
    <p class="text-sm text-slate-600 mt-2">Deze actie kan niet ongedaan worden gemaakt.</p>
    <div class="flex justify-end gap-3 mt-4">
        <button x-on:click="$dispatch('close-modal')">Annuleren</button>
        <form method="POST" action="{{ route('advertisements.destroy', $ad) }}">
            @csrf @method('DELETE')
            <button type="submit">Verwijderen</button>
        </form>
    </div>
</x-modal>
```

---

### 🚨 Foutafhandeling in Views

#### **Voorbeeld: Inline Validatie Errors**

```blade
<div>
    <label for="title">Titel</label>
    <input type="text" 
           name="title" 
           id="title"
           value="{{ old('title') }}"
           class="@error('title') border-red-500 @enderror">
    
    {{-- Toon foutmelding alleen voor dit veld --}}
    @error('title')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
```

#### **Voorbeeld: Success/Error Flash Messages**

```blade
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
        {{ session('error') }}
    </div>
@endif
```

---

## 5. Unieke Waarde Propositie

### 🌟 Wat Maakt ATD Webshop Uniek?

#### **1. Whitelabel Bedrijfspagina's**

**Functionaliteit:**
- Zakelijke gebruikers kunnen hun eigen "minishop" binnen het platform creëren
- Custom URL slugs (bijv. `/company/techhub-nl`)
- Branding: Eigen logo, merkkleur (hex color picker)
- Drag-and-drop page builder met componenten (hero, tekstblokken, featured ads)

**Technische Implementatie:**
```php
// Route binding op custom slug
Route::get('/company/{company:custom_url_slug}', [CompanyController::class, 'show']);

// Dynamic branding in Blade
<div style="background-color: {{ $company->brand_color }}">
    <img src="{{ Storage::url($company->logo_path) }}" alt="Logo">
</div>

// Page components (sorteerbaar)
@foreach($company->pageComponents()->orderBy('order')->get() as $component)
    @include("components.page-builder.{$component->component_type}", ['data' => $component->content])
@endforeach
```

**Waarde:** Bedrijven kunnen hun eigen merkidentiteit behouden terwijl ze profiteren van het platform.

---

#### **2. Intelligente Verhuurmodule**

**Unieke Features:**
- **Beschikbaarheidscontrole**: Voorkomt dubbele boekingen via database queries
- **Dynamische Prijsberekening**: `dagprijs × aantal dagen + slijtagekosten`
- **Slijtagebeleid**: Per bedrijf instelbaar (vast bedrag OF percentage)
- **Foto-gebaseerde Retours**: Huurder upload foto's bij inleveren, verkoper keurt goed

**Code Snippet: Overlap Preventie**
```php
// Check voor overlappende verhuurperiodes
$overlappingRental = Rental::where('advertisement_id', $advertisement->id)
    ->where(function ($query) use ($startDate, $endDate) {
        $query->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q) use ($startDate, $endDate) {
                  $q->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $endDate);
              });
    })
    ->exists();

if ($overlappingRental) {
    throw new \Exception('Deze data zijn al geboekt.');
}
```

**Waarde:** Volledige verhuurworkflow zonder handmatige administratie.

---

#### **3. Contract Management met PDF Generatie**

**Workflow:**
1. Zakelijke gebruiker download contract template (PDF via Blade)
2. Upload getekend contract
3. Admin keurt contract goed/af
4. Bij goedkeuring: Toegang tot API token generatie & CSV import

**Code Snippet: PDF Generatie**
```php
use Barryvdh\DomPDF\Facade\Pdf;

public function downloadContract()
{
    $user = auth()->user();
    $company = $user->companyProfile;

    $pdf = Pdf::loadView('pdf.contract-template', [
        'company' => $company,
        'user' => $user,
        'generatedAt' => now(),
    ]);

    return $pdf->download('contract-' . $company->kvk_number . '.pdf');
}
```

**Waarde:** Geautomatiseerde onboarding voor zakelijke klanten met compliance check.

---

#### **4. Polymorfische Reviews**

**Innovatie:**
- Gebruikers kunnen ZOWEL verkopers ALS advertenties reviewen
- Verificatie: Alleen kopers/huurders mogen reviewen
- Single table design (schaalbaarheid)

**Code Snippet: Review Authorization**
```php
// In Advertisement Model
public function canBeReviewedBy(User $user): bool
{
    $hasRented = $this->rentals()->where('renter_id', $user->id)->exists();
    $hasBought = $user->orders()->where('advertisement_id', $this->id)->exists();
    
    return $hasRented || $hasBought;
}

// In Controller
if (!$advertisement->canBeReviewedBy(auth()->user())) {
    abort(403, 'Je moet dit product eerst huren of kopen om te reviewen.');
}
```

---

#### **5. CSV Bulk Import voor Zakelijke Adverteerders**

**Functionaliteit:**
- Upload CSV bestand met advertenties
- Automatische validatie en creatie van meerdere advertenties tegelijk
- Foutrapportage per rij

**Waarde:** Bedrijven met grote catalogi kunnen snel hun inventaris uploaden.

---

#### **6. Agenda Dashboard**

**Functionaliteit:**
- Visuele kalender met:
  - Ingeplande verhuurperiodes (groen)
  - Einddatums van veilingen (rood)
- JSON API endpoint voor calendar libraries (FullCalendar.js)

**Code Snippet:**
```php
public function events()
{
    $user = auth()->user();
    
    $rentals = Rental::whereHas('advertisement', fn($q) => $q->where('user_id', $user->id))
        ->get()
        ->map(fn($rental) => [
            'title' => 'Verhuur: ' . $rental->advertisement->title,
            'start' => $rental->start_date,
            'end' => $rental->end_date,
            'color' => '#10b981',
        ]);

    $auctions = $user->advertisements()
        ->where('type', 'auction')
        ->whereNotNull('expires_at')
        ->get()
        ->map(fn($ad) => [
            'title' => 'Veiling eindigt: ' . $ad->title,
            'start' => $ad->expires_at,
            'color' => '#ef4444',
        ]);

    return response()->json($rentals->merge($auctions));
}
```

**Waarde:** Overzicht van alle deadlines en verhuurtransacties in één dashboard.

---

### 📸 Screenshots (Demo)

> **Opmerking:** Voeg hier 1-2 screenshots toe van:
> 1. Dashboard met whitelabel bedrijfspagina editor
> 2. Verhuurkalender met overlapping prevention
> 3. Contract workflow (PDF download → upload → goedkeuring)

**Plaats screenshots in `/public/screenshots/` en refereer hier:**

```markdown
![Whitelabel Builder](/screenshots/whitelabel-builder.png)
*Drag-and-drop page builder voor zakelijke accounts met realtime preview*

![Rental Calendar](/screenshots/rental-calendar.png)
*Agenda dashboard met verhuurperiodes en veiling deadlines*
```

---

## 🎯 Conclusie

De ATD Webshop onderscheidt zich door:

1. **Technische Excellentie**: Laravel best practices in elke laag (routing, validatie, ORM)
2. **Robuuste Foutafhandeling**: Try-catch blocks, FormRequests, ModelNotFoundException handling
3. **Schaalbaarheid**: Polymorfisme, query scopes, eager loading
4. **Gebruikerservaring**: Alpine.js interactivity, flash messages, old input preservation
5. **Business Logic**: Whitelabel, verhuur workflow, contract management, polymorfische reviews

**Unieke Waarde:**
- All-in-one platform voor verkoop, verhuur EN veilingen
- Whitelabel mogelijkheden voor zakelijke klanten
- Geautomatiseerde workflows (contract approval, PDF generatie)
- Geavanceerde verhuurlogica met overlap preventie

---

## 📚 Referenties

- [Laravel 11 Documentatie](https://laravel.com/docs/11.x)
- [Eloquent ORM Best Practices](https://laravel.com/docs/11.x/eloquent)
- [Form Request Validation](https://laravel.com/docs/11.x/validation#form-request-validation)
- [Blade Components](https://laravel.com/docs/11.x/blade#components)
- [Route Model Binding](https://laravel.com/docs/11.x/routing#route-model-binding)

---

*Documentatie gegenereerd op {{ date('Y-m-d') }}*

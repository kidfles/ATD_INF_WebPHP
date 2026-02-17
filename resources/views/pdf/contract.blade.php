<!DOCTYPE html>
{{--
    Layout: PDF Contract
    Doel: Genereert het samenwerkingscontract voor zakelijke partners (whitelabel).
    Bevat: Bedrijfsgegevens, voorwaarden en handtekeningvelden.
--}}
<html>
<head>
    <title>Contract - {{ $company->company_name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 40px; }
        .section { margin-bottom: 20px; }
        .signature-box { margin-top: 50px; border: 1px solid #000; height: 100px; width: 40%; display: inline-block; }
        .row { width: 100%; clear: both; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Samenwerkingsovereenkomst</h1>
        <p>Betreft: Zakelijk Adverteren & Verhuur</p>
    </div>

    <div class="section">
        <h3>1. De Partijen</h3>
        <p><strong>Platform Eigenaar:</strong><br>
        Naam: {{ config('app.name') }}<br>
        Adres: Startupstraat 1, 1234 AB, Amsterdam</p>

        <p><strong>De Partner (Adverteerder):</strong><br>
        <span class="label">Bedrijfsnaam:</span> {{ $company->company_name }}<br>
        <span class="label">KVK Nummer:</span> {{ $company->kvk_number }}<br>
        <span class="label">Contactpersoon:</span> {{ $user->name }}<br>
        <span class="label">Email:</span> {{ $user->email }}</p>
    </div>

    <div class="section">
        <h3>2. Overeenkomst</h3>
        <p>Hierbij komen partijen overeen dat de Partner gebruik mag maken van de zakelijke faciliteiten van het platform, waaronder:</p>
        <ul>
            <li>Het plaatsen van zakelijke advertenties en verhuur aanbiedingen.</li>
            <li>Gebruik van de whitelabel bedrijfspagina.</li>
            <li>Toegang tot de API voor geautomatiseerd beheer.</li>
        </ul>
    </div>

    <div class="section">
        <h3>3. Ondertekening</h3>
        <p>Aldus overeengekomen en getekend te {{ date('d-m-Y') }}:</p>
        
        <div class="row">
            <div style="float:left; width: 45%;">
                <p><strong>Namens Platform:</strong></p>
                <div class="signature-box"></div>
            </div>
            <div style="float:right; width: 45%;">
                <p><strong>Namens {{ $company->company_name }}:</strong></p>
                <div class="signature-box"></div>
            </div>
        </div>
    </div>
</body>
</html>

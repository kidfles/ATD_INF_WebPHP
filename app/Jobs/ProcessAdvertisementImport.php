<?php declare(strict_types=1);

namespace App\Jobs;

use App\Models\Advertisement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

/**
 * ProcessAdvertisementImport
 *
 * Verwerkt een CSV-bestand met advertenties op de achtergrond.
 * Elke rij wordt gevalideerd tegen de "max 4 per type" bedrijfsregel
 * voordat een advertentie wordt aangemaakt.
 */
class ProcessAdvertisementImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Het maximale aantal pogingen voor deze job.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * Maak een nieuwe job-instantie aan.
     *
     * @param int    $userId   Het ID van de gebruiker die de import heeft gestart.
     * @param string $filePath Het pad naar het geüploade CSV-bestand in storage.
     */
    public function __construct(
        protected int $userId,
        protected string $filePath
    ) {}

    /**
     * Voer de import-job uit.
     *
     * Leest het CSV-bestand rij voor rij om geheugengebruik laag te houden.
     * Valideert elke rij tegen de bedrijfsregel (max 4 advertenties per type)
     * en maakt alleen geldige advertenties aan.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info('[CSV Import] Job gestart', [
            'user_id' => $this->userId,
            'file_path' => $this->filePath,
        ]);

        // CSV-bestand openen vanuit de private storage
        $fullPath = Storage::disk('local')->path($this->filePath);

        Log::info('[CSV Import] Volledig pad', ['full_path' => $fullPath, 'exists' => file_exists($fullPath)]);

        if (!file_exists($fullPath)) {
            Log::error('[CSV Import] Bestand niet gevonden!', ['path' => $fullPath]);
            return;
        }

        try {
            $reader = Reader::createFromPath($fullPath, 'r');
            $reader->setHeaderOffset(0); // Eerste rij bevat kolomnamen

            $headers = $reader->getHeader();
            Log::info('[CSV Import] Headers gelezen', ['headers' => $headers]);

            $skipped = 0;
            $imported = 0;

            foreach ($reader->getRecords() as $index => $record) {
                Log::info("[CSV Import] Rij {$index} verwerken", ['data' => $record]);

                // Controleer of de vereiste velden aanwezig zijn
                if (empty($record['title']) || empty($record['price']) || empty($record['type'])) {
                    Log::warning("[CSV Import] Rij {$index} overgeslagen: verplichte velden ontbreken");
                    $skipped++;
                    continue;
                }

                // Valideer het type (alleen 'sell', 'rent', 'auction' toegestaan)
                $type = strtolower(trim($record['type']));
                if (!in_array($type, ['sell', 'rent', 'auction'])) {
                    Log::warning("[CSV Import] Rij {$index} overgeslagen: ongeldig type '{$type}'");
                    $skipped++;
                    continue;
                }

                // Bedrijfsregel: maximaal 4 advertenties per type per gebruiker
                $currentCount = Advertisement::where('user_id', $this->userId)
                    ->where('type', $type)
                    ->count();

                Log::info("[CSV Import] Type '{$type}' teller: {$currentCount}/4");

                if ($currentCount >= 4) {
                    Log::warning("[CSV Import] Rij {$index} overgeslagen: max 4 bereikt voor type '{$type}'");
                    $skipped++;
                    continue;
                }

                // Advertentie aanmaken
                try {
                    $ad = Advertisement::create([
                        'user_id'     => $this->userId,
                        'title'       => trim($record['title']),
                        'description' => trim($record['description'] ?? ''),
                        'price'       => (float) $record['price'],
                        'type'        => $type,
                    ]);
                    Log::info("[CSV Import] Advertentie aangemaakt!", ['ad_id' => $ad->id, 'title' => $ad->title]);
                    $imported++;
                } catch (\Exception $e) {
                    Log::error("[CSV Import] FOUT bij aanmaken rij {$index}", [
                        'error' => $e->getMessage(),
                        'record' => $record,
                    ]);
                    $skipped++;
                }
            }

            Log::info('[CSV Import] Klaar!', [
                'imported' => $imported,
                'skipped' => $skipped,
                'user_id' => $this->userId,
            ]);

        } catch (\Exception $e) {
            Log::error('[CSV Import] FATALE FOUT', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        // Opruimen: CSV-bestand verwijderen na verwerking
        Storage::disk('local')->delete($this->filePath);
        Log::info('[CSV Import] Bestand opgeruimd', ['path' => $this->filePath]);
    }
}

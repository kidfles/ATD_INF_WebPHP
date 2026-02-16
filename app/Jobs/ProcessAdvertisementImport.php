<?php

namespace App\Jobs;

use App\Models\Advertisement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        // CSV-bestand openen vanuit de private storage
        $fullPath = Storage::disk('local')->path($this->filePath);

        if (!file_exists($fullPath)) {
            return;
        }

        $reader = Reader::createFromPath($fullPath, 'r');
        $reader->setHeaderOffset(0); // Eerste rij bevat kolomnamen

        $skipped = 0;
        $imported = 0;

        foreach ($reader->getRecords() as $record) {
            // Controleer of de vereiste velden aanwezig zijn
            if (empty($record['title']) || empty($record['price']) || empty($record['type'])) {
                $skipped++;
                continue;
            }

            // Valideer het type (alleen 'sale', 'rent', 'auction' toegestaan)
            $type = strtolower(trim($record['type']));
            if (!in_array($type, ['sale', 'rent', 'auction'])) {
                $skipped++;
                continue;
            }

            // Bedrijfsregel: maximaal 4 advertenties per type per gebruiker
            $currentCount = Advertisement::where('user_id', $this->userId)
                ->where('type', $type)
                ->count();

            if ($currentCount >= 4) {
                $skipped++;
                continue;
            }

            // Advertentie aanmaken
            Advertisement::create([
                'user_id'     => $this->userId,
                'title'       => trim($record['title']),
                'description' => trim($record['description'] ?? ''),
                'price'       => (float) $record['price'],
                'type'        => $type,
            ]);

            $imported++;
        }

        // Opruimen: CSV-bestand verwijderen na verwerking
        Storage::disk('local')->delete($this->filePath);
    }
}

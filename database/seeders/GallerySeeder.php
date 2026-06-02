<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class GallerySeeder extends Seeder
{
    // Picsum photo IDs with warm/colourful/playful feel
    private array $picsumIds = [237, 326, 669, 432, 582, 200, 399, 577, 374, 103];

    private array $entries = [
        ['caption' => 'Someone let the finger paints loose during nap time',    'takenAt' => '2024-03-04'],
        ['caption' => 'Tuesday snack wars: graham crackers vs. goldfish',       'takenAt' => '2024-04-11'],
        ['caption' => 'Max discovered gravity using the entire bag of blocks',  'takenAt' => '2024-05-22'],
        ['caption' => 'Circle time got very off-script today',                  'takenAt' => '2024-06-07'],
        ['caption' => 'The great juice box incident of spring',                 'takenAt' => '2024-07-15'],
        ['caption' => 'Someone decided the playhouse needed a car wash',        'takenAt' => '2024-08-03'],
        ['caption' => 'Dress-up day: everyone chose to be a dinosaur',         'takenAt' => '2024-09-18'],
        ['caption' => 'Nature walk turned into a worm rescue mission',         'takenAt' => '2024-10-29'],
        ['caption' => 'Bubble station: things escalated quickly',               'takenAt' => '2024-11-12'],
        ['caption' => "Storytime fell apart at page two — nobody minded",       'takenAt' => '2025-01-08'],
    ];

    public function run(): void
    {
        $storageDir = storage_path('app/public/uploads/gallery');
        if (! is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        foreach ($this->entries as $i => $entry) {
            $picsumId = $this->picsumIds[$i];
            $url      = "https://picsum.photos/id/{$picsumId}/900/600";

            $this->command->info("Downloading image " . ($i + 1) . "/10 (Picsum #{$picsumId})…");

            try {
                $response = Http::timeout(20)->get($url);
                if (! $response->successful()) {
                    $this->command->warn("  Skipped — HTTP {$response->status()}");
                    continue;
                }
                $imageBytes = $response->body();
            } catch (\Exception $e) {
                $this->command->warn("  Skipped — " . $e->getMessage());
                continue;
            }

            $record = GalleryImage::create([
                'filename'  => '',
                'caption'   => $entry['caption'],
                'takenAt'   => $entry['takenAt'],
                'sortOrder' => $i + 1,
            ]);

            $filename = $record->id . '.jpg';
            file_put_contents($storageDir . '/' . $filename, $imageBytes);
            $record->update(['filename' => '/storage/uploads/gallery/' . $filename]);

            $this->command->line("  Saved as {$filename}");
        }

        $this->command->info('Gallery seeded with ' . count($this->entries) . ' images.');
    }
}


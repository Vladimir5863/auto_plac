<?php

namespace Database\Factories;

use App\Models\vozilo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<vozilo>
 */
class VoziloFactory extends Factory
{
    protected $model = vozilo::class;

    public function definition(): array
    {
        $img = $this->defaultImageDataUri();
        return [
            'marka' => fake()->randomElement(['Audi','BMW','Mercedes-Benz','Volkswagen','Opel','Ford','Toyota','Honda','Nissan','Renault']),
            'model' => fake()->bothify('Model-##'),
            'godinaProizvodnje' => fake()->numberBetween(1998, 2024),
            'cena' => fake()->randomFloat(2, 1000, 80000),
            // Match create form options exactly
            'tipGoriva' => fake()->randomElement(['Benzin','Dizel','Hibrid','Električno','Gas']),
            'kilometraza' => fake()->numberBetween(10_000, 250_000) . ' km',
            'tipKaroserije' => fake()->randomElement(['Limuzina','Hatchback','SUV','Karavan','Kupe','Kabriolet','Pickup']),
            'snagaMotoraKW' => fake()->randomFloat(1, 40, 300),
            'stanje' => fake()->randomElement(['Novo','Polovno']),
            'opis' => fake()->paragraph(),
            // Store image as base64 data URI directly in DB (JSON array)
            'slike' => [$img, $img],
            'lokacija' => fake()->city(),
            'klima' => fake()->randomElement(['Da','Ne']),
            'tipMenjaca' => fake()->randomElement(['Manuelni','Automatski','Poluautomatski']),
            'ostecenje' => fake()->boolean(20),
            'euroNorma' => fake()->randomElement(['Euro 1','Euro 2','Euro 3','Euro 4','Euro 5','Euro 6']),
            'kubikaza' => fake()->numberBetween(900, 5000),
        ];
    }

    /**
     * Get default image as data URI from public storage; fallback to project root Untitled.jpg
     */
    protected function defaultImageDataUri(): string
    {
        // Primary: storage/app/public/cars/default/Untitled.jpg
        $path = 'cars/default/Untitled.jpg';
        try {
            if (Storage::disk('public')->exists($path)) {
                $bytes = Storage::disk('public')->get($path);
                $base64 = base64_encode($bytes);
                return 'data:image/jpeg;base64,' . $base64;
            }
        } catch (\Throwable $e) {}

        // Secondary: project root Untitled.jpg
        $root = base_path('Untitled.jpg');
        if (is_file($root)) {
            $bytes = @file_get_contents($root) ?: '';
            if ($bytes !== '') {
                return 'data:image/jpeg;base64,' . base64_encode($bytes);
            }
        }

        // Transparent 1x1 PNG as last resort
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=';
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\vozilo;
use App\Models\oglas;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UsersAndOglasiSeeder extends Seeder
{
    public function run(): void
    {
        // Prepare a base64 data URI image once for all seeded vehicles
        $img = $this->defaultImageDataUri();

        // Create N users, each with M vehicles and related ads
        User::factory(100)->create()->each(function (User $user) use ($img) {
            $vehiclesCount = fake()->numberBetween(10, 20);

            for ($i = 0; $i < $vehiclesCount; $i++) {
                $vehicle = vozilo::factory()->create();
                // Overwrite images with predefined base64 data URI
                $vehicle->slike = [$img, $img];
                $vehicle->save();

                $ad = oglas::factory()->make();
                $ad->voziloID = $vehicle->voziloID;
                $ad->korisnikID = $user->id;
                $ad->save();
            }
        });
    }

    /**
     * Returns a base64 data URI from predefined image path.
     */
    protected function defaultImageDataUri(): string
    {
        // Primary: storage/app/public/cars/default/Untitled.jpg
        $path = 'cars/default/Untitled.jpg';
        try {
            if (Storage::disk('public')->exists($path)) {
                $bytes = Storage::disk('public')->get($path);
                return 'data:image/jpeg;base64,' . base64_encode($bytes);
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

        // Transparent 1x1 as last resort
        return 'data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=';
    }

}

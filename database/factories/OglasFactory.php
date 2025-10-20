<?php

namespace Database\Factories;

use App\Models\oglas;
use App\Models\vozilo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<oglas>
 */
class OglasFactory extends Factory
{
    protected $model = oglas::class;

    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-60 days', 'now');
        $expiresAt = (clone $createdAt)->modify('+30 days');

        return [
            'datumKreiranja' => $createdAt->format('Y-m-d'),
            'datumIsteka' => $expiresAt->format('Y-m-d'),
            'cenaIstaknutogOglasa' => fake()->optional(0.3)->randomFloat(2, 5, 50),
            'statusOglasa' => fake()->randomElement([
                'istaknutiOglas',
                'standardniOglas',
                'deaktiviranOglas',
                'istekaoOglas',
                'prodatOglas',
            ]),
            // We'll set foreign keys in the seeder to ensure referential integrity
            'voziloID' => null,
            'korisnikID' => null,
        ];
    }
}

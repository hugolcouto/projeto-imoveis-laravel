<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $amenitiesPool = [
            'ac', 'elevador', 'glass', 'piscina', 'varanda', 'jardim',
            'estacionamento', 'cercas', 'portaria', 'wifi', 'copa',
            'jacuzzi', 'salão de festas', 'luz solar', 'armário embutido',
            'copo', 'cozinha equilibrada', 'área gourmet', 'cena',
            'área de cultivo',
        ];

        $title = rtrim($this->faker->sentence(3));
        $title = strlen($title) > 40 ? substr($title, 0, 37) . '...' : $title;

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(8),
            'valueInCents' => $this->faker->numberBetween(150000, 7500000),
            'description' => $this->faker->paragraph(),
            'amenities' => $this->faker->randomElement([['ac', 'piscina'], ['elevador', 'glass', 'varanda'], ['jardim', 'estacionamento']]),
            'location' => $this->faker->city() . ', ' . $this->faker->stateAbbr() . ' - ' . $this->faker->streetName(),
            'transactionType' => $this->faker->randomElement(['buy', 'rental']),
        ];
    }
}

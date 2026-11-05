<?php

namespace Database\Factories;

use App\Models\MasterKategoriWaste;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterKategoriWaste> */
class MasterKategoriWasteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\MasterKategoriWaste>
     */
    protected $model = MasterKategoriWaste::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->unique()->word(),
            'deskripsi' => $this->faker->sentence(),
            'status_aktif' => true,
        ];
    }
}


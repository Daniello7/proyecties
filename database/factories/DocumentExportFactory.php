<?php

namespace Database\Factories;

use App\Models\DocumentExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Str;

class DocumentExportFactory extends Factory
{
    protected $model = DocumentExport::class;

    public function definition(): array
    {
        $user_id = User::factory()->create()->id;
        $filename = $this->faker->words(3, true);
        $type = $this->faker->randomElement(['pdf', 'xlsx']);
        $extension = $type === 'pdf' ? '.pdf' : '.xlsx';

        $filePath = sprintf(
            'excel/exports/%s/%s_%s_%s%s',
            now()->format('Y-m'),
            "user_$user_id",
            Str::slug(strtolower($filename)),
            Str::uuid(),
            $extension
        );

        return [
            'user_id' => $user_id,
            'filename' => $filename,
            'file_path' => $filePath,
            'type' => $type,
            'viewed_at' => $this->faker->dateTime(),
        ];
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'pdf',
            'file_path' => str_replace('.xlsx', '.pdf', $attributes['file_path'])
        ]);
    }

    public function xlsx(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'xlsx',
            'file_path' => str_replace('.pdf', '.xlsx', $attributes['file_path'])
        ]);
    }

    public function unviewed(): static
    {
        return $this->state([
            'viewed_at' => null
        ]);
    }

    public function viewed(): static
    {
        return $this->state([
            'viewed_at' => now()
        ]);
    }

}

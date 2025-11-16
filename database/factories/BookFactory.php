<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
     protected $model = \App\Models\Book::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Sample images (from public/images)
        $images = ['cover1.jpg', 'cover2.jpg', 'cover3.jpg', 'cover4.jpg', 'cover5.jpg'];
        $imageFile = $this->faker->randomElement($images);
        $filename = time() . '_' . $imageFile;

        // Copy the image to storage
        \Illuminate\Support\Facades\Storage::disk('public')->putFileAs(
            'images',
            new \Illuminate\Http\File(public_path('images/' . $imageFile)),
            $filename
        );
        return [
            //
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'tags' => $this->faker->randomElements(['fantasy','classic','adventure','romance','crime','drama','horror','sci-fi','children'], 3),
            'image' => $filename,
        ];
    }
}

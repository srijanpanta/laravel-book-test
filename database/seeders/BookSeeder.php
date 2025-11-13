<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('books')->insert([
        [
            'title' => 'The Hobbit',
            'author' => 'J.R.R. Tolkien',
            'tags' => 'fantasy,classic,adventure',
            'description' => 'A fantasy classic.',
            'image' => 'cover1.jpg', // Image saved in public/images/hobbit.jpg
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'title' => '1984',
            'author' => 'George Orwell',
            'tags' => 'dystopian,classic,literature',
            'description' => 'A powerful tale.',
            'image' => 'cover2.jpg', // Image saved in public/images/1984.jpg
            'created_at' => now(),
            'updated_at' => now(),
        ],
        ]);
    }
}

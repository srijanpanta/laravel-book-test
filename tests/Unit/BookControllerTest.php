<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->get("/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->where('book.id', $book->id)
                ->where('book.title', $book->title)
        );
    }

    public function test_store_creates_a_book()
    {
        Storage::fake('public');

        $controller = new BookController();

        $request = Request::create('/books', 'POST', [
            'title' => 'Unit Test Book',
            'author' => 'Author Name',
            'description' => 'A description',
            'tags' => json_encode(['tag1', 'tag2']),
        ]);

        $controller->store($request);

        $this->assertDatabaseHas('books', ['title' => 'Unit Test Book']);
    }

    public function test_update_modifies_a_book()
    {
        Storage::fake('public');

        $book = Book::factory()->create();

        $controller = new BookController();

        $request = Request::create("/books/{$book->id}", 'PUT', [
            'title' => 'Updated Title',
            'author' => $book->author,
        ]);

        $controller->update($request, $book);

        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title']);
    }

    public function test_delete_removes_a_book()
    {
        $book = Book::factory()->create();

        $controller = new BookController();

        $controller->destroy($book);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}

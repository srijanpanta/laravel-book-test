<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Book::query();

        $searchTerm = null;

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('author', 'like', "%{$searchTerm}%")
                ->orWhere('tags', 'like', "%{$searchTerm}%");
        }

        $books = $query->get();

        return Inertia::render('Books/Index', [
            'books' => $books,
            'filters' => [
                'search' => $searchTerm ?? '',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Books/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'tags' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);
        $book = Book::create($validated);
        // For API: return response()->json($book, 201);
        return redirect()->route('books.index')->with('success', 'Book created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
        return Inertia::render('Books/Show', [
            'book' => $book
        ]);
        // For API: return response()->json($book);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
        return Inertia::render('Books/Edit', [
            'book' => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'author' => 'sometimes|required|string',
            'tags' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);
        $book->update($validated);
        // For API: return response()->json($book);
        return redirect()->route('books.index')->with('success', 'Book updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
        $book->delete();
        // For API: return response()->json(null, 204);
        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }
}

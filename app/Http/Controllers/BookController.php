<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a paginated list of books.
     * Handles optional search filtering by title, author, or tags.
     */
    public function index(Request $request)
    {
        $query = Book::query();
        $searchTerm = $request->input('search', '');

        if ($searchTerm) {
            $query->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('author', 'like', "%{$searchTerm}%")
                  ->orWhereJsonContains('tags', $searchTerm);
        }

        $books = $query->orderBy('created_at', 'desc')
                       ->paginate(8)
                       ->withQueryString();

        return Inertia::render('Books/Index', [
            'books' => $books,
            'filters' => [
                'search' => $searchTerm,
            ],
        ]);
    }

    /**
     * Store a newly created book.
     * Handles file uploads and tags sent as JSON string from frontend.
     */
    public function store(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|string', // JSON string from Vue
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $book = new Book();
        $book->title = $validated['title'];
        $book->author = $validated['author'];
        $book->description = $validated['description'] ?? '';
        $book->tags = isset($validated['tags']) ? json_decode($validated['tags'], true) : [];

        // Handle uploaded image
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');

            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store the file in 'storage/app/public/images'
            $file->storeAs('images', $filename, 'public');

            // Save the filename to the DB
            $book->image = $filename;
        }

        $book->save();

        // Return to previous page
        return redirect()->back()->with('success', 'Book created successfully.');
}


    /**
     * Show a single book.
     * Only used if you want a separate "view page" for a book.
     */
    public function show(Book $book)
    {
        return Inertia::render('Books/Show', [
            'book' => $book
        ]);
    }

    /**
     * Show the form for editing a book.
     * Used when opening the modal with existing book data.
     */
    public function edit(Book $book)
    {
        return Inertia::render('Books/Edit', [
            'book' => $book
        ]);
    }

    /**
     * Update an existing book.
     * Handles new image uploads and updates tags.
     */
    public function update(Request $request, Book $book)
    {
        // dd($request->all(), $request->file('image'));

        // Validate form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|string', // JSON string from Vue
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Update fields
        $book->title = $validated['title'];
        $book->author = $validated['author'];
        $book->description = $validated['description'] ?? '';
        $book->tags = isset($validated['tags']) ? json_decode($validated['tags'], true) : $book->tags;

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');

            // Delete old image if exists
            if ($book->image && Storage::disk('public')->exists('images/' . $book->image)) {
                Storage::disk('public')->delete('images/' . $book->image);
            }

            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store the file in 'storage/app/public/images'
            $file->storeAs('images', $filename, 'public');

            // Save the filename to the DB
            $book->image = $filename;
        }

        $book->save();

        return redirect()->back()->with('success', 'Book updated successfully.');
}

    /**
     * Delete a book.
     * Also deletes the associated image file if it exists.
     */
    public function destroy(Book $book)
    {
        if ($book->image && Storage::exists('public/images/' . $book->image)) {
            Storage::delete('public/images/' . $book->image);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Book deleted successfully.');
    }
}

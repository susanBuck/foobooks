<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Book;
use App\Author;
use App\Tag;

class BookController extends Controller
{
    /*
     * GET /books
     */
    public function index()
    {
        # Get all the books from our library
        $books = Book::with('author')->orderBy('title')->get();

        # Query on the existing collection to get our recently added books
        $newBooks = $books->sortByDesc('created_at')->take(3);

        return view('books.index')->with([
            'books' => $books,
            'newBooks' => $newBooks,
        ]);
    }

    /*
     * GET /books/{id}
     */
    public function show($id)
    {
        $book = Book::with('author')->find($id);

        if (!$book) {
            return redirect('/books')->with(['alert' => 'Book not found']);
        }

        return view('books.show')->with([
            'book' => $book
        ]);
    }

    /*
     * GET /books/search
     */
    public function search(Request $request)
    {
        $searchTerm = $request->session()->get('searchTerm', '');
        $caseSensitive = $request->session()->get('caseSensitive', false);
        $searchResults = $request->session()->get('searchResults', null);

        return view('books.search')->with([
            'searchTerm' => $searchTerm,
            'caseSensitive' => $caseSensitive,
            'searchResults' => $searchResults,
        ]);
    }

    /**
     * GET /books/search-process
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function searchProcess(Request $request)
    {
        $request->validate([
            'searchTerm' => 'required'
        ]);

        $searchTerm = $request->searchTerm;
        $caseSensitive = $request->has('caseSensitive');

        if ($caseSensitive) {
            # Ref: https://stackoverflow.com/questions/25494849/case-sensitive-where-statement-in-laravel
            $searchResults = Book::whereRaw("BINARY `title`= ?", [$searchTerm])->get();
        } else {
            $searchResults = Book::where('title', $searchTerm)->get();
        }

        return redirect('/books/search')->with([
            'searchTerm' => $request->searchTerm,
            'caseSensitive' => $caseSensitive,
            'searchResults' => $searchResults
        ]);
    }

    /*
     * GET /books/create
     */
    public function create()
    {
        $authors = Author::getForDropdown();

        $tags = Tag::getForCheckboxes();

        return view('books.create')->with([
            'authors' => $authors,
            'tags' => $tags
        ]);
    }

    /*
     * POST /books
     */
    public function store(Request $request)
    {
        # Validate the request data
        $request->validate([
            'title' => 'required',
            'author_id' => 'required',
            'published_year' => 'required|digits:4',
            'cover_url' => 'required|url',
            'purchase_url' => 'required|url'
        ]);

        # Note: If validation fails, it will redirect the visitor back to the form page
        # and none of the code that follows will execute.

        $book = new Book();
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->published_year = $request->published_year;
        $book->cover_url = $request->cover_url;
        $book->purchase_url = $request->purchase_url;
        $book->save();

        # Note: Have to sync tags *after* the book has been saved so there's a book_id to store in the pivot table
        $book->tags()->sync($request->tags);

        return redirect('/books/create')->with(['alert' => 'The book ' . $book->title . ' was added.']);
    }

    /*
     * GET /books/{id}/edit
     */
    public function edit($id)
    {
        $book = Book::find($id);

        $authors = Author::getForDropdown();

        $tags = Tag::getForCheckboxes();

        $bookTags = $book->tags->pluck('id')->toArray();

        if (!$book) {
            return redirect('/books')->with(['alert' => 'Book not found.']);
        }

        return view('books.edit')->with([
            'book' => $book,
            'authors' => $authors,
            'tags' => $tags,
            'bookTags' => $bookTags,
        ]);
    }

    /*
     * PUT /books/{id}
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->published_year = $request->published_year;
        $book->cover_url = $request->cover_url;
        $book->purchase_url = $request->purchase_url;
        $book->tags()->sync($request->tags);

        $book->save();

        return redirect('/books/' . $id . '/edit')->with(['alert' => 'Your changes were saved.']);
    }

    /*
    * Asks user to confirm they want to delete the book
    * GET /books/{id}/delete
    */
    public function delete($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return redirect('/books')->with(['alert' => 'Book not found']);
        }

        return view('books.delete')->with([
            'book' => $book,
        ]);
    }

    /*
    * Deletes the book
    * DELETE /books/{id}/delete
    */
    public function destroy($id)
    {
        $book = Book::find($id);

        $book->tags()->detach();

        $book->delete();

        return redirect('/books')->with([
            'alert' => '“' . $book->title . '” was removed.'
        ]);
    }
}

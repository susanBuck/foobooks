<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class BookController extends Controller
{
    /*
     * GET /books/
     */
    public function index()
    {
        return view('books.index');
    }

    /*
     * GET /books/{title}
     */
    public function show($title)
    {
        return view('books.show')->with([
            'title' => $title
        ]);
    }
}

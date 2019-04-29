<?php

/*
 * Misc "static" pages
 */
Route::view('/', 'welcome');
Route::view('/about', 'about');
Route::view('/contact', 'contact');

/*
 * Books
 */
Route::get('/books/create', 'BookController@create');
Route::post('/books', 'BookController@store');

# Show the search form
Route::get('/books/search', 'BookController@search');

# Processing the search form
Route::get('/books/search-process', 'BookController@searchProcess');

Route::get('/books', 'BookController@index');
Route::get('/books/{id}', 'BookController@show');

# Update functionality
# Show the form to edit a specific book
Route::get('/books/{id}/edit', 'BookController@edit');

# Process the form to edit a specific book
Route::put('/books/{id}', 'BookController@update');

# DELETE
# Show the page to confirm deletion of a book
Route::get('/books/{id}/delete', 'BookController@delete');

# Process the deletion of a book
Route::delete('/books/{id}', 'BookController@destroy');


/**
 * Practice
 */
Route::any('/practice/{n?}', 'PracticeController@index');


# Example routes from the discussion of P3 development (Week 6, Part 8 video)
//Route::get('/', 'TriviaController@index');
//Route::get('/check-answer', 'TriviaController@checkAnswer');

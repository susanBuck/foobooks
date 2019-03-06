<?php

Route::view('/', 'welcome');
Route::get('/books', 'BookController@index');
Route::get('/books/{title}', 'BookController@show');

# Example routes from the discussion of P3 development (Week 6, Part 8 video)
//Route::get('/', 'TriviaController@index');
//Route::get('/check-answer', 'TriviaController@checkAnswer');
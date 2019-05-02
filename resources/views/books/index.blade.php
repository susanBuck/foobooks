@extends('layouts.master')

@section('title')
    Your Books
@endsection

@section('head')
    <link href='/css/books/index.css' rel='stylesheet'>
    <link href='/css/books/_book.css' rel='stylesheet'>
@endsection

@section('content')
    <section id='newBooks'>
        <h2>Recently added books</h2>
        @if($books->count() == 0)
            <p>None yet.</p>
        @else
            @foreach($newBooks as $book)
                @include('books._book')
            @endforeach
        @endif
    </section>

    <section id='allBooks'>
        <h2>Your books</h2>
        @if($books->count() == 0)
            <p>You don't have any books yet; would you like to <a href='/books/create'>add one?</a></p>
        @else
            @foreach($books as $book)
                @include('books._book')
            @endforeach
        @endif
    </section>
@endsection



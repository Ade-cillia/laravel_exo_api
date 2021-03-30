<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;

class BookController extends Controller
{
    public static function add(Request $request)
    {
        $book = new Book;
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->description = $request->description;
        $book->pages_nb = $request->pages_nb;
        $book->publication_year = $request->publication_year;
        $book->save();
        $book->genres()->attach($request->genres);
        return redirect('/list');
    }

    public static function delete(Request $request)
    {
        $book = Book::find($request->id);
        $book->genres()->detach();
        $book->delete();
        //Book::destroy($request->id);
        return redirect('/list');
    }

    public static function update(Request $request)
    {
        $book = Book::find($request->id);
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->description = $request->description;
        $book->pages_nb = $request->pages_nb;
        $book->genres()->sync($request->genres);
        $book->publication_year = $request->publication_year;
        $book->save();
        return redirect('/list');
    }

}

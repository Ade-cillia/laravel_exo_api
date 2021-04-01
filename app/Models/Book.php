<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public static function addBook($data){
        $book = new Book;
        
        $book->title = $data['title'];
        $book->author_id = $data['author_id'];
        $book->description = $data['description'];
        $book->pages_nb = $data['pages_nb'];
        $book->publication_year = $data['publication_year'];
        $book->save();
        $book->genres()->attach($data['genre_id']);
        return $book;
    }
    public static function updateBook($book, $data){
        $book->title = $data['title'];
        $book->author_id = $data['author_id'];
        $book->description = $data['description'];
        $book->pages_nb = $data['pages_nb'];
        $book->publication_year = $data['publication_year'];
        $book->genres()->sync($data['genre_id']);
        $book->save();
        return $book;
    }
}

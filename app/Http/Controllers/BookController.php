<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Resources\BookCollection;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Book_Genre;
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $genreFilter = $request->query->get('genre');
        if ($genreFilter) {
            
            $bookGenre = Book::whereHas('genres', function($query) use ($genreFilter){
                $query->where('genres.id', '=', $genreFilter);
            });
            return new BookCollection($bookGenre->simplePaginate(10));
        }else{
            $data = $request->query->get('search');
            $book = Book::where('title', 'like', "%{$data}%");
            return new BookCollection($book->orderBy('title')->simplePaginate(10));
        }    
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newBook = Book::addBook($request->all());
        //Book_Genre::addBook_Genre($newBook, $request->all());
        return response()->json($newBook,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if ($book) {
            return new BookResource($book);
        }else{
            return response()->json(['message' => 'ID not found',], 404);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $updateBook = Book::updateBook($book, $request->all());

        return response()->json($updateBook,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->genres()->detach();
        $book->delete();
        return response()->json('',204);
    }
}

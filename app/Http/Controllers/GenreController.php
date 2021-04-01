<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\GenreResource;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $data = $request->query->get('search');
        $genre = Genre::where('name', 'like', "%{$data}%");
        return new GenreCollection($genre->orderBy('name')->simplePaginate(10));
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newGenre = Genre::addGenre($request->all());

        return response()->json($newGenre,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $genre = Genre::find($id);
        if ($genre) {
            return new GenreResource($genre);
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
    public function update(Request $request, Genre $genre)
    {
        $updateGenre = Genre::updateGenre($genre, $request->all());

        return response()->json($updateGenre, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json('',204);
    }
}

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
     * 
     * @OA\GET(
     *      path="/genres",
     *      operationId="getAllGenres",
     *      tags={"CRUD_Genre"},

     *      summary="Get all genres",
     *      description="Returns all genres",
     *      security={"bearerAuth"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="search your results",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *  )
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
     * 
     * @OA\POST(
     *      path="/genres",
     *      operationId="createGenre",
     *      tags={"CRUD_Genre"},

     *      summary="Create genre",
     *      description="create genre",
     *      security={"bearerAuth"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass genre information",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Hentai"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful created",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),

     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *  )
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
     * 
     * @OA\GET(
     *      path="/genres/{id}",
     *      operationId="getGenreByID",
     *      tags={"CRUD_Genre"},

     *      summary="Get genre by ID",
     *      description="Returns genre by his id",
     *      security={"bearerAuth"},
     * 
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="genre id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Id not found"
     *      ),
     *  )
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
     * 
     * @OA\PATCH(
     *      path="/genres/{id}",
     *      operationId="updateGenreByID",
     *      tags={"CRUD_Genre"},

     *      summary="Update genre by ID",
     *      description="Update genre by his id",
     *      security={"bearerAuth"},
     * 
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="genre id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass genre information",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="NUUUUUUUUUUL"),
     *          ),
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Id not found"
     *      ),
     *  )
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
     * 
     * @OA\DELETE(
     *      path="/genres/{id}",
     *      operationId="deleteGenreByID",
     *      tags={"CRUD_Genre"},

     *      summary="Delete genre by ID",
     *      description="Delete genre by his id",
     *      security={"bearerAuth"},
     * 
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="genre id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=204,
     *          description="genre was deleted",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *  )
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json('',204);
    }
}

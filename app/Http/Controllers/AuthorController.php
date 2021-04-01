<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\GET(
     *      path="/authors",
     *      operationId="getAllAuthor",
     *      tags={"CRUD_Author"},

     *      summary="Get all authors",
     *      description="Returns all authors",
     *      security={"bearerAuth"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
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
    public function index(Request $request)
    {
        $data = $request->query->get('search');
        $author = Author::where('name', 'like', "%{$data}%");
        return new AuthorCollection($author->orderBy('name')->simplePaginate(10));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\POST(
     *      path="/authors",
     *      operationId="createAuthor",
     *      tags={"CRUD_Author"},

     *      summary="Create author",
     *      description="create author",
     *      security={"bearerAuth"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass author information",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Rage vince"),
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
        $newAuthor = Author::addAuthor($request->all());
        
        return response()->json($newAuthor,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\GET(
     *      path="/authors/{id}",
     *      operationId="getAuthorByID",
     *      tags={"CRUD_Author"},

     *      summary="Get author by ID",
     *      description="Returns author by his id",
     *      security={"bearerAuth"},
     * 
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="author id",
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
        $author = Author::find($id);
        if ($author) {
            return new AuthorResource($author);
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
     *      path="/authors/{id}",
     *      operationId="updateAuthorByID",
     *      tags={"CRUD_Author"},

     *      summary="Update author by ID",
     *      description="Update author by his id",
     *      security={"bearerAuth"},
     * 
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="author id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass author information",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Laisse Béton update"),
     *          ),
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
    public function update(Request $request, Author $author)
    {
        $updateAuthor = Author::updateAuthor($author, $request->all());
        return response()->json($updateAuthor,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\DELETE(
     *      path="/authors/{id}",
     *      operationId="deleteAuthorByID",
     *      tags={"CRUD_Author"},

     *      summary="Delete author by ID",
     *      description="Delete author by his id",
     *      security={"bearerAuth"},
     * 
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="book id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=204,
     *          description="authors was deleted",
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
    public function destroy(Author $author)
    {
        $author->delete();
        return response()->json('',204);
    }
}

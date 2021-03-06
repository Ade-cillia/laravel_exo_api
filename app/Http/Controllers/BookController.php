<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
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
     * 
     * @OA\GET(
     *      path="/books",
     *      operationId="getAllBooks",
     *      tags={"CRUD_Book"},

     *      summary="Get all books",
     *      description="Returns all books",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="sort your results ('title' or 'pages_nb' only)",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="genre",
     *          in="query",
     *          description="Filter your results with a genre_id (integer)",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="search your results",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number request (integer)",
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
        $sort = $request->query->get('sort');
        $genre = $request->query->get('genre'); //Filter
        $search = $request->query->get('search');
        $book = new Book;
        if ($sort) {
            if ($sort == "title") {
                $book = Book::orderBy('title');
            }elseif ($sort == "pages_nb") {
                $book = Book::orderBy('pages_nb');
            }
        }
        if ($genre) {
            $book = $book->whereHas('genres', function($query) use ($genre){
                $query->where('genres.id', '=', $genre);
            });
        }
        if($search){
            $book = $book->where('title', 'like', "%{$search}%");
        }
        return new BookCollection($book->simplePaginate(10));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @OA\POST(
     *      path="/books",
     *      operationId="createBook",
     *      tags={"CRUD_Book"},

     *      summary="Create book",
     *      description="create book",
     *      security={{"bearerAuth":{}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass book information",
     *          @OA\JsonContent(
     *              required={"title","author_id","description","pages_nb","publication_year","genre_id"},
     *              @OA\Property(property="title", type="string", example="C'est un beau titre"),
     *              @OA\Property(property="author_id", type="number", example="53"),
     *              @OA\Property(property="description", type="text", example="Description"),
     *              @OA\Property(property="pages_nb", type="number", example="206"),
     *              @OA\Property(property="publication_year", type="number", example="1999"),
     *              @OA\Property(property="genre_id", type="number", example="17"),
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
        $validateInfo = $request ->validate([
            "title" => "required|max:200",
            "author_id" => "required|integer",
            "description" => "required|max:500",
            "publication_year" => "required|integer",
            "pages_nb" => "required|integer",
            "genre_id" => "required|array",
        ]);
        $newBook = Book::addBook($validateInfo);
        return response()->json($newBook,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\GET(
     *      path="/books/{id}",
     *      operationId="getBookByID",
     *      tags={"CRUD_Book"},

     *      summary="Get book by ID",
     *      description="Returns book by his id",
     *      security={{"bearerAuth":{}}},
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
     * 
     * @OA\PATCH(
     *      path="/books/{id}",
     *      operationId="updateBookByID",
     *      tags={"CRUD_Book"},

     *      summary="Update book by ID",
     *      description="Update book by his id",
     *      security={{"bearerAuth":{}}},
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
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass book information",
     *          @OA\JsonContent(
     *              required={"title","author_id"},
     *              @OA\Property(property="title", type="string", example="C'est un beau titre update"),
     *              @OA\Property(property="author_id", type="number", example="54"),
     *              @OA\Property(property="description", type="text", example="Description DE OUF"),
     *              @OA\Property(property="pages_nb", type="number", example="666"),
     *              @OA\Property(property="publication_year", type="number", example="1990"),
     *              @OA\Property(property="genre_id", type="number", example="16"),
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
    public function update(Request $request, $id)
    {
        $validateInfo = $request ->validate([
            "title" => "required|max:200",
            "author_id" => "required|integer",
            "description" => "required|max:500",
            "publication_year" => "required|integer",
            "pages_nb" => "required|integer",
            "genre_id" => "required|array",
        ]);
        $book = Book::find($id);
        if ($book) {
            $updateBook = Book::updateBook($book, $validateInfo);
            return response()->json($updateBook,200);
        }else{
            return response()->json(['message' => 'ID not found',], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @OA\DELETE(
     *      path="/books/{id}",
     *      operationId="deleteBookByID",
     *      tags={"CRUD_Book"},

     *      summary="Delete book by ID",
     *      description="Delete book by his id",
     *      security={{"bearerAuth":{}}},
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
     *          description="Book was deleted",
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
    public function destroy($id)
    {
        $book = Book::find($id);
        if ($book) {
            $book->genres()->detach();
            $book->delete();
            return response()->json('',204);
        }else{
            return response()->json(['message' => 'ID not found',], 404);
        }
    }
}

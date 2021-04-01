<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\POST(
     *      path="/register",
     *      operationId="createUser",
     *      tags={"CRUD_User"},

     *      summary="Register",
     *      description="create user",
     *      security={"bearerAuth"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user information",
     *          @OA\JsonContent(
     *              required={"name","email","password","c_password"},
     *              @OA\Property(property="name", type="string", example="THIS IS A NAME"),
     *              @OA\Property(property="email", type="string",format="email", example="email@mmmmmmmmmmmmaiiiiiiiiil.mail"),
     *              @OA\Property(property="password", type="string",format="password", example="000_yes_its_true"),
     *              @OA\Property(property="c_password", type="string",format="password", example="000_yes_its_true"),
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\POST(
     *      path="/login",
     *      operationId="login",
     *      tags={"CRUD_User"},

     *      summary="Login",
     *      description="login",
     *      security={"bearerAuth"},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass user information",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string",format="email", example="email@mmmmmmmmmmmmaiiiiiiiiil.mail"),
     *              @OA\Property(property="password", type="string",format="password", example="000_yes_its_true"),
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
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}
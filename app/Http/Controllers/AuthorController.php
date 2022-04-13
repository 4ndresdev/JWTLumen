<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Http\Request;


class AuthorController extends Controller
{

    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return authors list
     * 
     * @return Illuminate\Http\Response
     */

    public function index(){
     
        $authors = Author::all();

        return $this->successResponse($authors);
        
    }

     /**
     * Create an instance of author
     * 
     * @return Illuminate\Http\Response
     */

    public function store(Request $request){

        $rules = [
            'name'      => 'required|max:255',
            'gender'    => 'required|max:255|in:Male,Female',
            'country'   => 'required|max:255'
        ];

        $this->validate($request, $rules);

        $author = Author::create($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);

    }
    
    /**
     * Return an specific author
     * 
     * @return Illuminate\Http\Response
     */

    public function show($author){

        $author = Author::findOrFail($author);

        return $this->successResponse($author);

    }

    /**
     * Update information of an existing author
     * 
     * @return Illuminate\Http\Response
     */

    public function update(Request $request, $author){

        $rules = [
            'name'      => 'max:255',
            'gender'    => 'max:255|in:Male,Female',
            'country'   => 'max:255'
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($author);

        $author->fill($request->all());

        // Verifica si los datos si cambiaron
        if($author->isClean()){
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $author->save();
        return $this->successResponse($author);

    }

    /**
     * Remove author
     * 
     * @return Illuminate\Http\Response
     */

    public function destroy($author){

        $author = Author::findOrFail($author);
        $author->delete();

        return $this->successResponse($author);

    }

}

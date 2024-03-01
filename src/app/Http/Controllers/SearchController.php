<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\User;
  
class SearchController extends Controller
{
    /**
     * Autocomplete for users
     *
     * @return \Illuminate\Http\Response
     */
    public function usersAutocomplete(Request $request)
    {
        $data = User::select("username")
                ->where("username","LIKE","%{$request->input('query')}%")
                ->get();
   
        return json_encode($data);
    }
}
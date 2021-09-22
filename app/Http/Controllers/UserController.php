<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    public function columns()
    {
        return Schema::getColumnListing('users');
    }

    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function store(Request $request)
    {
        //dd($request->except('email'));
        //$collection = collect($request->all());
        //dd($collection);
        //dd($request->json());
        
        $this->validate($request, ['firstname'=>'required', 
                                    'lastname'=>'required', 
                                    'email' => 'required|email|unique:users']);
        $user = new User();
        $user->fill($request->all());
        $user->save();

        return $user;
    }

    public function update(Request $request, $id)
    {   

        $user = User::findOrFail($id);
        $this->validate($request, ['firstname'=>'required', 'lastname'=>'nullable']);
        $user->fill($request->only('firstname', 'lastname'));
        $user->save();

        return $user;
    }

    public function delete($id)
    {   
        $user = User::findOrFail($id);
        $user->delete();       
        return response($user, 204);
    }
}

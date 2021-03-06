<?php

namespace App\Http\Controllers;

use Exception;
use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Validation\AddressValidator;

class AddressController extends Controller
{
    protected $address;
    public function __construct()
    {
        $this->middleware('auth');
        $this->address = new Address();
    }
    
 
    public function index()
    {
        return view('address.list')->with('addresses', $this->address->getAddresses());
    }
    
    public function show($id)
    {
        return redirect('/home');
    }
    
    
    public function create()
    {
        return view('address.index');
    }



    public function getErrors($errors)
    {
        return view('address.errors', ['errors' => $errors]);
    }

    public function store(Request $request)
    {
        $this->address->store($request);

        Session::flash('message', "Pomyślnie dodano");
        return redirect($request->input('last_url', '/home'));
    }
    
    public function store2(Request $request)
    {      

//        try {
//            $address->store($request);
//        } catch (\Illuminate\Validation\ValidationException $ex) {
//            return response(view('home2')
//                ->with('errors', $ex->validator->errors()), 422);
//        }
      
        return view('Address.list_item', ['address' => $this->address->store($request)]);
    }

    public function edit(Address $address)
    {
        $this->authorization($address);
           
        if (request()->expectsJson()) {
            return view('address.form2', compact('address'));
        }

        return view('address.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorization($address);
        
        $address->store($request, $address);
        if (request()->expectsJson()) {
            return view('Address.list_item', compact('address'));
        }
        Session::flash('message', "Pomyślnie wyedytowano");
        return redirect('/home');
    }

    public function destroy(Address $address)
    {
        $this->authorization($address);
        $address->delete();
        if (request()->expectsJson()) {
            return response('Address Deleted!');
        }
        return redirect('/home');
    }

    public function authorization(Address $address)
    {
        if ($address->user_id != auth()->id()) {
            return abort(403, 'Unauthorized action.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Items;
use App\Tags;
use App\ItemTag;
use App\Categories;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModeratorController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
      @param  array   $parameters
      @return \Symfony\Component\HttpFoundation\Response
     */
//    public function callAction($method, $parameters)
//    {
//        //sprawdzam czy użytkownik ma uprawnienia do zapisania konfiguracji
//        //$this->authorize('konfiguracja-save');
//        if (!\Auth::user()->isAdmin()) {
//            return redirect(route('home'))->with('error', trans('messages.access_denied'));
//        }
//
//        return parent::callAction($method, $parameters);
//    }

    public function createNewItem() {


        $tags = Tags::all();
        $categories = Categories::all();
        return view('items.add', compact('tags', 'categories'));
    }

    public function getItem($itemid) {
        $item = Items::where('id', $itemid)->with('category')->get();
//       dd($item);
        return view('items.show', compact('item'));
    }
    
   
    public function saveNewItem(Request $request) {

        $request->validate([
            'name' => 'bail|required|min:4|max:255|regex:/^[A-ZĄŻŹĘŚĆŁÓa-ząćłśóżźę0-9., \/]+$/',
            'price' => 'required|regex:/^[0-9., ]+$/',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $item = new Items();
        $item->name = $request->input('name');
        $item->price = floatval($request->input('price'));
        $item->category_id = $request->input('category');
        if (Input::has('photo')) {

            // get current time and append the upload file extension to it,
            // then put that name to $photoName variable.
            $photoName = time() . '.' . $request->photo->getClientOriginalExtension();

            /*
              talk the select file and move it public directory and make avatars
              folder if doesn't exsit then give it that unique name.
             */
            $request->photo->move(public_path('photos'), $photoName);
            $item->photo_name = $photoName;
        }

        $item->save();
        if (Input::has('tags')) {
            $tags = $request->input('tags');
            foreach ($tags as $tagid) {
                $itemTag = new ItemTag();
                $itemTag->item_id = $item->id;
                $itemTag->tag_id = $tagid;
                $itemTag->save();
            }
        }

        //dodawnia wiadomości po wykonanej akcji
        Session::flash('message', "Pomyślnie dodano");
        return redirect()->route('item.create');
    }

    public function editItem($itemid) {
        $tags = Tags::all();
        $categories = Categories::all();
        $item = Items::where('id', '=', $itemid)->with('category')->with('tags')->first();
//        dd($item);
        return view('items.edit', compact('tags', 'categories', 'item'));
    }

    public function updateItem(Request $request, $itemid) {

        $request->validate([
            'name' => 'bail|required|min:4|max:255|regex:/^[A-ZĄŻŹĘŚĆŁÓa-ząćłśóżźę0-9., \/]+$/',
            'price' => 'required|regex:/^[0-9., ]+$/',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $item = Items::where('id', '=', $itemid)->first();
        $item->name = $request->input('name');
        $item->price = floatval($request->input('price'));
        $item->category_id = $request->input('category');
        if (Input::has('photo')) {

            // get current time and append the upload file extension to it,
            // then put that name to $photoName variable.
            $photoName = time() . '.' . $request->photo->getClientOriginalExtension();

            /*
              talk the select file and move it public directory and make avatars
              folder if doesn't exsit then give it that unique name.
             */
            $request->photo->move(public_path('photos'), $photoName);
            $item->photo_name = $photoName;
        }

        $item->save();
        if (Input::has('tags')) {
            ItemTag::where('item_id', $item->id)->delete();
            $tags = $request->input('tags');
            foreach ($tags as $tagid) {
                $itemTag = new ItemTag();
                $itemTag->item_id = $item->id;
                $itemTag->tag_id = $tagid;
                $itemTag->save();
            }
        }

        //dodawnia wiadomości po wykonanej akcji
        Session::flash('message', "Pomyślnie zedytowano");
        return redirect()->back();
    }

    public function deleteItem($itemid) {
        ItemTag::where('item_id', $itemid)->delete();
        Items::where('id', '=', $itemid)->delete();
        Session::flash('message', "Pomyślnie dodano");
        return redirect()->back();
    }

}
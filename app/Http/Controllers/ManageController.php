<?php

namespace App\Http\Controllers;

use App\User;
use App\RolesHasUsers;
use App\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManageController extends Controller {

    public function getUserslist() {
        $roles = Roles::all();
        $users = User::with('roles')->get();
//        dd($users);
        return view('manage', compact('users', 'roles'));
    }

    public function deleteUser($userid) {

        User::where('id', '$userid')->delete();
        $this->getUserslist();
    }

    public function changeUser(Request $request, $userid) {

        $request->validate([
            'roles' => 'bail|required',
        ]);

        RolesHasUsers::where('users_id', $userid)->delete();
        $roles = $request->input('roles');
        foreach ($roles as $roleID) {
            $user = new RolesHasUsers();
            $user->users_id = $userid;
            $user->roles_id = $roleID;
            $user->save();
        }
        Session::flash('message', "Pomyślnie zmieniono uprawnienia");
        return $this->getUserslist();
    }

}
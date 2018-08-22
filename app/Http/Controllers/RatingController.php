<?php

namespace App\Http\Controllers;

use App\NotifiPrice;

class RatingController extends Controller {

    public function saveNotfication($itemID) {
        $userID = auth()->id();
        $notification = NotifiPrice::where('item_id', $itemID)->where('user_id', $userID)->first();
        if ($notification == null) {
            $notification = new NotifiPrice();
            $notification->user_id = $userID;
            $notification->item_id = $itemID;
            $notification->save();
        }
        return redirect()->back();
    }

}

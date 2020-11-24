<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use App\Room;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class RoomController extends MainAdminController
{
    public function getList()
    {
        return Room::getRoomList();
    }

}

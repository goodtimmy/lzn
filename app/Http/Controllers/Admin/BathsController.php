<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use App\Bath;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;

class BathsController extends MainAdminController
{
    public function getList()
    {
        return Bath::getBathList();
    }
    public function getBusyRooms(Request $request)
    {
        $inputs = $request->all();
        return Bath::getRoomsWithBusyBaths($inputs['date']);
    }

}

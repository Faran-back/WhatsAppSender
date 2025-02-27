<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DeviceController extends Controller
{
    public function create_device(){
        return view('device.index');
    }

}

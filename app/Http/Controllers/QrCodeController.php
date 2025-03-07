<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function scan($id) {
        $device = Device::findOrFail($id);

        $session = $device->device_name;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $device->token
        ])->post("http://localhost:21465/api/{$session}/start-session",[
            'token' => $device->token
        ]);

        if ($response->successful()) {
            if(isset($response['qrcode'])){
                $qrCodeImage = $response['qrcode'];
                return view('qr.index', compact(['qrCodeImage','device']));
            }
        }



        return view('qr.index', compact('device'));
    }

}

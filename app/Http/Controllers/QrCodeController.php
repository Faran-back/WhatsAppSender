<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

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

    public function disconnect_session(Request $request, $id){
        try{

        $device = Device::findOrFail($id);

        $session = $device->device_name;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $device->token
        ])->post("http://localhost:21465/api/{$session}/logout-session");

        if ($response->successful()) {

            if($device->status === 'Connected'){
                $device->update([
                    'status' => 'Disconnected'
                ]);
            }else{
                return redirect()->route('device.list')->with('error', 'Device Already Disconnected');
            }

            return redirect()->route('device.list')->with('success', 'Disconnected');
        }

        } catch(Exception $e){
            return redirect()->route('device.list')->with('error', 'Failed to disconnect');
        }

    }

}

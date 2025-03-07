<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        if (!$response->successful()) {
            return response()->json([
                'status' => 500,
                'message' => 'Error starting session: ' . $response->body()
            ]);
        }

        Log::info('API Response:', $response->json());


        $sessionId = $response->json('session');

        if (!$sessionId) {
            return response()->json([
                'status' => 500,
                'message' => 'Error: Session ID not received from API.'
            ]);
        }

        $qrCodeImage = QrCode::size(250)->generate($sessionId);


        return view('qr.index', compact(['qrCodeImage','device']));
    }

}

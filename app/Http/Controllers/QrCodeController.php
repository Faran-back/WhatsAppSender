<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Device;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function scan($id) {
        $device = Device::findOrFail($id);

        // Send request to get the QR code
        $response = Http::post(env('URL_WA_SERVER') . '/sessions/add', [
            'sessionId' => $device->device_name
        ]);

        $data = $response->json();

        if (isset($data['qr'])) {
            $qrCodeImage = $data['qr'];

        } else {
            $qrCodeImage = null; // If no QR code, show error
        }


        return view('qr.index', compact(['qrCodeImage','device']));
    }

}

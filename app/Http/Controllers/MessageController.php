<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{

    public function index(){
        $devices = Device::all();
        return view('messages.index',compact('devices'));
    }


    public function send_message(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'device' => 'required',
                'to' => 'required',
                'message' => 'required|string'
            ]);

            $device = Device::findOrFail($request->device);

            $response = Http::post(env('URL_WA_SERVER') . $device->name . '/messages/send', [
            ]);

            // Decode response
            $responseData = $response->json();

            // Check for API errors
            if (isset($responseData['error'])) {
                throw new Exception($responseData['error']['message']);
            }

            Message::create([
                'device_id' => $request->device_id,
                'to' => $request->to,
                'message' => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

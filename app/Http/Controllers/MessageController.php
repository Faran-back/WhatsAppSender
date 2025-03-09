<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            $request->validate([
                'device' => 'required',
                'phone' => 'required',
                'message' => 'required|string'
            ]);

            $device = Device::findOrFail($request->device);
            $session = $device->device_name;

            // Debug: Log session value
            Log::info("Using session: " . $session);

            if (!$session) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Session ID is missing!',
                ]);
            }

            $url = "http://localhost:21465/api/{$session}/send-message"; // Correct syntax

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $device->token
            ])->post($url, [
                'phone' => $request->phone . '@s.whatsapp.net',
                'message' => $request->message,
            ]);

            Log::info("WhatsApp API Response:", ['body' => $response->body()]);

            if (!$response->successful()) {
                Log::error("WhatsApp API Request Failed. Response: " . $response->body());

                return response()->json([
                    'status' => 'error',
                    'error' => 'API request failed',
                    'api_response' => $response->body(),
                ]);
            }

            Message::create([
                'device_id' => $device->id,
                'phone' => $request->phone,
                'message' => $request->message,
                'token' => $device->token
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully!',
                'api_response' => $response->json(),
            ]);

        } catch (Exception $e) {
            Log::error("WhatsApp API Error: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }



}

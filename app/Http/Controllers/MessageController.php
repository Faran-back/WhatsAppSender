<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{

    public function index(){
        return view('messages.index');
    }


    public function send_message(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'to' => 'required',
                'message' => 'required|string'
            ]);

            // Retrieve credentials
            $accessToken = env('WHATSAPP_ACCESS_TOKEN');
            $phoneNumberId = env('PHONE_NUMBER_ID');

            // Check if credentials are set
            if (!$accessToken || !$phoneNumberId) {
                throw new Exception("Missing WhatsApp API credentials.");
            }

            // API URL
            $url = "https://graph.facebook.com/v22.0/{$phoneNumberId}/messages";

            // Send request
            $response = Http::withToken($accessToken)->post($url, [
                "messaging_product" => "whatsapp",
                "to" => $request->to,
                "type" => "text",
                "text" => ["body" => $request->message]
            ]);

            // Decode response
            $responseData = $response->json();

            // Check for API errors
            if (isset($responseData['error'])) {
                throw new Exception($responseData['error']['message']);
            }

            Message::create([
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

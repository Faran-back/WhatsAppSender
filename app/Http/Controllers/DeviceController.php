<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Device;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DeviceController extends Controller
{
    public function index(){
        return view('device.index');
    }

    public function list(){
        $devices = Device::all();

        return view('device.list', compact('devices'));
    }

    public function edit($id){
        $device = Device::find($id);
        return view('device.edit', compact('device'));
    }

    public function store(Request $request){
        try{

            $request->validate([
                'phone_number' => 'required',
                'device_name' => 'required'
            ]);

            $device = Device::create([
                'phone_number' => $request->phone_number,
                'device_name' => $request->device_name,
                'description' => $request->description,
            ]);

            $session = $request->device_name;
            $secretKey = 'THISISMYSECURETOKEN';

            $url = $url = 'http://localhost:21465/api/' . $session . '/' . $secretKey . '/generate-token';
            $response = Http::post($url);

            if (!$response->successful()) {
                Log::error('API Request Failed: ' . $response->body());
                return response()->json([
                    'status' => 500,
                    'message' => 'Error generating token: ' . $response->body()
                ]);
            } else {
                $full = $response->json('full');

                $token = $response->json('token');

                $device->update([
                    'full' => $full,
                    'token' => $token
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Device created successfully!',
                    'redirect_url' => route('device.list')
                ]);
            }


        } catch (Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function update(Request $request, $id){
        try{

           $validatedData =  $request->validate([
                'device_name' => 'required',
                'phone_number' => 'required',
                'description' => 'required',
            ]);

            $device = Device::where('id',$id)->update($validatedData);

            return response()->json([
                'success' => true,
                'message' =>'Device Updated Successfully!',
                'redirect_url' => route('device.list')
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function delete($id){

    $device = Device::findOrFail($id);
    $device->delete();

    // Flash success message
    Session::flash('success', 'Device deleted successfully!');

    return redirect()->route('device.list');
}

public function checkStatus($id){

    $device = Device::findOrFail($id);

    $session = $device->device_name;
    $token = $device->token;

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->get("http://localhost:21465/api/{$session}/check-connection-session");

    if ($response->successful()) {

        $status = $response->json('message') ?? 'Disconnected';

        if($status === 'Connected'){
            $device->update([
                'status' => 'Connected'
            ]);
        }

        return response()->json(['status' => $status]);

    }

        return response()->json(['status' => 'Disconnected']);

}
}

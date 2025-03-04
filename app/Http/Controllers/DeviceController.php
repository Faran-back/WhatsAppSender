<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Device;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

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

            return response()->json([
                'success' => true,
                'message' => 'Device created successfully!',
                'redirect_url' => route('device.list')
            ]);


            // return redirect()->route('device.list')->with(['success', 'Device created successfully']);


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

    // public function delete($id){
    //     try{
    //         $device = Device::where('id' , $id);

    //         if(!$device){
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'Device Not Found',
    //             ]);
    //         }

    //         $device->delete();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Device deleted successfully!'
    //         ]);

    //     }catch(Exception $e){
    //         return response()->json([
    //             'status' => 500,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function delete($id)
{
    $device = Device::findOrFail($id);
    $device->delete();

    // Flash success message
    Session::flash('success', 'Device deleted successfully!');

    return redirect()->route('device.list');
}

public function checkStatus($id)
    {
        $device = Device::findOrFail($id);

        // Call API to check status
        $response = Http::get(env('URL_WA_SERVER') . '/sessions/' . $device->device_name . '/status');
        $data = $response->json();

        // If authenticated, update database
        if ($data['status'] === "AUTHENTICATED") {
            $device->update([
                'status' => 'AUTHENTICATED'
            ]);
        }

        return response()->json(['status' => $data['status']]);
    }
}

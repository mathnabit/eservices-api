<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Service::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'service_url' => 'required',
        //     'category_id' => 'required',
        // ]);
        // $request->validate([
        //     'photo' => 'required|file|image|size:1024|dimensions:max_width=500,max_height=500',
        // ]);
        /**
         * Store Service and photo
         */
        $request['service'] = json_decode($request['service'], true);
        $service = Service::create([
            'title' => $request['service']['title'],
            'description' => $request['service']['description'],
            'service_url' => $request['service']['service_url'],
            'image_url' => asset('storage/images/0.jpg'),
            'category_id' => $request['service']['category_id'],
        ]);
        if($request->photo != null) {
            $path = $request->photo->storeAs('public/images', $service->id.'.'.$request->photo->extension());
            $service->update([
                'image_url' => asset('storage/images/' . $service->id.'.'.$request->photo->extension())
            ]);
        }
        
        return $service;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request['service'] = json_decode($request['service'], true);
        $service = Service::find($request['service']['id']);
        $service->update([
            'title' => $request['service']['title'],
            'description' => $request['service']['description'],
            'service_url' => $request['service']['service_url'],
            'category_id' => $request['service']['category_id'],
        ]);
        if($request->photo != null) {
            $path = $request->photo->storeAs('public/images', $service->id.'.'.$request->photo->extension());
            $service->update([
                'image_url' => asset('storage/images/' . $service->id.'.'.$request->photo->extension())
            ]);
        }
        
        return $service;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::find($id);
        $ext = explode('.', $service->image_url);
        if(Storage::disk('public')->exists('images/'.$id.'.'.$ext[count($ext)-1])) {
            Storage::disk('public')->delete('images/'.$id.'.'.$ext[count($ext)-1]);
        }
        return Service::destroy($id);
    }
}

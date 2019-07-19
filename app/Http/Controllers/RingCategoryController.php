<?php

namespace App\Http\Controllers;

use App\RingCategory; 
use Illuminate\Http\Request;

class RingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = RingCategory::all();

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ring_image !== null) {
            $exploded = explode(',',$request->ring_image);
            $decoded = base64_decode($exploded[1]);
            if(str_contains($exploded[0],'jpeg'))
            {
                $extension = 'jpg';
            }
            else
            {
                $extension = 'png';
            }

            
            $filename = generateRandomString().'.'.$extension;
            $path = public_path().'/img/ring/'.$filename;
            file_put_contents($path,$decoded);    
            $ring = RingCategory::create($request->except('ring_image') + ['ring_image' => $filename]);
            
        }
        else {
            $ring = RingCategory::create($request->all());
        }

        return response()->json($ring, 201);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $img)
    {
        if($request->ring_image !== null) {
            $exploded = explode(',',$request->ring_image);
            $decoded = base64_decode($exploded[1]);
            if(str_contains($exploded[0],'jpeg'))
            {
                $extension = 'jpg';
            }
            else
            {
                $extension = 'png';
            }

            if (file_exists('img/ring/'.$img.'.png')) {
                unlink('img/ring/'.$img.'.png');
            }
            else if (file_exists('img/ring/'.$img.'.jpg')){
                unlink('img/ring/'.$img.'.jpg');
            }
            else{}
            
            $filename = generateRandomString().'.'.$extension;
            $path = public_path().'/img/ring/'.$filename;
            file_put_contents($path,$decoded);

            $ring = RingCategory::where('id', $id)->update($request->except('ring_image') + ['ring_image' => $filename]);
        }
        else {

            $ring = RingCategory::where('id', $id)->update($request->all());
            
        }

        return response()->json($ring, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function deleteRing(Request $request){
        $ring = RingCategory::where('id', $request->deletedID)->delete();

        if($request->deletedName !== null) {
            $filename = 'img/ring/'.$request->deletedName;

            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        return 'deleted';
    }
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function base64url_decode($data) { 
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
} 
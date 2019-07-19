<?php

namespace App\Http\Controllers;

use App\Mould; 
use App\Client; 
use App\Auth; 
use Illuminate\Http\Request;

class MouldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $result = Mould::leftJoin('client', function($join) { $join->on('mould.client_id', '=', 'client.id'); })
                        ->leftJoin('ring_category', function($join) { $join->on('mould.ring_id', '=', 'ring_category.id'); })
                        ->get(['mould.id','mould.mould_image','mould.mould_details','client.client_name','client.client_type','ring_category.ring_code','ring_category.ring_name','mould.rack_no','client.id as client_id','ring_category.id as ring_id']);

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
        
        if($request->mould_image !== null) {
            $exploded = explode(',',$request->mould_image);
            $decoded = base64_decode($exploded[1]);
            if(str_contains($exploded[0],'jpeg'))
            {
                $extension = 'jpg';
            }
            else
            {
                $extension = 'png';
            }

            if(Mould::where('mould_image', $request->mould_image)->exists())
                $mould = false;
            else {
                $filename = generateRandomString().'.'.$extension;
                $path = public_path().'/img/mould/'.$filename;
                file_put_contents($path,$decoded);    
                $mould = Mould::create($request->except('mould_image') + ['mould_image' => $filename]);
            }
        }
        else {
            $mould = Mould::create($request->all());
        }

        return response()->json($mould, 201);
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
        if($request->mould_image !== null) {
            $exploded = explode(',',$request->mould_image);
            $decoded = base64_decode($exploded[1]);
            if(str_contains($exploded[0],'jpeg'))
            {
                $extension = 'jpg';
            }
            else
            {
                $extension = 'png';
            }

            if (file_exists('img/mould/'.$img.'.png')) {
                unlink('img/mould/'.$img.'.png');
            }
            else if (file_exists('img/mould/'.$img.'.jpg')){
                unlink('img/mould/'.$img.'.jpg');
            }
            else{}
                
            $filename = generateRandomString().'.'.$extension;
            $path = public_path().'/img/mould/'.$filename;
            file_put_contents($path,$decoded);

            $ring = Mould::where('id', $id)->update($request->except('mould_image') + ['mould_image' => $filename]);
        }
        else {

            $ring = Mould::where('id', $id)->update($request->all());
            
        }

        echo $decoded;

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

    public function deleteMould(Request $request){
        $mould = Mould::where('id', $request->deletedID)->delete();

        if($request->deletedName !== null) {
            $filename = 'img/mould/'.$request->deletedName;

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
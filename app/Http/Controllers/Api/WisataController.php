<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Wisata;

class WisataController extends Controller
{
    public function index(){
        $wisatas = Wisata::all();

        if(count($wisatas) > 0){
            return response([
                'message' => 'Retrieve All Success!',
                'data' => $wisatas
            ], 200);
        }

        return response([
            'message' => 'Empty!',
            'data' => null
        ], 200);
    }

    public function show($id){
        $wisata = Wisata::find($id);

        if(!is_null($wisata)){
            return response([
                'message' => 'Retrieve Wisata Success!',
                'data' => $wisata
            ], 200);
        }

        return response([
            'message' => 'Wisata Not Found!',
            'data' => null
        ], 404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_wisata' => 'required|max:60|unique:wisatas',
            'lokasi' => 'required',
            'deskripsi' => 'required',
            'url_gambar' => 'required',
            'harga' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $wisata = Wisata::create($storeData);
        return response([
            'message' => 'Add Wisata Success!',
            'data' => $wisata
        ], 200);
    }

    public function destroy($id){
        $wisata = Wisata::find($id);

        if(is_null($wisata)){
            return response([
                'message' => 'Wisata Not Found!',
                'data' => null
            ], 404);
        }

        if($wisata->delete()){
            return response([
                'message' => 'Delete Wisata Success!',
                'data' => $wisata
            ], 200);
        }

        return response([
            'message' => 'Delete Wisata Failed!',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id){
        $wisata = Wisata::find($id);

        if(is_null($wisata)){
            return response([
                'message' => 'Wisata Not Found!',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_wisata' => ['required', 'max:60', Rule::unique('wisatas')->ignore($wisata)],
            'lokasi' => 'required',
            'deskripsi' => 'required',
            'url_gambar' => 'required',
            'harga' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $wisata->nama_wisata = $updateData['nama_wisata'];
        $wisata->lokasi = $updateData['lokasi'];
        $wisata->deskripsi = $updateData['deskripsi'];
        $wisata->url_gambar = $updateData['url_gambar'];
        $wisata->harga = $updateData['harga'];


        if($wisata->save()){
            return response([
                'message' => 'Update Wisata Success!',
                'data' => $wisata
            ], 200);
        }

        return response([
            'message' => 'Update Wisata Failed!',
            'data' => null
        ], 400);
    }
}

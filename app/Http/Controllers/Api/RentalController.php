<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Rental;

class RentalController extends Controller
{
    public function index(){
        $rentals = Rental::all();

        if(count($rentals) > 0){
            return response([
                'message' => 'Retrieve All Success!',
                'data' => $rentals
            ], 200);
        }

        return response([
            'message' => 'Empty!',
            'data' => $rentals
        ], 200);
    }

    public function show($id){
        $rental = Rental::find($id);

        if(!is_null($rental)){
            return response([
                'message' => 'Retrieve Rental Success!',
                'data' => $rental
            ], 200);
        }

        return response([
            'message' => 'Rental Not Found!',
            'data' => null
        ], 404);
    }
    
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'no_plat' => 'required|unique:rentals',
            'nama_kendaraan' => 'required',
            'jenis_kendaraan' => 'required',
            'biaya_penyewaan' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $rental = Rental::create($storeData);
        return response([
            'message' => 'Add Wisata Success!',
            'data' => $rental
        ], 200);
    }

    public function destroy($id){
        $rental = Rental::find($id);

        if(is_null($rental)){
            return response([
                'message' => 'Rental Not Found!',
                'data' => null
            ], 404);
        }

        if($rental->delete()){
            return response([
                'message' => 'Delete Rental Success!',
                'data' => $rental
            ], 200);
        }

        return response([
            'message' => 'Delete Rental Failed!',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id){
        $rental = Rental::find($id);

        if(is_null($rental)){
            return response([
                'message' => 'Rental Not Found!',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'no_plat' => ['required', Rule::unique('rentals')->ignore($rental)],
            'nama_kendaraan' => 'required',
            'jenis_kendaraan' => 'required',
            'biaya_penyewaan' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $rental->no_plat = $updateData['no_plat'];
        $rental->nama_kendaraan = $updateData['nama_kendaraan'];
        $rental->jenis_kendaraan = $updateData['jenis_kendaraan'];
        $rental->biaya_penyewaan = $updateData['biaya_penyewaan'];
        $rental->status = $updateData['status'];


        if($rental->save()){
            return response([
                'message' => 'Update Rental Success!',
                'data' => $rental
            ], 200);
        }

        return response([
            'message' => 'Update Rental Failed!',
            'data' => null
        ], 400);
    }
}

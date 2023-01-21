<?php

namespace App\Http\Controllers\Api\Fitur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Shelter;

class ShelterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $shelters = Shelter::with('districts')->get();
            return response()->json([
                'message' => 'Shelter data',
                'data' => $shelters
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'city' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $shelters = Shelter::whereText($request->city)->first();

            // var_dump($shelters);
            if ($shelters) {
                return response()->json([
                    'success' => false,
                    'message' => "{$request->city}, is available in the database"
                ]);
            }

            $city = $request->city;
            $shelter = new Shelter;
            $shelter->text = ucfirst($city);
            $shelter->save();

            return response()->json([
                'success' => true,
                'message' => 'Adding new shelter ' . $shelter->text
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
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
    public function update(Request $request, Shelter $shelter)
    {
        try {
            $validator = Validator::make($request->all(), [
                'city' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $shelterToUpdate = Shelter::findOrFail($request->id);

            $shelter->city = $request->city;
            $shelter->save();

            return response()->json([
                'success' => true,
                'message' => "Update shelter {$shelterToUpdate->city}",
                'data' => $shelter
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
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
}

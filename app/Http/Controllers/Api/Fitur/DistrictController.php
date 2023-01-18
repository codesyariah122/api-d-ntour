<?php

namespace App\Http\Controllers\Api\Fitur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\District;
use App\Models\Shelter;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                'district_name' => 'required',
                'shelter_id' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $shelter = Shelter::whereId($request->shelter_id)->first();

            if ($shelter === NULL) {
                return response()->json([
                    'success' => false,
                    'message' => "Shelter with ID : {$request->shelter_id}, is not found"
                ]);
            }

            $districts = District::whereDistrictName($request->district_name)->first();

            // var_dump($Districts);
            if ($districts) {
                return response()->json([
                    'success' => false,
                    'message' => "{$request->district_name}, is available in the database"
                ]);
            }

            $district_name = $request->district_name;
            $district = new District;
            $district->district_name = ucfirst($district_name);
            $district->shelter_id = $request->shelter_id;
            $district->save();
            // $district_id = $district->id;
            // $shelter->districts('post')->sync($district_id);

            return response()->json([
                'success' => true,
                'message' => 'Adding new District ' . $district->district_name
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
    public function update(Request $request, $id)
    {
        //
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

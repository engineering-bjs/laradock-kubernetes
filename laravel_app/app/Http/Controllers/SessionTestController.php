<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionTestController extends Controller
{
    protected $data = array();

    public function store(Request $request)
    {
        $this->data = $request->all();
        $request->session()->put('data', json_encode($this->data));
        return response()->json(['message' => 'session added successfully']);
    }


    public function index(Request $request)
    {
        if ($request->session()->exists('data')) {
            $this->data = json_decode($request->session()->get('data'));

        }
        return response()->json(['data' => $this->data]);
    }
}

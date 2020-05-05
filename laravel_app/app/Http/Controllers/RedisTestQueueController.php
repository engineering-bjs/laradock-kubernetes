<?php

namespace App\Http\Controllers;
use App\Jobs\RedisTestJob;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RedisTestQueueController extends Controller
{
        public function store(Request $request)
        {
            $data = $request->all();
            $json_encode = json_encode($data);
            RedisTestJob::dispatch($json_encode);
            return response()->json(['message'=>'data successfully submitted']);
        }
}

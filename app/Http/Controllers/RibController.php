<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rib;
use App\Http\Resources\Rib as RibResource;

class RibController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ribs = Rib::all();
        return RibResource::collection($ribs);
        
    }

  
}

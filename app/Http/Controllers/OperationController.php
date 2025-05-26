<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use App\Http\Resources\Operation as OperationResource;

class OperationController extends Controller
{
    public function index()
    {
        $operations = Operation::all();
        return OperationResource::collection($operations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $operation = $request->isMethod('put') ? Operation::findOrFail($request->operation_id) : new Operation;

        $operation->id = $request->input('operation_id');
        $operation->label = $request->input('label');
        $operation->rib_id = $request->input('rib_id');
        $operation->date = $request->input('date');
        $operation->amount = $request->input('amount');

        if($operation->save()){
            return new OperationResource($operation);
        }
    }

    public function getByRib(Request $request, $rib_id)
    {
        $begin = $request->query('begin');
        $end = $request->query('end');

        $query = Operation::where('rib_id', $rib_id);

        if ($begin && $end) {
            $query->whereBetween('date', [$begin, $end]);
        }

        $operations = $query->get();

        return OperationResource::collection($operations);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $operation = Operation::findOrFail($id);
    
        if($operation->delete()){
            return new OperationResource($operation);
        }
    }
}


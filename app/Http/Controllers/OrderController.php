<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\OrderResource;
use App\Laravue\Acl;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\Order;
use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Validator;

class OrderController extends Controller
{
    const ITEM_PER_PAGE = 20;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @return \Illuminate\Http\Response|ResourceCollection*
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $orderQuery = Order::query();
        $orderQuery_role = Order::query();
        $orderQuery_status = Order::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);

        $role = Arr::get($searchParams, 'role', '');
        $status = Arr::get($searchParams, 'status', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $orderQuery_role->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
            return OrderResource::collection($orderQuery_role->paginate($limit));
        }
        if (!empty($status)) {
            $orderQuery_status->where('status', 'LIKE', '%' . $status . '%');
            return OrderResource::collection($orderQuery_status->paginate($limit));
        }
        if (!empty($keyword)) {
            $orderQuery->where('id', 'LIKE', '%' . $keyword . '%');
            $orderQuery->where('user_id', 'LIKE', '%' . $keyword . '%');
            return OrderResource::collection($orderQuery->paginate($limit));
        }

        return OrderResource::collection($orderQuery->paginate($limit));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\laravue\Models\Order  $order
     *
     * @return OrderResource
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\laravue\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\laravue\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\laravue\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'user_id' => 'required',
            'report_id' => 'nullable',
            'orderer_id' => 'nullable',
            'executor_id' => 'nullable',
            'status' => 'nullable',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }

}

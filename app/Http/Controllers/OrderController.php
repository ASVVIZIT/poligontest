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
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    const ITEM_PER_PAGE = 20;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $orderQuery = Order::query();
        $orderQuery->withTrashed();
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
            return OrderResource::collection($orderQuery->paginate($limit));
        }

        return OrderResource::collection($orderQuery->paginate($limit));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        sleep(1);
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'user_id' => 'nullable',
                    'orderer_id' => 'nullable',
                    'executor_id' => 'nullable',
                    'status' => 'nullable',
                    'title' => 'nullable',
                    'note' => 'nullable',
                    'content' => 'nullable',
                    'content_short' => 'nullable',
                    'sum' => 'nullable',
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $order = Order::create([
                'user_id' => $params['user_id'],
                'orderer_id' => $params['orderer_id'],
                'executor_id' => $params['executor_id'],
                'status' => $params['status'],
                'title' => $params['title'],
                'note' => $params['note'],
                'content' => $params['content'],
                'content_short' => $params['content_short'],
                'sum' => $params['sum'],
            ]);
            $order->save();
            return response()->json(null, 204);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  Order $order
     * @return OrderResource|\Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\laravue\Models\Order $order
     * @return Response
     */
    public function edit(Order $order)
    {
        sleep(1);
        $validator_edit = Validator::make(
            $request_edit->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'user_id' => 'nullable',
                    'orderer_id' => 'nullable',
                    'executor_id' => 'nullable',
                    'status' => 'nullable',
                    'title' => 'nullable',
                    'note' => 'nullable',
                    'content' => 'nullable',
                    'content_short' => 'nullable',
                    'sum' => 'nullable',
                ]
            )
        );

        if ($validator_edit->fails()) {
            return response()->json(['errors' => $validator_edit->errors()], 403);
        } else {
            $params_edit = $request_edit->all();
            $order = Order::create([
                'user_id' => $params_edit['user_id'],
                'orderer_id' => $params_edit['orderer_id'],
                'executor_id' => $params_edit['executor_id'],
                'status' => $params_edit['status'],
                'title' => $params_edit['title'],
                'note' => $params_edit['note'],
                'content' => $params_edit['content'],
                'content_short' => $params_edit['content_short'],
                'sum' => $params_edit['sum'],
            ]);
            $order->save();
            return response()->json(null, 204);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order    $order
     * @return OrderResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Order $order)
    {

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $order->user_id = $request->get('user_id');
            $order->orderer_id = $request->get('orderer_id');
            $order->executor_id = $request->get('executor_id');
            $order->status = $request->get('status');
            $order->title = $request->get('title');
            $order->note = $request->get('note');
            $order->content = $request->get('content');
            $order->content_short = $request->get('content_short');
            $order->sum = $request->get('sum');
            $order->save();
            return new OrderResource($order);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\laravue\Models\Order $order
     * @return OrderResource
     */
    public function restore(Order $order)
    {
        try {
            $order->restore();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
        return new OrderResource($order);
    }

    /**
    * @param  \App\laravue\Models\Order $order
    * @return Response
    */
    public function destroy(Order $order)
    {
        //Order::withTrashed()->find($id)->restore();
        //DB::delete('delete from orders where id = ?',[$id]);
        try {
            $order->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
        return response()->json(null, 204);
    }


    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'user_id' => 'nullable',
            'report_id' => 'nullable',
            'orderer_id' => 'nullable',
            'executor_id' => 'nullable',
            'status' => 'nullable',
            'title' => 'nullable',
            'note' => 'nullable',
            'content' => 'nullable',
            'content_short' => 'nullable',
            'sum' => 'nullable',
        ];
    }

}

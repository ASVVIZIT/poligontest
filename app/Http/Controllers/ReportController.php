<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\ReportResource;
use App\Laravue\Acl;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\Report;
use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Validator;

class ReportController extends Controller
{
    const ITEM_PER_PAGE = 5;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @return \Illuminate\Http\Response|ResourceCollection*
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $reportQuery = Report::query();
        $reportQuery_role = Report::query();
        $reportQuery_status = Report::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);

        $role = Arr::get($searchParams, 'role', '');
        $status = Arr::get($searchParams, 'status', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $reportQuery_role->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
            return ReportResource::collection($reportQuery_role->paginate($limit));
        }
        if (!empty($status)) {
            $reportQuery_status->where('status', 'LIKE', '%' . $status . '%');
            return ReportResource::collection($reportQuery_status->paginate($limit));
        }
        if (!empty($keyword)) {
            $reportQuery->where('id', 'LIKE', '%' . $keyword . '%');
            $reportQuery->where('user_id', 'LIKE', '%' . $keyword . '%');
            return ReportResource::collection($reportQuery->paginate($limit));
        }

        return ReportResource::collection($reportQuery->paginate($limit));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\laravue\Models\Report $report)
     *
     * @return ReportResource
     */
    public function show(Report $report)
    {
        return new ReportResource($report);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\laravue\Models\Report $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\laravue\Models\Report $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\laravue\Models\Report $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
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
            'status' => 'nullable',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }

}

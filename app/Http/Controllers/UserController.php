<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\Acl;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
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
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('firstname', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('surname', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('patronymic', 'LIKE', '%' . $keyword . '%');
            $userQuery->whereDate('birthday', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('email', 'LIKE', '%' . $keyword . '%');
        }

        return UserResource::collection($userQuery->paginate($limit));
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
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $user = User::create([
                'name' => $params['name'],
                'firstname' => $params['firstname'],
                'surname' => $params['surname'],
                'patronymic' => $params['patronymic'],
                'birthday' => $params['birthday'],
                'email' => $params['email'],
                'password' => Hash::make($params['password']),
            ]);
            $role = Role::findByName($params['role']);
            $user->syncRoles($role);

            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $firstname = $request->get('firstname');
            $found = User::where('firstname', $firstname)->first();
            //      if ($found && $found->id !== $user->id) {
            //          return response()->json(['error' => 'firstname has been taken'], 403);
            //     }
            $surname = $request->get('surname');
            $found = User::where('surname', $surname)->first();
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'surname has been taken'], 403);
            }
            $patronymic = $request->get('patronymic');
            $found = User::where('patronymic', $patronymic)->first();
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'patronymic has been taken'], 403);
            }
            $birthday = $request->get('birthday');
            $found = User::where('birthday', $birthday)->first();
            //   if ($found && $found->id !== $user->id) {
            //       return response()->json(['error' => 'birthday has been taken'], 403);
            //   }
            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'Email has been taken'], 403);
            }
            $password = $request->get('password');
            $found = User::where('password', $password)->first();
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'password has been taken'], 403);
            }

          //  $dt = Carbon::createFromFormat('Y-m-d', $birthday);

            $user->name = $request->get('name');
            $user->firstname = $request->get('firstname');
            $user->surname = $request->get('surname');
            $user->patronymic = $request->get('patronymic');
            $user->birthday = $request->get('birthday');
            $user->email = $email;
            $user->password = \Illuminate\Support\Facades\Hash::make($password);
            //$user->created_at = Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
            $user->save();
            return new UserResource($user);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }

        try {
            $user->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }
}

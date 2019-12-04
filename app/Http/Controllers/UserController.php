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
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $userQuery->withTrashed();

        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
            return UserResource::collection($userQuery->paginate($limit));
        }

        if (!empty($keyword)) {
            $userQuery->where('address', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('name', 'LIKE', '%' . $keyword . '%');
           // $userQuery->orWhere('gender', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('email', 'LIKE', '%' . $keyword . '%');
            $userQuery->orWhere('skype', 'LIKE', '%' . $keyword . '%');
            return UserResource::collection($userQuery->paginate($limit));
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
                    'firstname' => 'nullable',
                    'surname' => 'nullable',
                    'patronymic' => 'nullable',
                    'birthday' => 'nullable',
                    'gender' => 'nullable',
                    'avatar' => 'nullable',
                    'phone1' => 'nullable',
                    'phone2' => 'nullable',
                    'phone3' => 'nullable',
                    'phone4' => 'nullable',
                    'skype' => 'nullable',
                    'address' => 'nullable',
                    'email' => 'nullable',
                    'password' => ['required', 'min:8'],
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
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'firstname has been taken'], 403);
            }
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
            $user->gender = $request->get('gender');
            $user->phone1 = $request->get('phone1');
            $user->phone2 = $request->get('phone2');
            $user->phone3 = $request->get('phone3');
            $user->phone4 = $request->get('phone4');
            $user->skype = $request->get('skype');
            $user->address = $request->get('address');
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
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        try {
            User::onlyTrashed()->find($id)->restore();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
       // dd(__METHOD__, request()->all());

        $user = User::withTrashed()->find($id);
        if ($user->trashed()) {
            try {
                $user->forceDelete();
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        } else {
            try {
                $user->delete();
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        }
        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_user_forever(User $user, $id)
    {
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }
        try {
            User::onlyTrashed()->find($id)->forceDelete();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
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
            'firstname' => 'nullable',
            'surname' => 'nullable',
            'patronymic' => 'nullable',
            'birthday' => 'nullable',
            'gender' => 'nullable',
            'avatar' => 'nullable',
            'phone1' => 'nullable',
            'phone2' => 'nullable',
            'phone3' => 'nullable',
            'phone4' => 'nullable',
            'skype' => 'nullable',
            'address' => 'nullable',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }
}

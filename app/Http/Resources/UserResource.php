<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $url_uploads = env('APP_URL');
        //Start point of our date range.
        $start = strtotime("2015-01-01 01:10:10");
        //End point of our date range.
        $end = strtotime("2019-01-01 12:10:10");
        $timestamp = mt_rand($start, $end);
        $date_uploads = date('Y-m-d h:m:s', $timestamp);
        // Подготовка путей для аватарок
        if ($this->avatar === 'default.png'){
            // Если пользователь не выбрал аватарку то подгрузиться аватарка согласно стандартным значениям
            $avatar_path = $url_uploads . '/uploads/avatars/default/'. 'v0/';
        } elseif ($this->avatar === 'default_male.png') {
            $avatar_path = $url_uploads . '/uploads/avatars/default/'. 'v0/';
        } elseif ($this->avatar === 'default_female.png') {
            $avatar_path = $url_uploads . '/uploads/avatars/default/'. 'v0/';
        } else {
            $avatar_path = $url_uploads . '/uploads/avatars/' . $this->id . '/';
        }

        $key_user_oline_status = 'user-is-online-' . $this->id;
        $this->onlineStatus = false;
        if (Cache::has($key_user_oline_status, false)) {

            $value = Cache::get($key_user_oline_status, false);
            if ($value) {
                $this->onlineStatus = $value;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'firstname' => $this->firstname,
            'patronymic' => $this->patronymic,
            'gender' => $this->gender,
            'family_status' => $this->family_status,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'phone3' => $this->phone3,
            'phone4' => $this->phone4,
            'skype' => $this->skype,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
            'deleted_at' => $this->deleted_at ?? '',
            'roles' => array_map(
                function ($role) {
                    return $role['name'];
                },
                $this->roles->toArray()
            ),
            'permissions' => array_map(
                function ($permission) {
                    return $permission['name'];
                },

                $this->getAllPermissions()->toArray()
            ),
            'avatar' => $avatar_path . $this->avatar . '?23423423424+' . $date_uploads,
            'onlineStatus' => $this->onlineStatus,
        ];
        //'avatar' => 'http://i.pravatar.cc',
    }
}

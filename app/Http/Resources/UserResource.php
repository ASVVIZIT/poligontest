<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        // Подготовка путей для аватарок
        if ($this->avatar === 'default.png'){
            // Если пользователь не выбрал аватарку то подгрузиться аватарка согласно стандартным значениям
            $avatar_path = 'http://poligontest.loc/uploads/avatars/default/'. 'v0/';
        } elseif ($this->avatar === 'default_male.png') {
            $avatar_path = 'http://poligontest.loc/uploads/avatars/default/'. 'v0/';
        } elseif ($this->avatar === 'default_female.png') {
            $avatar_path = 'http://poligontest.loc/uploads/avatars/default/'. 'v0/';
        } else {
            $avatar_path = 'http://poligontest.loc/uploads/avatars/' . $this->id . '/';
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'firstname' => $this->firstname,
            'patronymic' => $this->patronymic,
            'gender' => $this->gender,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'phone3' => $this->phone3,
            'phone4' => $this->phone4,
            'skype' => $this->skype,
            'address' => $this->address,
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
            'avatar' => $avatar_path . $this->avatar,
        ];

        //'avatar' => 'http://i.pravatar.cc',
    }
}

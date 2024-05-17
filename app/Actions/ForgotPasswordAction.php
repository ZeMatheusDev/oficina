<?php

namespace App\Actions;

use App\Mail\SendTemporaryPassword;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserAction
{
    private array $userParams;
    private string $createOrUpdate;

    public function __construct()
    {
    }



    public function withParams(array $userParams): static
    {
        $this->userParams = $userParams;
        return $this;
    }

    public function sendTempPassword(): void
    {
        $to = $this->userParams['email'];
        $name = $this->userParams['name'];
        $password = $this->userParams['uncrypted_password'];
        Mail::to($to)->send(new SendTemporaryPassword($name, $password));
    }

    private function storePicture(): void
    {
        if (Arr::exists($this->userParams, 'profile_picture') && is_file($this->userParams['profile_picture'])) {
            $this->userParams['profile_picture'] = Storage::put('avatars/1', $this->userParams['profile_picture']);
        }
    }

    private function syncPermissions($userID)
    {
        $user = User::find($this->userParams['user_id'] ?? $userID->id);

        $link_group = filter_var(data_get($this->userParams, 'link_group'), FILTER_VALIDATE_BOOLEAN);

        if (Arr::exists($this->userParams, 'permission') && !$link_group) {
            $user->syncPermissions(array_keys($this->userParams['permission']));
            $user->syncRoles([]);
        } elseif ($link_group) {
            $user->syncRoles(data_get($this->userParams, 'group_permissions'));
            $user->syncPermissions([]);
        }
    }

    private function makePassword(): void
    {
        $this->userParams['uncrypted_password'] = rand(11111111, 99999999);
    }

    private function encryptPassword(): void
    {
        if (data_get($this->userParams, 'uncrypted_password')) {
            $this->userParams['password'] = bcrypt($this->userParams['uncrypted_password']);
            return;
        }

        $this->userParams['password'] = bcrypt($this->userParams['password']);
    }

    public function switchStoreUpdate(String $switch): static
    {
        $this->createOrUpdate = match ($switch) {
            'store' => 1,
            'update' => 2
        };
        return $this;
    }

    public function store(): mixed
    {
        $store = User::create($this->userParams);
        $this->sendTempPassword();
        return $store;
    }

    public function update(): mixed
    {
        $filteredParams = array_filter($this->userParams, function ($array) {
            return is_bool($array) || !empty($array);
        });

        $update = User::find($this->userParams['user_id'])
            ->update($filteredParams);
        return $update;
    }

    public function execute(): mixed
    {
        if ($this->createOrUpdate) {
            $this->makePassword();
        }

        $this->encryptPassword();

        $this->storePicture();

        $userID = match ($this->createOrUpdate) {
            '1' => $this->store(),
            '2' => $this->update()
        };

        return $this->syncPermissions($userID);
    }
}

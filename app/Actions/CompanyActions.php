<?php

namespace App\Actions;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CompanyActions
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

    private function storeLogo(): void
    {
        if (Arr::exists($this->userParams, 'logo_path') && is_file($this->userParams['logo_path'])) {
            $this->userParams['logo_path'] = Storage::put('logo_company/1', $this->userParams['logo_path']);
        }
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
        $store = Company::create($this->userParams);
        return $store;
    }

    public function update(): mixed
    {
        $filteredParams = array_filter($this->userParams, function ($array) {
            return is_bool($array) || !empty($array);
        });
        $update = Company::find($this->userParams['company_id'])
            ->update($filteredParams);
        return $update;
    }

    public function execute(): void
    {
        $this->storeLogo();

        match ($this->createOrUpdate) {
            '1' => $this->store(),
            '2' => $this->update()
        };
    }
}

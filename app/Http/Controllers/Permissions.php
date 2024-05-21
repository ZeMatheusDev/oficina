<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Exception;
use App\Models\DisabledColumns;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use stdClass;


class Permissions extends Controller
{
    public function render(Request $request)
    {
        
        $usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
        $roles = Role::paginate(10);
        $empresaSelecionada = session()->all()['empresa_nome'];


        return Inertia::render('Permission/List')
            ->with([
				"empresaSelecionada" => $empresaSelecionada,
                "hasRole" => $usuario != null,
                'roles' => $roles,
            ]);

    }

    public function create()
    {
        $permUser = Auth::user()->hasPermissionTo("create.users");
		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }
        $usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

        $empresaSelecionada = session()->all()['empresa_nome'];

        $roles = Role::all();
        return Inertia::render('Permission/Create')
            ->with([
                'roles' => $roles,
				"empresaSelecionada" => $empresaSelecionada,

                "hasRole" => $usuario != null,

            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'max:255|unique:roles',
            'status' => 'sometimes',
            'permission' => 'sometimes|array',
        ], [
            'name.unique' => 'O nome do grupo já existe!'
        ]);

        $role = Role::create($validated);

        $role->syncPermissions($validated['permission'] ?? []);

        return redirect()->route('list.permission');
    }

    public function edit($roleId)
    {

        $permUser = Auth::user()->hasPermissionTo("edit.users");
        $permUser2 = Auth::user()->hasPermissionTo("duplicate.users");
        if ((!$permUser) && (!$permUser2)) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        $usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

        $role = Role::findOrFail($roleId);
        $role->load(['permissions']);
        $empresaSelecionada = session()->all()['empresa_nome'];

        return Inertia::render('Permission/Edit')
            ->with([
                'role' => $role,
				"empresaSelecionada" => $empresaSelecionada,
                "hasRole" => $usuario != null,
            ]);
    }


    public function update(Request $request, $roleId)
    {

        $role = Role::findOrFail($roleId);

        $role->syncPermissions($request->permission ?? []);

        $role->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->route('list.permission');
    }

    public function delete($role_id)
    {
        $permUser = Auth::user()->hasPermissionTo("delete.users");
		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        Role::findOrFail($role_id)
            ->delete();
        return redirect()->back();
    }
}

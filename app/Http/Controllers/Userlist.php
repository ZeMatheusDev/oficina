<?php

namespace App\Http\Controllers;

use App\Actions\UserAction;
use App\Http\Requests\FormUser;
use App\Mail\SendTemporaryPassword;
use App\Models\DisabledColumns;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use \Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use stdClass;


class Userlist extends Controller
{
    public function index(Request $request)
    {
        $permUser = Auth::user()->hasPermissionTo("list.users");
		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        $data = Session::all();
        if(!isset($data["users"]) || empty($data["users"])){
                    session(["users" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
                    $data = Session::all();
                }
    
                $Filtros = new Security;
                if($request->input()){
                $Limpar = false;
                if($request->input("limparFiltros") == true){
                    $Limpar = true;
                }
    
                $arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["users"]);	
                if($arrayFilter){
    
                session(["users" => $arrayFilter]);
                $data = Session::all();
                }
                }
    
        $columnsTable = DisabledColumns::whereRouteOfList('list.users')
            ->first()
            ?->columns;

        $query =  DB::table("users")
        ->leftjoin('model_has_roles as m', 'users.id', '=', 'm.model_id')	
        ->leftjoin('roles as r', 'm.role_id', '=', 'r.id')	
        ->select(DB::raw("users.*, DATE_FORMAT(users.created_at, '%d/%m/%Y') as data_final, r.name as customizada"));
               
        User::query();

       

        $query->when(request('searchBy'), function ($q) {
            $q->where('users.name', 'like', '%' . request('searchBy') . '%');
            $q->orWhere('users.email', 'like', '%' . request('searchBy') . '%');
            $q->orWhere('users.created_at', 'like', '%' . request('searchBy') . '%');
        });

        $users = $query->paginate(($data["users"]["limit"] ?: 10))
            ->appends(['page', 'orderBy', 'searchBy', 'limit']);

        return Inertia::render('User/List', [
            'columnsTable' => $columnsTable,
            'users' => $users,
            "Filtros" => $data["users"],
        ]);
    }

    public function create()
    {
        // if (!Auth::user()->can('criar-usuarios')) {
        //     return redirect()->back();
        // }

        $permUser = Auth::user()->hasPermissionTo("edit.users");
		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        $roles = Role::all();

        $companies = DB::table("companies")->where('deleted','0')->where('status','1')->get();

        return Inertia::render('User/Create', [
            'roles' => $roles,
            'Empresa' => $companies,
        ]);
    }

    private function syncPermissions($user_id,$link_group,$group_permission,$permission)
    {
        $user = User::find($user_id);

        $link_group = filter_var(data_get($link_group, 'link_group'), FILTER_VALIDATE_BOOLEAN);

        if (!$link_group) {
            $user->syncPermissions(array_keys($permission));
            $user->syncRoles([]);
        } elseif ($link_group) {
            $user->syncRoles(data_get($group_permission, 'group_permissions'));
            $user->syncPermissions([]);
        }
    }

    public function store(Request $request)
    {
        // if (!Auth::user()->can('criar-usuarios')) {
        //     return redirect()->back();
        // }

        $Usuarios =  DB::table("users")->where("email",$request->email)->first();      
        if($Usuarios){
            return redirect()->route("form.store.user")->withErrors(['msg' => "E-mail já cadastrado em nossa base de dados."]);
        }        

        $Array = array();

     

        if($request->group_permissions == 'false' && $request->permission == $Array){
            return redirect()->route("form.store.user")->withErrors(['msg' => "Permissão não selecionada."]);
        } 


        $Empresa = implode(',',$request->empresa);

        if($request->permission){
            $request->permission = implode(',',$request->permission);
        }
       
    //    print_r($request->all()); die;
        $save = new stdClass;        
        $save->empresa = $Empresa;
        $save->name = $request->name;
        $save->email = $request->email;
        $save->password = bcrypt($request->password);
        $save->phone = $request->phone;
        $save->status = $request->status;

        $save = collect($save)->toArray();
        DB::table("users")
            ->insert($save);
        $lastId = DB::getPdo()->lastInsertId();
        
        $user = User::find($lastId);

        if ($request->group_permissions == false || $request->permission){
            $permissions = explode(',', $request->permission);          
            $allPermissions = Permission::get('id')->pluck('id')->toArray();     
            $validPermissions = array_intersect($permissions, $allPermissions);
            $user->syncPermissions($validPermissions);
        } else {
            $user->syncRoles($request->group_permissions);
        }
        
        return redirect()->route('list.users');
    }

    public function edit($id)
    {
        // if (!Auth::user()->can('editar-usuarios')) {
        //     return redirect()->back();
        // }

        $permUser = Auth::user()->hasPermissionTo("edit.users");
        $permUser2 = Auth::user()->hasPermissionTo("duplicate.users");
        if ((!$permUser) && (!$permUser2)) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        $data = Session::all();

        $roles = Role::all();
        $user1 = User::findOrFail($id);

        $user1['link_group'] = "true";

        if ($user1->permissions->count()) {
            $user1['link_group'] = "false";
        }

        $companies = DB::table("companies")->where('deleted','0')->where('status','1')->get();

        $Usuarios =  DB::table("users")->where("id",$id)->first();      
        $MinhasEmpresas = explode(',',$Usuarios->empresa);
        $EmpresaFinal = '';
        $Empresa = DB::table("companies")->where('deleted','0')->where('status','1')->whereIn("id",$MinhasEmpresas)->get();
        foreach($Empresa as $valid){
            $EmpresaFinal .= $valid->id.',';
        }
        $EmpresaFinal = substr($EmpresaFinal,0,-1);

        $user1->load(['permissions', 'roles']);

        return Inertia::render('User/Edit', [
            'roles' => $roles,
            'user1' => $user1,
            'Empresa' => $companies,
            'ExplodeEmpresa' => $EmpresaFinal,
        ]);
    }

    public function editProfile()
    {
        $user_id = auth()->user()->id;

       
        $roles = Role::all();
        $user = User::findOrFail($user_id);

        $user['link_group'] = "true";

        if ($user->permissions->count()) {
            $user['link_group'] = "false";
        }

        $user->load(['permissions', 'roles']);
        $UsuarioAtual = DB::table("users")->where('id',$user_id)->first();
        $EmpresaExplodeUsuarioAtual = explode(',',$UsuarioAtual->empresa);

        $companies = DB::table("companies")->where('deleted','0')->where('status','1')->whereIn('id',$EmpresaExplodeUsuarioAtual)->get();

        $data = Session::all();       
           
        $EmpresaExplode = explode(',',$data['empresa']);
        $arr = '';
        foreach ($EmpresaExplode as $emp => $key){   
            if($key){          
            $SelecionaEmpresa = DB::table("companies")->where('id',$key)->where('status','1')->first();
    
            $arr .= $SelecionaEmpresa->name.',';
            }
        }
    

        return Inertia::render('User/Profile', [
            'roles' => $roles,
            'user' => $user,
            'Empresa' => $companies,
            'SelectEmpresa' => $arr,
        ]);
    }

    public function updateProfile(Request $request)
    {
      
        $user_id = auth()->user()->id;

        if($request->empresaSelect){
        $EmpresaImplode = implode(',',$request->empresaSelect);
        }

        $UsuarioAtual = DB::table("users")->where('id',$user_id)->first();

        $url = null;
        $rules = 'jpg,png';
        $FormatosLiberados = explode(",", $rules);    
        if($request->hasFile('profile_picture')){
            if($request->file('profile_picture')->isValid()){
                if (in_array($request->file('profile_picture')->extension(),$FormatosLiberados)) {
                $ext = $request->file('profile_picture')->extension();
                $profile_picture = $request->file('profile_picture')->store('avatars/1');
                $url = $profile_picture;		
                $url = explode('/',$url);
                $url = $profile_picture;
                } else {
                    $ext = $request->file('profile_picture')->extension();
                    return redirect()->route("form.update.profile")->withErrors(['msg' => "Atenção o formato enviado na Foto de Perfil foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
                }
            }
        }

      
        
        $save = new stdClass;        
        $save->name = $request->name;
        $save->email = $request->email;
        if($request->password){
        $save->password = bcrypt($request->password);
        }
        if($request->profile_picture){
            $save->profile_picture = $url;
        }
        $save->phone = $request->phone;

        $save = collect($save)->toArray();        
        DB::table("users")
        ->where("id", $user_id)
        ->update($save);

             
        if($request->empresaSelect){
        session(['empresa' => $EmpresaImplode]);
        }

        return redirect()->route('home');
    }


    public function update(Request $request, $user_id)
    {

        // if (!Auth::user()->can('editar-usuarios')) {
        //     return redirect()->back();
        // }


        $Usuarios =  DB::table("users")->where("email",$request->email)->where('id','<>',$user_id)->first();      
        if($Usuarios){
            return redirect()->route("form.update.user")->withErrors(['msg' => "E-mail já cadastrado em nossa base de dados."]);
        } 

        $Array = array();    

        if($request->group_permissions == 'false' && $request->permission == $Array){
            return redirect()->route("form.update.user")->withErrors(['msg' => "Permissão não selecionada."]);
        } 


        $Empresa = '';
        foreach($request->empresa as $emp => $key){
            foreach($key as $value){
                if(is_int($value)){            
                $Empresa .= $value.',';
                }
            }           
        }
       $Empresa = substr($Empresa,0,-1);
     
        if($request->permission){
            $request->permission = implode(',',$request->permission);
        }       
         
    //    print_r($request->all()); die;
        $save = new stdClass;        
        $save->empresa = $Empresa;
        $save->name = $request->name;
        $save->email = $request->email;
        if($request->password){
        $save->password = bcrypt($request->password);
        }
        $save->phone = $request->phone;
        $save->status = $request->status['value'];

        // echo '<pre>';
        // print_r($save);
        // echo '</pre>';
        // die;

        $save = collect($save)->toArray();        
        DB::table("users")
        ->where("id", $user_id)
        ->update($save);

        
      

        $user = User::find($user_id);

        if ($request->group_permissions == false || $request->permission){
            $permissions = explode(',', $request->permission);          
            $allPermissions = Permission::get('id')->pluck('id')->toArray();     
            $validPermissions = array_intersect($permissions, $allPermissions);
            $user->syncPermissions($validPermissions);
        } else {
            $user->syncRoles($request->group_permissions);
        }

        if($user_id == auth()->user()->id){
        $Sessoes = Session::all();   
        session(['empresa' => $Empresa]);
        }

        return redirect()->route('list.users');
    }

    public function delete($user_id)
    {
        // if (!Auth::user()->can('excluir-usuarios')) {
        //     return redirect()->back();
        // }

        $permUser = Auth::user()->hasPermissionTo("delete.users");
		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        User::findOrFail($user_id)
            ->delete();
        return redirect()->back();
    }

    public function resendPassword($user_id)
    {
        $user = User::findOrFail($user_id);

        $to = $user->email;
        $name = $user->name;
        $password = rand(11111111, 99999999);

        $user->temp_password = true;
        $user->password = bcrypt($password);
        $user->save();

        Mail::to($to)->send(new SendTemporaryPassword($name, $password));

        return redirect()->back();
    }
}

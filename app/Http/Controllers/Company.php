<?php

namespace App\Http\Controllers;

use App\Actions\CompanyActions;
use App\Http\Requests\FormCompany;
use App\Models\Company as ModelsCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DisabledColumns;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use stdClass;

class Company extends Controller
{
    public function index(Request $request)
    {
        $permUser = Auth::user()->hasPermissionTo("list.companies");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}
            
            $data = Session::all();
            if(!isset($data["companies"]) || empty($data["companies"])){
                        session(["companies" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
                        $data = Session::all();
                    }
        
                    $Filtros = new Security;
                    if($request->input()){
                    $Limpar = false;
                    if($request->input("limparFiltros") == true){
                        $Limpar = true;
                    }
        
                    $arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["companies"]);	
                    if($arrayFilter){
        
                    session(["companies" => $arrayFilter]);
                    $data = Session::all();
                    }
                    }
            $columnsTable = DisabledColumns::whereRouteOfList('list.company')
            ->first()
            ?->columns;

        $query = ModelsCompany::query();


        $query->when(request('searchBy'), function ($q) {
            $q->where('name', 'like', '%' . request('searchBy') . '%');
            $q->orWhere('cnpj', 'like', '%' . request('searchBy') . '%');
            $q->orWhere('created_at', 'like', '%' . request('searchBy') . '%');
        });

        $companies = $query->paginate(($request->limit ?: 10))
            ->appends(['page', 'orderBy', 'searchBy', 'limit']);

            $usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

            $Registros = $this->Registros();

        return Inertia::render('Company/List', [
            'columnsTable' => $columnsTable,
            'companies' => $companies,
            "Filtros" => $data["companies"],
            "hasRole" => $usuario != null,
            "Registros" => $Registros,
        ]);
    }


    public function Registros()
		{ 
		
			$mes = date("m");
			$Total = DB::table("companies")	
			->where("companies.deleted", "0")
			->count();

			$Ativos = DB::table("companies")	
			->where("companies.deleted", "0")
			->where("companies.status", "1")
			->count();

			$Inativos = DB::table("companies")	
			->where("companies.deleted", "0")
			->where("companies.status", "0")
			->count();

			$EsseMes = DB::table("companies")	
			->where("companies.deleted", "0")
			->whereMonth("companies.created_at", $mes)
			->count();


			$data = new stdClass;
			$data->total = number_format($Total, 0, ",", ".");
			$data->ativo = number_format($Ativos, 0, ",", ".");
			$data->inativo = number_format($Inativos, 0, ",", ".");
			$data->mes = number_format($EsseMes, 0, ",", ".");
			return $data;


		}


    public function create()
    {
        $permUser = Auth::user()->hasPermissionTo("create.companies");
      		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        // $Texto = ''

        // $Texto = explode(';',$Texto);
        // foreach($Texto as $Campos){
        //     if($Campos){
        //     $Campos = explode('–',$Campos);
        //     $save = new stdClass;
        //     $save->codigo = trim($Campos[0]);
        //     $save->descricao = trim($Campos[1]);
        //     $save = collect($save)->toArray();
        //     DB::table("pis_situacao_tributaria")
        //     ->insert($save);
        //     }
        // }

        $icms_origem = DB::table("icms_origem")->orderBy('descricao', 'asc')->get();
        $icms_situacao_tributaria = DB::table("icms_situacao_tributaria")->orderBy('descricao', 'asc')->get();

        $pis_situacao_tributaria = DB::table("pis_situacao_tributaria")->orderBy('descricao', 'asc')->get();
        $cofins_situacao_tributaria = DB::table("cofins_situacao_tributaria")->orderBy('descricao', 'asc')->get();

        $usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

        return Inertia::render('Company/Create',['Origem'=>$icms_origem,'Tributaria'=>$icms_situacao_tributaria, 'Pis' => $pis_situacao_tributaria,
        "hasRole" => $usuario != null,
        'Cofins'=>$cofins_situacao_tributaria]);
    }

    public function store(Request $request)
    {

        $url = null;
        $rules = 'jpg,png';
        $FormatosLiberados = explode(",", $rules);    
        if($request->hasFile('logo_path')){
            if($request->file('logo_path')->isValid()){
                if (in_array($request->file('logo_path')->extension(),$FormatosLiberados)) {
                $ext = $request->file('logo_path')->extension();
                $logo_path = $request->file('logo_path')->store('logo_company/1');
                $url = $logo_path;		
                $url = explode('/',$url);
                $url = $logo_path;
                } else {
                    $ext = $request->file('logo_path')->extension();
                    return redirect()->route("list.company")->withErrors(['msg' => "Atenção o formato enviado na logo foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
                }
            }
        }
   

        $save = new stdClass;
        $save->name = $request->name;
        $save->nome_fantasia = $request->nome_fantasia;
        if($request->logo_path){
            $save->logo_path = $url;      
        }   
        $save->cnpj = $request->cnpj;
        $save->insc_estadual = $request->insc_estadual;
        $save->insc_municipal = $request->insc_municipal;

        $save->icms_origem = $request->icms_origem['codigo'];
        $save->icms_situacao_tributaria = $request->icms_situacao_tributaria['codigo'];

        $save->pis_situacao_tributaria = $request->pis_situacao_tributaria['codigo'];
        $save->cofins_situacao_tributaria = $request->cofins_situacao_tributaria['codigo'];


        $save->token_api = $request->token_api;
        $save->cep = $request->cep;
        $save->logradouro = $request->logradouro;
        $save->complemento = $request->complemento;
        $save->numero = $request->numero;
        $save->bairro = $request->bairro;
        $save->cidade = $request->cidade;
        $save->estado = $request->estado;
        $save->quality_responsable = $request->quality_responsable;
        $save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));
        $save->status = $request->status['value']; 
        $save = collect($save)->toArray();
        DB::table("companies")
            ->insert($save);
        $lastId = DB::getPdo()->lastInsertId();
       
        return redirect()->route('list.company');
    }

    public function edit($companyId)
    {
        $permUser = Auth::user()->hasPermissionTo("edit.companies");
        $permUser2 = Auth::user()->hasPermissionTo("duplicate.companies");
		
        if ((!$permUser) && (!$permUser2)) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        $icms_origem = DB::table("icms_origem")->orderBy('descricao', 'asc')->get();
        $icms_situacao_tributaria = DB::table("icms_situacao_tributaria")->orderBy('descricao', 'asc')->get();

        $pis_situacao_tributaria = DB::table("pis_situacao_tributaria")->orderBy('descricao', 'asc')->get();
        $cofins_situacao_tributaria = DB::table("cofins_situacao_tributaria")->orderBy('descricao', 'asc')->get();
        $usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
        $company = ModelsCompany::findOrFail($companyId);
        return Inertia::render('Company/Edit', [
            'company' => $company,
            'Origem'=>$icms_origem,
            "hasRole" => $usuario != null,
            'Tributaria'=>$icms_situacao_tributaria,
            'Pis' => $pis_situacao_tributaria,
            'Cofins' => $cofins_situacao_tributaria,
        ]);
    }

    public function update(Request $request, $companyId)
    {

     
        $url = null;
        $rules = 'jpg,png';
        $FormatosLiberados = explode(",", $rules);    
        if($request->hasFile('logo_path')){
            if($request->file('logo_path')->isValid()){
                if (in_array($request->file('logo_path')->extension(),$FormatosLiberados)) {
                $ext = $request->file('logo_path')->extension();
                $logo_path = $request->file('logo_path')->store('logo_company/1');
                $url = $logo_path;		
                $url = explode('/',$url);
                $url = $logo_path;
                } else {
                    $ext = $request->file('logo_path')->extension();
                    return redirect()->route("form.update.company",["id"=>$companyId])->withErrors(['msg' => "Atenção o formato enviado na logo foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
                }
            }
        }
   
     
        $save = new stdClass;
        $save->name = $request->name;
        $save->nome_fantasia = $request->nome_fantasia;
        if($request->logo_path){
            $save->logo_path = $url;      
        }    
        $save->cnpj = $request->cnpj;
        $save->insc_estadual = $request->insc_estadual;
        $save->insc_municipal = $request->insc_municipal;
        
        $save->icms_origem = $request->icms_origem['codigo'];
        $save->icms_situacao_tributaria = $request->icms_situacao_tributaria['codigo'];

        $save->pis_situacao_tributaria = $request->pis_situacao_tributaria['codigo'];
        $save->cofins_situacao_tributaria = $request->cofins_situacao_tributaria['codigo'];

        $save->token_api = $request->token_api;
        $save->cep = $request->cep;
        $save->logradouro = $request->logradouro;
        $save->complemento = $request->complemento;
        $save->numero = $request->numero;
        $save->bairro = $request->bairro;
        $save->cidade = $request->cidade;
        $save->estado = $request->estado;
        $save->quality_responsable = $request->quality_responsable;
        $save->status = $request->status['value'];			
        $save = collect($save)->filter(function ($value) {
            return !is_null($value);
        });
        $save = $save->toArray();

        DB::table("companies")
            ->where("token", $companyId)
            ->update($save);

        return redirect()->route('list.company');
    }

    public function delete($companyId)
    {
        
        $permUser = Auth::user()->hasPermissionTo("delete.companies");
		
        if (!$permUser) {
            return redirect()->route("list.Dashboard",['id'=>'1']);
        }

        $Sessoes = Session::all();   
        $NovaEmpresa = '';
        $Empresas = explode(',',$Sessoes['empresa']);
        $total = count($Empresas);
        for($i=0; $i<$total; $i++){
            if($Empresas[$i] != $companyId){
                $NovaEmpresa .= $Empresas[$i].',';
            }
        }
        $NovaEmpresa = substr($NovaEmpresa,0,-1);
                   

        session(['empresa' => $NovaEmpresa]);

        

        ModelsCompany::findOrFail($companyId)
            ->delete();

        return redirect()->route('list.company');
    }
}

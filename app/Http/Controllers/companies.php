<?php

	namespace App\Http\Controllers;
	use Exception;
	use App\Models\DisabledColumns;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;
	use App\Models\Office;
	use Illuminate\Http\Request;
	use Illuminate\Support\Arr;
	use Inertia\Inertia;
	use stdClass;
	
	class companies extends Controller
	{
		public function index(Request $request)
		{
			$Modulo = "Companies";

			$permUser = Auth::user()->hasPermissionTo("list.companies");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}


			try{
				$data = Session::all();
	if(!isset($data["Companies"]) || empty($data["Companies"])){
				session(["Companies" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if($request->input()){
			$Limpar = false;
			if($request->input("limparFiltros") == true){
				$Limpar = true;
			}

			$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["Companies"]);	
			if($arrayFilter){

			session(["Companies" => $arrayFilter]);
			$data = Session::all();
			}
			}
				$columnsTable = DisabledColumns::whereRouteOfList("list.companies")
				->first()
				?->columns;
	
			$companies = DB::table("companies");
			
			if(isset($data["Companies"]["orderBy"])){				
				$Coluna = $data["Companies"]["orderBy"]["column"];			
				$companies =  $companies->orderBy("companies.$Coluna",$data["Companies"]['orderBy']["sorting"] ? "asc" : "desc");
			} else {
				$companies =  $companies->orderBy("companies.created_at", "desc");
			}

	
	
			$companies->when(request("searchBy"), function ($q) {				
				$q->orWhere("name", "like", "%" . request("name") . "%");
				$q->orWhere("logo_path", "like", "%" . request("logo_path") . "%");
				$q->orWhere("cnpj", "like", "%" . request("cnpj") . "%");
				$q->orWhere("quality_responsable", "like", "%" . request("quality_responsable") . "%");
				$q->orWhere("status", "like", "%" . request("status") . "%");
				$q->orWhere("created_at", "like", "%" . request("created_at") . "%");        
			});
	
		    $companies = $companies->where("deleted", "0");
	
			$companies = $companies->paginate(($request->limit ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de Companies";
			$Registros = $this->Registros();
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
	
			return Inertia::render("Companies/List", [
				"columnsTable" => $columnsTable,
				"Registros" => $Registros,
				"companies" => $companies,
				"Filtro" => $data["Companies"],
			]);

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);
			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(403, "Error Found and Sent to LOG de Erros");
        }

		}
		public function Registros()
		{ 
		
			$mes = date("m");
			$Total = DB::table("companies")	
			->where("companies.deleted", "0")
			->count();

			$Ativos = DB::table("companies")	
			->where("companies.deleted", "0")
			->where("companies.status", "0")
			->count();

			$Inativos = DB::table("companies")	
			->where("companies.deleted", "0")
			->where("companies.status", "1")
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
			$Modulo = "Companies";
			$permUser = Auth::user()->hasPermissionTo("create.companies");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}
			try{



			
			$Acao = "Opened the Registration Screen of the Module Companies";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			return Inertia::render("Companies/Create");

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);
			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(505, "Error Found and Sent to LOG de Erros");
        }
		

		}

		public function return_id($id)
		{ 
		
			$companies = DB::table("companies")->where("token", $id)->first();
	
			return $companies->id;
		}
	
		public function store(Request $request)
		{
			$Modulo = "Companies";

			$permUser = Auth::user()->hasPermissionTo("create.companies");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->name = $request->name;
			$save->logo_path = $request->logo_path;
			$save->cnpj = $request->cnpj;
			$save->quality_responsable = $request->quality_responsable;
			$save->status = $request->status;
			$save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));
			$save->status = $request->status; 
			$save = collect($save)->toArray();
			DB::table("companies")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inserted a New Record in the Module of Companies";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->route("list.companies");
			
			} catch (Exception $e) {	
			
				$Error = $e->getMessage();
				$Error = explode("MESSAGE:",$Error);
				
	
				$Pagina = $_SERVER["REQUEST_URI"];
				
				$Erro = $Error[0];
				$Erro_Completo = $e->getMessage();
				$LogsErrors = new logsErrosController; 
				$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
				abort(403, "Error Found and Sent to LOG de Erros");
			}

			return redirect()->route("list.companies");
			
		}
	
		public function edit($IDcompanies)
		{
			$Modulo = "Companies";

			$permUser = Auth::user()->hasPermissionTo("edit.companies");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			$AcaoID = $this->return_id($IDcompanies);
					  
			$companies = DB::table("companies")
			->where("token", $IDcompanies)
			->first();   

			$Acao = "Opened the Module Editing Screen of Companies";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,1);
	
			return Inertia::render("Companies/Edit", [
				"companies" => $companies
			]);

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(403, "Error Found and Sent to LOG de Erros");
		}

		}
	
	
		public function update(Request $request, $id)
		{
		  
			$Modulo = "Companies";

			$permUser = Auth::user()->hasPermissionTo("edit.companies");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}


			try{

				$AcaoID = $this->return_id($id);

				$token = md5(date("Y-m-d H:i:s").rand(0,999999999));
				$save = new stdClass;
				$save->name = $request->name;
				$save->logo_path = $request->logo_path;
				$save->cnpj = $request->cnpj;
				$save->quality_responsable = $request->quality_responsable;
				$save->status = $request->status;
				$save->token = $token;				
				$save = collect($save)->toArray();

				DB::table("companies")
					->where("token", $id)
					->update($save);

			

				$Acao = "Edited a record in the Module of Companies";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,$AcaoID);
				
			return redirect()->route("list.companies");

			} catch (Exception $e) {	
				
				$Error = $e->getMessage();
				$Error = explode("MESSAGE:",$Error);			

				$Pagina = $_SERVER["REQUEST_URI"];
				
				$Erro = $Error[0];
				$Erro_Completo = $e->getMessage();
				$LogsErrors = new logsErrosController; 
				$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
				abort(403, "Error Found and Sent to LOG de Erros");
			}
		}
	
	
		public function delete($IDcompanies)
		{
			$Modulo = "Companies";

			$permUser = Auth::user()->hasPermissionTo("delete.companies");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			DB::table("companies")
				->where("token", $IDcompanies)
				->delete();

			$AcaoID = $this->return_id($IDcompanies);
			
			$Acao = "Deleted a record in the Module of Companies";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
	
			return redirect()->route("list.companies");

		} catch (Exception $e) {	
				
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);

			abort(403, "Error Found and Sent to LOG de Erros");
		}


		}
	}
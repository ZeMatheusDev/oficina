<?php

	namespace App\Http\Controllers;
	use Exception;
	use App\Models\DisabledColumns;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;
    use Illuminate\Database\Query\Builder;
	use App\Models\Office;
	use Illuminate\Http\Request;
	use Illuminate\Support\Arr;
	use Inertia\Inertia;
	use stdClass;
	
	class logsErrosController extends Controller
	{
		public function index(Request $request)
		{
			$Modulo = "LogsErros";

			$permUser = Auth::user()->hasPermissionTo("list.logsErros");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}

			try{
				$data = Session::all();
				if(!isset($data["LogsErros"]) || empty($data["LogsErros"])){
							session(["LogsErros" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
							$data = Session::all();
						}
			
						$Filtros = new Security;
						if($request->input()){
						$Limpar = false;
						if($request->input("limparFiltros") == true){
							$Limpar = true;
						}
			
						$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["LogsErros"]);	
						if($arrayFilter){
			
						session(["LogsErros" => $arrayFilter]);
						$data = Session::all();
						}
						}
				$columnsTable = DisabledColumns::whereRouteOfList("list.logsErros")
				->first()
				?->columns;
	
			$logsErros = DB::table("logs_errors");
			
						


			if(isset($data["logsErros"]["searchBy"])){				
				$AplicaFiltro = $data["logsErros"]["searchBy"];			
				$logsErros = $logsErros->Where("logs_errors.pagina", "like", "%" . $AplicaFiltro . "%");	
				$logsErros = $logsErros->Where("logs_errors.modulo", "like", "%" . $AplicaFiltro . "%");			
				$logsErros = $logsErros->Where("logs_errors.erro", "like", "%" . $AplicaFiltro . "%");	
				$logsErros = $logsErros->Where("logs_errors.erro_completo", "like", "%" . $AplicaFiltro . "%");	
				$logsErros = $logsErros->Where("logs_errors.created_at", "like", "%" . $AplicaFiltro . "%");	
				
			}
			
	
			$logsErros = $logsErros->paginate(($request->limit ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de LogsErros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
	
			return Inertia::render("LogsErros/List", [
				"columnsTable" => $columnsTable,
				"logsErros" => $logsErros,
				"Filtros" => $data["logsErros"],
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
	
		public function create()
		{        
			$Modulo = "LogsErros";
			$permUser = Auth::user()->hasPermissionTo("create.logsErros");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}
			try{

			
			$Acao = "Opened the Registration Screen of the Module LogsErros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			return Inertia::render("LogsErros/Create");

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

		public function return_id($id)
		{ 
			$logsErros = DB::table("logs_errors");
			$logsErros = $logsErros->where("id", $id);

			return $logsErros->id;
		}

		public function RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo)
		{
			$Modulo = "LogsErros";
			
			$data = Session::all();
	
			$save = new stdClass;
			$save->id_usuario = auth()->user()->id;
			$save->pagina = $Pagina;
			$save->modulo = $Modulo;
			$save->erro = $Erro;
			$save->erro_completo = $Erro_Completo;
			$save = collect($save)->toArray();
			DB::table("logs_errors")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inserted a New Record in the Module of LogsErros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			}
	
		public function store(Request $request)
		{
			$Modulo = "LogsErros";

			$permUser = Auth::user()->hasPermissionTo("create.logsErros");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->id_usuario = $data["login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d"];
			$save->pagina = $request->pagina;
			$save->modulo = $request->modulo;
			$save->erro = $request->erro;
			$save->erro_completo = $request->erro_completo;
			$save = collect($save)->toArray();
			DB::table("logs_errors")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inserted a New Record in the Module of LogsErros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->route("list.logsErros");
			
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

			return redirect()->route("list.logsErros");
			
		}
	
		public function edit($IDlogsErros)
		{
			$Modulo = "LogsErros";

			$permUser = Auth::user()->hasPermissionTo("edit.logsErros");
			$permUser2 = Auth::user()->hasPermissionTo("duplicate.LogsErros");
		
			if ((!$permUser) && (!$permUser2)) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}

			try{

			
	
			$AcaoID = $this->return_id($IDlogsErros);
				  
			$logsErros = DB::table("logs_errors")
			->where("id", $IDlogsErros)
			->first();   

			$Acao = "Opened the Module Editing Screen of LogsErros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,$AcaoID);
	
			return Inertia::render("LogsErros/Edit", [
				"logsErros" => $logsErros
			]);

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(101, "Error Found and Sent to LOG de Erros");
		}

		}
	
	
		public function update(Request $request, $id)
		{
		  
			$Modulo = "LogsErros";

			$permUser = Auth::user()->hasPermissionTo("edit.logsErros");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}


			try{

		
	
				$save = new stdClass;
				$save->pagina = $request->pagina;
$save->modulo = $request->modulo;
$save->erro = $request->erro;
$save->erro_completo = $request->erro_completo;


				$save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));
				$save->status = $request->status;
				
				$save = collect($save)->toArray();
		
				DB::table("logs_errors")
					->where("token", $id)
					->update($save);

				$AcaoID = $this->return_id($IDlogsErros);

				$Acao = "Edited a record in the Module of LogsErros";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,$AcaoID);
				
			return redirect()->route("list.logsErros");

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
	
	
		public function delete($IDlogsErros)
		{
			$Modulo = "LogsErros";

			$permUser = Auth::user()->hasPermissionTo("delete.logsErros");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			
	
			DB::table("logs_errors")
				->where("token", $IDlogsErros)
				->delete();

			$AcaoID = $this->return_id($IDlogsErros);
			
			$Acao = "Deleted a record in the Module of LogsErros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
	
			return redirect()->route("list.logsErros");

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
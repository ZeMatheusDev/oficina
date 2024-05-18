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
	
	class logs extends Controller
	{
		public function index(Request $request)
		{
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("list.logs");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}

			try{
				$data = Session::all();
				if(!isset($data["logs"]) || empty($data["logs"])){
							session(["logs" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
							$data = Session::all();
						}
			
						$Filtros = new Security;
						if($request->input()){
						$Limpar = false;
						if($request->input("limparFiltros") == true){
							$Limpar = true;
						}
			
						$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["logs"]);	
						if($arrayFilter){
			
						session(["logs" => $arrayFilter]);
						$data = Session::all();
						}
						}
				$columnsTable = DisabledColumns::whereRouteOfList("list.logsUsuario")
				->first()
				?->columns;
	
			$logsUsuario = DB::table("logs")
			->leftjoin('users as u', 'logs.id_usuario', '=', 'u.id')
			->select(DB::raw("logs.*, DATE_FORMAT(logs.created_at, '%d/%m/%Y') as data_final, u.name as user"));
		
		
				

			if(isset($data["logsUsuario"]["searchBy"])){				
				$AplicaFiltro = $data["logsUsuario"]["searchBy"];			
				$logsUsuario = $logsUsuario->Where("logs.tipo", "like", "%" . $AplicaFiltro . "%");	
				$logsUsuario = $logsUsuario->Where("logs.modulo", "like", "%" . $AplicaFiltro . "%");			
				$logsUsuario = $logsUsuario->Where("logs.acao", "like", "%" . $AplicaFiltro . "%");	
				$logsUsuario = $logsUsuario->Where("logs.id_ref", "like", "%" . $AplicaFiltro . "%");	
		        $logsUsuario = $logsUsuario->Where("logs.created_at", "like", "%" . $AplicaFiltro . "%");	
		

			}



			$logsUsuario = $logsUsuario->where("deleted", "0");
	
			$logsUsuario = $logsUsuario->paginate(($data["logs"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de LogsUsers";
			$Logs = new logs; 
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			
	
			return Inertia::render("LogsUsers/List", [
				"columnsTable" => $columnsTable,
				"logsUsuario" => $logsUsuario,
				"Filtros" => $data["logs"],
				"hasRole" => $usuario != null,
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

		public function RegistraLog($tipo,$Modulo,$Acao,$id=0)
		{
			
			$data = Session::all();
	
			$save = new stdClass;
			$save->id_usuario = auth()->user()->id;
			$save->tipo = $tipo;
			$save->modulo = $Modulo;
			$save->acao = $Acao;
			$save->id_ref = $id;
			$save = collect($save)->toArray();
			DB::table("logs")
				->insert($save);
		}
	
		public function create()
		{        
			$Modulo = "LogsUsers";
			$permUser = Auth::user()->hasPermissionTo("create.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}
			try{

			
			$Acao = "Opened the Registration Screen of the Module LogsUsers";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

			return Inertia::render("LogsUsers/Create", [
				"hasRole" => $usuario != null,

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

		public function return_id($id)
		{ 
			$logsUsuario = DB::table("logs");
			$logsUsuario = $logsUsuario->where("deleted", "0");
			$logsUsuario = $logsUsuario->where("token", $id)->first();

			return $logsUsuario->id;
		}
	
		public function store(Request $request)
		{
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("create.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->id_usuario = auth()->user()->id;$save->tipo = $request->tipo;
$save->modulo = $request->modulo;
$save->acao = $request->acao;
$save->id_ref = $request->id_ref;


			$save = collect($save)->toArray();
			DB::table("logs")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inserted a New Record in the Module of LogsUsers";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->route("list.logsUsuario");
			
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

			return redirect()->route("list.logsUsuario");
			
		}
	
		public function storeAjax(Request $request)
		{
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("create.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->id_usuario = auth()->user()->id;$save->tipo = $request->tipo;
$save->modulo = $request->modulo;
$save->acao = $request->acao;
$save->id_ref = $request->id_ref;

			
			$save = collect($save)->toArray();
			DB::table("logs")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inserted a New Record in the Module of LogsUsers";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->back();
			
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

			return redirect()->route("list.logsUsuario");
			
		}


		public function edit($IDlogsUsuario)
		{
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("edit.logs");
		
			$permUser2 = Auth::user()->hasPermissionTo("duplicate.Logs");
		
			if ((!$permUser) && (!$permUser2)) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}
			try{

			
	
			$AcaoID = $this->return_id($IDlogsUsuario);
				  
			$logsUsuario = DB::table("logs")
			->where("token", $IDlogsUsuario)
			->first();   

			$Acao = "Opened the Module Editing Screen of LogsUsers";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,$AcaoID);
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

			return Inertia::render("LogsUsers/Edit", [
				"logsUsuario" => $logsUsuario,
				"hasRole" => $usuario != null,
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
		  
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("edit.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}


			try{


				$AcaoID = $this->return_id($id);
		
	
				$save = new stdClass;
				$save->tipo = $request->tipo;
$save->modulo = $request->modulo;
$save->acao = $request->acao;
$save->id_ref = $request->id_ref;

				
				$save = collect($save)->toArray();
		
				DB::table("logs")
					->where("token", $id)
					->update($save);

				

				$Acao = "Edited a record in the Module of LogsUsers";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,$AcaoID);
				
			return redirect()->route("list.logsUsuario");

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


		public function updateAjax(Request $request, $id)
		{
		  
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("edit.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}


			try{

				$save = $request->all();
				$save = collect($save)->toArray();
				
				$save = $save->toArray();
		
				DB::table("logs")
					->where("id", $id)
					->update($save);
			
				$Acao = "Edited a record in the Module of LogsUsers";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,$id);
				
				return redirect()->back();

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
	
	
		public function delete($IDlogsUsuario)
		{
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("delete.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{

			$AcaoID = $this->return_id($IDlogsUsuario);
	
			DB::table("logs")
				->where("token", $IDlogsUsuario)
				->delete();

		
			
			$Acao = "Deleted a record in the Module of LogsUsers";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
	
			return redirect()->route("list.logsUsuario");

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

		public function deleteAjax($id)
		{
			$Modulo = "LogsUsers";

			$permUser = Auth::user()->hasPermissionTo("delete.logs");
		
			if (!$permUser) {
				abort(403, "Unauthorized user.");
			}

			try{		
	
			DB::table("logs")
				->where("id", $id)
				->delete();
					
			$Acao = "Deleted a record in the Module of LogsUsers";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$id);
	
			return redirect()->back();

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
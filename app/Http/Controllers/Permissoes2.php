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
	
	class Permissoes2 extends Controller
	{
		public function index(Request $request)
		{
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("list.Permissoes2");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{
				$data = Session::all();
				if(!isset($data["Permissoes2"]) || empty($data["Permissoes2"])){
							session(["Permissoes2" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
							$data = Session::all();
						}
			
						$Filtros = new Security;
						if($request->input()){
						$Limpar = false;
						if($request->input("limparFiltros") == true){
							$Limpar = true;
						}
			
						$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["Permissoes2"]);	
						if($arrayFilter){
			
						session(["Permissoes2" => $arrayFilter]);
						$data = Session::all();
						}
						}
				$columnsTable = DisabledColumns::whereRouteOfList("list.Permissoes2")
				->first()
				?->columns;
	
			$Permissoes2 = DB::table("roles")
			->select(DB::raw("*, DATE_FORMAT(created_at, '%d/%m/%Y') as data_final"));
			
if(isset($data["Permissoes2"]["orderBy"])){				
	$Coluna = $data["Permissoes2"]["orderBy"]["column"];			
	$Permissoes2 =  $Permissoes2->orderBy("roles.$Coluna",$data["Permissoes2"]['orderBy']["sorting"] ? "asc" : "desc");
} else {
	$Permissoes2 =  $Permissoes2->orderBy("roles.created_at", "desc");
}

	
			if(isset($data["Permissoes2"]["searchBy"])){				
				$AplicaFiltro = $data["Permissoes2"]["searchBy"];			
				$Permissoes2 = $Permissoes2->Where("roles.name", "like", "%" . $AplicaFiltro . "%");	
				$Permissoes2 = $Permissoes2->Where("roles.guard_name", "like", "%" . $AplicaFiltro . "%");			
				$Permissoes2 = $Permissoes2->Where("roles.status", "like", "%" . $AplicaFiltro . "%");	
				$Permissoes2 = $Permissoes2->Where("roles.created_at", "like", "%" . $AplicaFiltro . "%");	
				
				

			}




			$Permissoes2 = $Permissoes2->where("deleted", "0");
	
			$Permissoes2 = $Permissoes2->paginate(($request->limit ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de Permissoes2";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
	
			return Inertia::render("Permissoes2/List", [
				"columnsTable" => $columnsTable,
				"Permissoes2" => $Permissoes2,
				"Filtros" => $data["Permissoes2"],
			]);

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);
			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(403, "Erro localizado e enviado ao LOG de Erros");
        }

		}
	
		public function create()
		{        
			$Modulo = "Permissoes2";
			$permUser = Auth::user()->hasPermissionTo("create.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}
			try{

			
			$Acao = "Abriu a Tela de Cadastro do Módulo de Permissoes2";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			return Inertia::render("Permissoes2/Create");

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);
			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(403, "Erro localizado e enviado ao LOG de Erros");
        }
		

		}

		public function return_id($id)
		{ 
			$Permissoes2 = DB::table("roles");
			$Permissoes2 = $Permissoes2->where("deleted", "0");
			$Permissoes2 = $Permissoes2->where("token", $id)->first();

			return $Permissoes2->id;
		}
	
		public function store(Request $request)
		{
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("create.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->name = $request->name;
$save->guard_name = $request->guard_name;
$save->status = $request->status;


			$save = collect($save)->toArray();
			DB::table("roles")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inseriu um Novo Registro no Módulo de Permissoes2";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->route("list.Permissoes2");
			
			} catch (Exception $e) {	
			
				$Error = $e->getMessage();
				$Error = explode("MESSAGE:",$Error);
				
	
				$Pagina = $_SERVER["REQUEST_URI"];
				
				$Erro = $Error[0];
				$Erro_Completo = $e->getMessage();
				$LogsErrors = new logsErrosController; 
				$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
				abort(403, "Erro localizado e enviado ao LOG de Erros");
			}

			return redirect()->route("list.Permissoes2");
			
		}
	
		public function storeAjax(Request $request)
		{
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("create.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->name = $request->name;
$save->guard_name = $request->guard_name;
$save->status = $request->status;

			
			$save = collect($save)->toArray();
			DB::table("roles")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inseriu um Novo Registro no Módulo de Permissoes2";
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
				abort(403, "Erro localizado e enviado ao LOG de Erros");
			}

			return redirect()->route("list.Permissoes2");
			
		}


		public function edit($IDPermissoes2)
		{
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("edit.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{

			
	
			$AcaoID = $this->return_id($IDPermissoes2);
				  
			$Permissoes2 = DB::table("roles")
			->where("token", $IDPermissoes2)
			->first();   

			$Acao = "Abriu a Tela de Edição do Módulo de Permissoes2";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,$AcaoID);
	
			return Inertia::render("Permissoes2/Edit", [
				"Permissoes2" => $Permissoes2
			]);

		} catch (Exception $e) {	
			
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
			abort(403, "Erro localizado e enviado ao LOG de Erros");
		}

		}
	
	
		public function update(Request $request, $id)
		{
		  
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("edit.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}


			try{


				$AcaoID = $this->return_id($id);
		
	
				$save = new stdClass;
				$save->name = $request->name;
$save->guard_name = $request->guard_name;
$save->status = $request->status;

				
				$save = collect($save)->toArray();
		
				DB::table("roles")
					->where("token", $id)
					->update($save);

				

				$Acao = "Editou um registro no Módulo de Permissoes2";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,$AcaoID);
				
			return redirect()->route("list.Permissoes2");

			} catch (Exception $e) {	
				
				$Error = $e->getMessage();
				$Error = explode("MESSAGE:",$Error);			

				$Pagina = $_SERVER["REQUEST_URI"];
				
				$Erro = $Error[0];
				$Erro_Completo = $e->getMessage();
				$LogsErrors = new logsErrosController; 
				$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);
				abort(403, "Erro localizado e enviado ao LOG de Erros");
			}
		}


		public function updateAjax(Request $request, $id)
		{
		  
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("edit.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}


			try{

				$save = $request->all();
				$save = collect($save)->toArray();
				
				$save = $save->toArray();
		
				DB::table("roles")
					->where("id", $id)
					->update($save);
			
				$Acao = "Editou um registro no Módulo de Permissoes2";
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
				abort(403, "Erro localizado e enviado ao LOG de Erros");
			}
		}
	
	
		public function delete($IDPermissoes2)
		{
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("delete.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{

			$AcaoID = $this->return_id($IDPermissoes2);
	
			DB::table("roles")
				->where("token", $IDPermissoes2)
				->delete();

		
			
			$Acao = "Excluiu um registro no Módulo de Permissoes2";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
	
			return redirect()->route("list.Permissoes2");

		} catch (Exception $e) {	
				
			$Error = $e->getMessage();
			$Error = explode("MESSAGE:",$Error);			

			$Pagina = $_SERVER["REQUEST_URI"];
			
			$Erro = $Error[0];
			$Erro_Completo = $e->getMessage();
			$LogsErrors = new logsErrosController; 
			$Registra = $LogsErrors->RegistraErro($Pagina,$Modulo,$Erro,$Erro_Completo);

			abort(403, "Erro localizado e enviado ao LOG de Erros");
		}


		}

		public function deleteAjax($id)
		{
			$Modulo = "Permissoes2";

			$permUser = Auth::user()->hasPermissionTo("delete.Permissoes2");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{		
	
			DB::table("roles")
				->where("id", $id)
				->delete();
					
			$Acao = "Excluiu um registro no Módulo de Permissoes2";
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

			abort(403, "Erro localizado e enviado ao LOG de Erros");
		}


		}
	}
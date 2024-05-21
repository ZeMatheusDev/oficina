<?php

	namespace App\Http\Controllers;
	use Exception;
	use App\Models\DisabledColumns;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;
	use App\Models\Office;
	use Illuminate\Http\Request;
	use App\Models\aluguelCarro;
	use App\Models\HistoricoAluguelCarro;
	use App\Models\ConfigCarross;
	use App\Models\vendaCarros;
	use Carbon\Carbon;
	use Illuminate\Support\Arr;
	use Inertia\Inertia;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use Illuminate\Support\Facades\Storage;
	use stdClass;
	
	class ConfigCarros extends Controller
	{
		public function index(Request $request)
		{
			
			$Modulo = "ConfigCarros";

			$dataDeHoje = Carbon::now();

			$carrosAlugados = DB::table('aluguel_carros')->get();
	
			foreach($carrosAlugados as $carro){
				$fimAluguel = Carbon::createFromFormat('d/m/Y', $carro->fim_aluguel);
				if($fimAluguel < $dataDeHoje){
					$historico = new HistoricoAluguelCarro();
					$historico->carro_id = $carro->carro_id;
					$historico->user_id = $carro->user_id;
					$historico->inicio_aluguel = $carro->inicio_aluguel;
					$historico->fim_aluguel = $carro->fim_aluguel;
					$historico->valor_total = $carro->valor_total;
					$historico->save();
					ConfigCarross::where('id', $carro->carro_id)->update(['alugado' => 0]);
					AluguelCarro::where('id', $carro->id)->delete();
				}
			}
			
			$permUser = Auth::user()->hasPermissionTo("list.ConfigCarros");

			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{

			

			$data = Session::all();

			if(!isset($data["ConfigCarros"]) || empty($data["ConfigCarros"])){
				session(["ConfigCarros" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if($request->input()){
			$Limpar = false;
			if($request->input("limparFiltros") == true){
				$Limpar = true;
			}

			$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigCarros"]);	
			if($arrayFilter){
			session(["ConfigCarros" => $arrayFilter]);
			$data = Session::all();
			}
			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigCarros")
				->first()
				?->columns;
	
			$ConfigCarros = DB::table("config_carros")
			
			->select(DB::raw("config_carros.*, DATE_FORMAT(config_carros.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));
	
			if(isset($data["ConfigCarros"]["orderBy"])){				
				$Coluna = $data["ConfigCarros"]["orderBy"]["column"];			
				$ConfigCarros =  $ConfigCarros->orderBy("config_carros.$Coluna",$data["ConfigCarros"]["orderBy"]["sorting"] ? "asc" : "desc");
			} else {
				$ConfigCarros =  $ConfigCarros->orderBy("config_carros.created_at", "desc");
			}
			
			
			
if(isset($data["ConfigCarros"]["modelo"])){				
					$AplicaFiltro = $data["ConfigCarros"]["modelo"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.modelo",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["placa"])){				
					$AplicaFiltro = $data["ConfigCarros"]["placa"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.placa",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["marca"])){				
					$AplicaFiltro = $data["ConfigCarros"]["marca"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.marca",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["ano"])){				
					$AplicaFiltro = $data["ConfigCarros"]["ano"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.ano",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["cor"])){				
					$AplicaFiltro = $data["ConfigCarros"]["cor"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.cor",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_compra"])){				
					$AplicaFiltro = $data["ConfigCarros"]["valor_compra"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.valor_compra",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_para_venda"])){				
	$AplicaFiltro = $data["ConfigCarros"]["valor_para_venda"];			
	$ConfigCarros = $ConfigCarros->Where("config_carros.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");			
}
if(isset($data["ConfigCarros"]["observacao"])){				
					$AplicaFiltro = $data["ConfigCarros"]["observacao"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.observacao",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["status"])){				
					$AplicaFiltro = $data["ConfigCarros"]["status"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.status",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["created_at"])){				
					$AplicaFiltro = $data["ConfigCarros"]["created_at"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.created_at",  "like", "%" . $AplicaFiltro . "%");			
				}
	
			$ConfigCarros = $ConfigCarros->where("config_carros.deleted", "0");
	
			$ConfigCarros = $ConfigCarros->paginate(($data["ConfigCarros"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

			return Inertia::render("ConfigCarros/List", [
				"columnsTable" => $columnsTable,
				"ConfigCarros" => $ConfigCarros,
				"hasRole" => $usuario != null,
				"Filtros" => $data["ConfigCarros"],
				"Registros" => $Registros,

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

		public function telaCompraCarro(Request $request){
			$tokenDoCarro = $request->route('id');
			$carro = DB::table('config_carros')->where('token', $tokenDoCarro)->first();
			$carro_modelo = $carro->modelo;
			$carro_id = $carro->id;
			$hasRole = session('hasRole');
			$usuario = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
			$usuario_id = $usuario->id;
			$usuario_nome = $usuario->name;
			$Users = DB::table('users')->get();

			return Inertia::render("telaCompraCarro", [
				'hasRole' => $hasRole,
				'usuario_id' => $usuario_id,
				'usuario_nome' => $usuario_nome,
				'Users' => $Users,
				'carro_modelo' => $carro_modelo,
				'valor_para_venda' => $carro->valor_para_venda,
				'categoria' => $usuario->role_id,	
				'carro_id' => $carro_id,
			]);
		}

		public function comprandoCarro($IDConfigCarros){
		
			$Modulo = "ConfigCarros";
	
			try {
				$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
				return redirect()->route('telaCompraCarro', ['id' => $IDConfigCarros])
				->with(['carroId' => $IDConfigCarros, 'hasRole' => $usuario != null]);
			} catch (Exception $e) {
	
				$Error = $e->getMessage();
				$Error = explode("MESSAGE:", $Error);
	
				$Pagina = $_SERVER["REQUEST_URI"];
	
				$Erro = $Error[0];
				$Erro_Completo = $e->getMessage();
				$LogsErrors = new logsErrosController;
				$Registra = $LogsErrors->RegistraErro($Pagina, $Modulo, $Erro, $Erro_Completo);
	
				abort(403, "Erro localizado e enviado ao LOG de Erros");
			}
		
		}
		
		public function buscarDadosAluguel($usuario_id)
		{
			$alugueisCarros = DB::table('aluguel_carros')
				->join('users', 'aluguel_carros.user_id', '=', 'users.id')
				->join('config_carros', 'aluguel_carros.carro_id', '=', 'config_carros.id')
				->where('aluguel_carros.user_id', $usuario_id)
				->select('aluguel_carros.*', 'users.name as user_name', 'config_carros.modelo as modelo', 'config_carros.placa as placa', 'carro_id AS veiculo_id')
				->addSelect(DB::raw("'carro' AS veiculo"))
				->get();

			$alugueisMotos = DB::table('aluguel_motos')
				->join('users', 'aluguel_motos.user_id', '=', 'users.id')
				->join('config_motos', 'aluguel_motos.moto_id', '=', 'config_motos.id')
				->where('aluguel_motos.user_id', $usuario_id)
				->select('aluguel_motos.*', 'config_motos.modelo as modelo', 'config_motos.placa as placa', 'users.name as user_name', 'moto_id AS veiculo_id')
				->addSelect(DB::raw("'moto' AS veiculo"))
				->get();
			$alugueis = $alugueisCarros->concat($alugueisMotos);
			return response()->json($alugueis);
		}

		public function buscarDadosCompra($usuario_id)
		{
			$comprasCarros = DB::table('venda_carros')
				->join('users', 'venda_carros.user_id', '=', 'users.id')
				->join('config_carros', 'venda_carros.carro_id', '=', 'config_carros.id')
				->where('venda_carros.user_id', $usuario_id)
				->select('venda_carros.*', 'users.name as user_name', 'config_carros.modelo as modelo', 'config_carros.valor_compra as valor_compra', 'config_carros.placa as placa', 'carro_id AS veiculo_id')
				->addSelect(DB::raw("'carro' AS veiculo"))
				->get();

			$comprasMotos = DB::table('venda_motos')
				->join('users', 'venda_motos.user_id', '=', 'users.id')
				->join('config_motos', 'venda_motos.moto_id', '=', 'config_motos.id')
				->where('venda_motos.user_id', $usuario_id)
				->select('venda_motos.*', 'config_motos.modelo as modelo', 'config_motos.valor_compra as valor_compra', 'config_motos.placa as placa', 'users.name as user_name', 'moto_id AS veiculo_id')
				->addSelect(DB::raw("'moto' AS veiculo"))
				->get();
			$compras = $comprasCarros->concat($comprasMotos);
			return response()->json($compras);
		}

		public function vendaCarros(Request $request){
						
			$Modulo = "ConfigCarros";


			try{

			

			$data = Session::all();

			if(!isset($data["ConfigCarros"]) || empty($data["ConfigCarros"])){
				session(["ConfigCarros" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if($request->input()){
			$Limpar = false;
			if($request->input("limparFiltros") == true){
				$Limpar = true;
			}

			$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigCarros"]);	
			if($arrayFilter){
			session(["ConfigCarros" => $arrayFilter]);
			$data = Session::all();
			}
			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigCarros")
				->first()
				?->columns;
	
			$ConfigCarros = DB::table("config_carros")
			
			->select(DB::raw("config_carros.*, DATE_FORMAT(config_carros.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));
	
			if(isset($data["ConfigCarros"]["orderBy"])){				
				$Coluna = $data["ConfigCarros"]["orderBy"]["column"];			
				$ConfigCarros =  $ConfigCarros->orderBy("config_carros.$Coluna",$data["ConfigCarros"]["orderBy"]["sorting"] ? "asc" : "desc");
			} else {
				$ConfigCarros =  $ConfigCarros->orderBy("config_carros.created_at", "desc");
			}
			
			
			
if(isset($data["ConfigCarros"]["modelo"])){				
					$AplicaFiltro = $data["ConfigCarros"]["modelo"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.modelo",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["placa"])){				
					$AplicaFiltro = $data["ConfigCarros"]["placa"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.placa",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["marca"])){				
					$AplicaFiltro = $data["ConfigCarros"]["marca"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.marca",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["ano"])){				
					$AplicaFiltro = $data["ConfigCarros"]["ano"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.ano",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["cor"])){				
					$AplicaFiltro = $data["ConfigCarros"]["cor"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.cor",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_compra"])){				
					$AplicaFiltro = $data["ConfigCarros"]["valor_compra"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.valor_compra",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_para_venda"])){				
	$AplicaFiltro = $data["ConfigCarros"]["valor_para_venda"];			
	$ConfigCarros = $ConfigCarros->Where("config_carros.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");			
}
if(isset($data["ConfigCarros"]["observacao"])){				
					$AplicaFiltro = $data["ConfigCarros"]["observacao"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.observacao",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["status"])){				
					$AplicaFiltro = $data["ConfigCarros"]["status"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.status",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["created_at"])){				
					$AplicaFiltro = $data["ConfigCarros"]["created_at"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.created_at",  "like", "%" . $AplicaFiltro . "%");			
				}
	
			$ConfigCarros = $ConfigCarros->where("config_carros.alugado", "0")->where("config_carros.deleted", "0")->where("config_carros.vendido", "0");

	
			$ConfigCarros = $ConfigCarros->paginate(($data["ConfigCarros"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			
			return Inertia::render("vendaCarros", [
				"columnsTable" => $columnsTable,
				"ConfigCarros" => $ConfigCarros,
				"hasRole" => $usuario != null,
				"Filtros" => $data["ConfigCarros"],
				"Registros" => $Registros,

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

		public function Registros()
		{ 
		
			$mes = date("m");
			$Total = DB::table("config_carros")	
			->where("config_carros.deleted", "0")
			->count();

			$Ativos = DB::table("config_carros")	
			->where("config_carros.deleted", "0")
			->where("config_carros.status", "0")
			->count();

			$Inativos = DB::table("config_carros")	
			->where("config_carros.deleted", "0")
			->where("config_carros.status", "1")
			->count();

			$EsseMes = DB::table("config_carros")	
			->where("config_carros.deleted", "0")
			->whereMonth("config_carros.created_at", $mes)
			->count();


			$data = new stdClass;
			$data->total = number_format($Total, 0, ",", ".");
			$data->ativo = number_format($Ativos, 0, ",", ".");
			$data->inativo = number_format($Inativos, 0, ",", ".");
			$data->mes = number_format($EsseMes, 0, ",", ".");
			return $data;


		}

		public function compradoCarros(Request $request){
			ConfigCarross::where('id', $request->carro_id)->update(['vendido' => 1]);
			$verificarValorCompra = DB::table('config_carros')->where('id', $request->carro_id)->first();
			$valorFormatado = explode('$', $request->valor);
			$lucro = intval($valorFormatado[1]) - intval($verificarValorCompra->valor_compra);
			$venda = new vendaCarros();
			$venda->carro_id = $request->carro_id;
			$venda->user_id = $request->usuario_id;
			$venda->lucro = $lucro;
			$venda->save();
			return redirect()->route('home');
		}
	
		public function create()
		{        
			$Modulo = "ConfigCarros";
			$permUser = Auth::user()->hasPermissionTo("create.ConfigCarros");
		
			if (!$permUser) {
					return redirect()->route("list.Dashboard",["id"=>"1"]);
			}
			try{			

			$Acao = "Abriu a Tela de Cadastro do Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

			return Inertia::render("ConfigCarros/Create",[
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
			abort(403, "Erro localizado e enviado ao LOG de Erros");
        }
		

		}

		public function return_id($id)
		{ 
			$ConfigCarros = DB::table("config_carros");
			$ConfigCarros = $ConfigCarros->where("deleted", "0");
			$ConfigCarros = $ConfigCarros->where("token", $id)->first();

			return $ConfigCarros->id;
		}

		public function aluguelCarros(Request $request){
			$Modulo = "ConfigCarros";
			

			try{
				$dataDeHoje = Carbon::now();

				$carrosAlugados = DB::table('aluguel_carros')->get();
		
				foreach($carrosAlugados as $carro){
					$fimAluguel = Carbon::createFromFormat('d/m/Y', $carro->fim_aluguel);
					if($fimAluguel < $dataDeHoje){
						$historico = new HistoricoAluguelCarro();
						$historico->carro_id = $carro->carro_id;
						$historico->user_id = $carro->user_id;
						$historico->inicio_aluguel = $carro->inicio_aluguel;
						$historico->fim_aluguel = $carro->fim_aluguel;
						$historico->valor_total = $carro->valor_total;
						$historico->save();
						ConfigCarross::where('id', $carro->carro_id)->update(['alugado' => 0]);
						AluguelCarro::where('id', $carro->id)->delete();
					}
				}
				

			$data = Session::all();

			if(!isset($data["ConfigCarros"]) || empty($data["ConfigCarros"])){
				session(["ConfigCarros" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if($request->input()){
			$Limpar = false;
			if($request->input("limparFiltros") == true){
				$Limpar = true;
			}

			$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigCarros"]);	
			if($arrayFilter){
			session(["ConfigCarros" => $arrayFilter]);
			$data = Session::all();
			}
			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigCarros")
				->first()
				?->columns;
	
			$ConfigCarros = DB::table("config_carros")
			
			->select(DB::raw("config_carros.*, DATE_FORMAT(config_carros.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));
	
			if(isset($data["ConfigCarros"]["orderBy"])){				
				$Coluna = $data["ConfigCarros"]["orderBy"]["column"];			
				$ConfigCarros =  $ConfigCarros->orderBy("config_carros.$Coluna",$data["ConfigCarros"]["orderBy"]["sorting"] ? "asc" : "desc");
			} else {
				$ConfigCarros =  $ConfigCarros->orderBy("config_carros.created_at", "desc");
			}
			
			
			
if(isset($data["ConfigCarros"]["modelo"])){				
					$AplicaFiltro = $data["ConfigCarros"]["modelo"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.modelo",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["placa"])){				
					$AplicaFiltro = $data["ConfigCarros"]["placa"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.placa",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["marca"])){				
					$AplicaFiltro = $data["ConfigCarros"]["marca"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.marca",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["ano"])){				
					$AplicaFiltro = $data["ConfigCarros"]["ano"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.ano",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["cor"])){				
					$AplicaFiltro = $data["ConfigCarros"]["cor"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.cor",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_compra"])){				
					$AplicaFiltro = $data["ConfigCarros"]["valor_compra"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.valor_compra",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_para_venda"])){				
	$AplicaFiltro = $data["ConfigCarros"]["valor_para_venda"];			
	$ConfigCarros = $ConfigCarros->Where("config_carros.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");			
}
if(isset($data["ConfigCarros"]["observacao"])){				
					$AplicaFiltro = $data["ConfigCarros"]["observacao"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.observacao",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["status"])){				
					$AplicaFiltro = $data["ConfigCarros"]["status"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.status",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["created_at"])){				
					$AplicaFiltro = $data["ConfigCarros"]["created_at"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.created_at",  "like", "%" . $AplicaFiltro . "%");			
				}
	
			$ConfigCarros = $ConfigCarros->where("config_carros.alugado", "0")->where("config_carros.deleted", "0")->where("config_carros.vendido", "0");
	
			$ConfigCarros = $ConfigCarros->paginate(($data["ConfigCarros"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
	
			return Inertia::render("aluguelCarros", [
				"columnsTable" => $columnsTable,
				"ConfigCarros" => $ConfigCarros,
				"hasRole" => $usuario != null,
				"Filtros" => $data["ConfigCarros"],
				"Registros" => $Registros,

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

		public function minhasCompras(Request $request){
			$Modulo = "ConfigCarros";
			$data = Session::all();
			if (!isset($data["ConfigCarros"]) || empty($data["ConfigCarros"])) {
				session(["ConfigCarros" => ["status" => "0", "orderBy" => ["column" => "created_at", "sorting" => "1"], "limit" => "10"]]);
				$data = Session::all();
			}
			$Filtros = new Security;
			if ($request->input()) {
				$Limpar = false;
				if ($request->input("limparFiltros") == true) {
					$Limpar = true;
				}
				$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigCarros"]); 
				if ($arrayFilter) {
					session(["ConfigCarros" => $arrayFilter]);
					$data = Session::all();
				}
			}
		
			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigCarros")->first()?->columns;
		
			$ConfigCarros = DB::table("config_carros")
				->select(DB::raw("config_carros.*, DATE_FORMAT(config_carros.created_at, '%d/%m/%Y - %H:%i:%s') as data_final"));
		
			if (isset($data["ConfigCarros"]["orderBy"])) {               
				$Coluna = $data["ConfigCarros"]["orderBy"]["column"];         
				$ConfigCarros = $ConfigCarros->orderBy("config_carros.$Coluna", $data["ConfigCarros"]["orderBy"]["sorting"] ? "asc" : "desc");
			} else {
				$ConfigCarros = $ConfigCarros->orderBy("config_carros.created_at", "desc");
			}
		
			$filters = ['modelo', 'placa', 'marca', 'ano', 'cor', 'valor_compra', 'valor_para_venda', 'observacao', 'status', 'created_at'];
			foreach ($filters as $filter) {
				if (isset($data["ConfigCarros"][$filter])) {
					$AplicaFiltro = $data["ConfigCarros"][$filter];
					$ConfigCarros = $ConfigCarros->where("config_carros.$filter", "like", "%" . $AplicaFiltro . "%");
				}
			}
		
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$user = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
			$usuario_id = $user->id;
			$Users = DB::table('users')->get();
			$usuario_nome = $user->name;
			$Logs = new logs;
			$Acao = "Acessou a listagem do Módulo de ConfigCarros";
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$Registros = $this->Registros();
			$data = Session::all();
		
			$minhasComprasCarros = DB::table('venda_carros')
				->where('user_id', $usuario_id)
				->join('config_carros', 'config_carros.id', 'venda_carros.carro_id')
				->select('venda_carros.*', 'config_carros.*', DB::raw("'carro' as veiculo"))
				->get();
		
			$minhasComprasMotos = DB::table('venda_motos')
				->where('user_id', $usuario_id)
				->join('config_motos', 'config_motos.id', 'venda_motos.moto_id')
				->select('venda_motos.*', 'config_motos.*', DB::raw("'moto' as veiculo"))
				->get();
		
			$minhasCompras = $minhasComprasCarros->concat($minhasComprasMotos);
		
			$perPage = $data["ConfigCarros"]["limit"] ?: 10;
			$currentPage = LengthAwarePaginator::resolveCurrentPage();
			$currentResults = $minhasCompras->slice(($currentPage - 1) * $perPage, $perPage)->all();
			$paginatedCompras = new LengthAwarePaginator($currentResults, $minhasCompras->count(), $perPage, $currentPage, [
				'path' => LengthAwarePaginator::resolveCurrentPath(),
				'pageName' => 'page',
			]);
			$paginatedCompras->appends($request->except('page'));
			return Inertia::render("minhasCompras", [
				"hasRole" => $usuario != null,
				'categoria' => $user->role_id,    
				'usuario_nome' => $usuario_nome,
				'usuario_id' => $usuario_id,
				'Users' => $Users,
				"Filtros" => $data["ConfigCarros"],
				'minhasCompras' => $paginatedCompras,
				"Registros" => $Registros,
			]);
		}

		public function meusAlugueis(Request $request)
		{
			$Modulo = "ConfigCarros";
			$data = Session::all();
			if (!isset($data["ConfigCarros"]) || empty($data["ConfigCarros"])) {
				session(["ConfigCarros" => ["status" => "0", "orderBy" => ["column" => "created_at", "sorting" => "1"], "limit" => "10"]]);
				$data = Session::all();
			}
			$Filtros = new Security;
			if ($request->input()) {
				$Limpar = false;
				if ($request->input("limparFiltros") == true) {
					$Limpar = true;
				}
				$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigCarros"]); 
				if ($arrayFilter) {
					session(["ConfigCarros" => $arrayFilter]);
					$data = Session::all();
				}
			}
		
			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigCarros")->first()?->columns;
		
			$ConfigCarros = DB::table("config_carros")
				->select(DB::raw("config_carros.*, DATE_FORMAT(config_carros.created_at, '%d/%m/%Y - %H:%i:%s') as data_final"));
		
			if (isset($data["ConfigCarros"]["orderBy"])) {               
				$Coluna = $data["ConfigCarros"]["orderBy"]["column"];         
				$ConfigCarros = $ConfigCarros->orderBy("config_carros.$Coluna", $data["ConfigCarros"]["orderBy"]["sorting"] ? "asc" : "desc");
			} else {
				$ConfigCarros = $ConfigCarros->orderBy("config_carros.created_at", "desc");
			}
		
			$filters = ['modelo', 'placa', 'marca', 'ano', 'cor', 'valor_compra', 'valor_para_venda', 'observacao', 'status', 'created_at'];
			foreach ($filters as $filter) {
				if (isset($data["ConfigCarros"][$filter])) {
					$AplicaFiltro = $data["ConfigCarros"][$filter];
					$ConfigCarros = $ConfigCarros->where("config_carros.$filter", "like", "%" . $AplicaFiltro . "%");
				}
			}
		
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$user = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
			$usuario_id = $user->id;
			$Users = DB::table('users')->get();
			$usuario_nome = $user->name;
			$Logs = new logs;
			$Acao = "Acessou a listagem do Módulo de ConfigCarros";
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$Registros = $this->Registros();
			$data = Session::all();
		
			$meusAlugueisCarros = DB::table('aluguel_carros')
				->where('user_id', $usuario_id)
				->join('config_carros', 'config_carros.id', 'aluguel_carros.carro_id')
				->select('aluguel_carros.*', 'config_carros.*', DB::raw("'carro' as veiculo"))
				->get();
		
			$meusAlugueisMotos = DB::table('aluguel_motos')
				->where('user_id', $usuario_id)
				->join('config_motos', 'config_motos.id', 'aluguel_motos.moto_id')
				->select('aluguel_motos.*', 'config_motos.*', DB::raw("'moto' as veiculo"))
				->get();
		
			$meusAlugueis = $meusAlugueisCarros->concat($meusAlugueisMotos);
		
			$perPage = $data["ConfigCarros"]["limit"] ?: 10;
			$currentPage = LengthAwarePaginator::resolveCurrentPage();
			$currentResults = $meusAlugueis->slice(($currentPage - 1) * $perPage, $perPage)->all();
			$paginatedAlugueis = new LengthAwarePaginator($currentResults, $meusAlugueis->count(), $perPage, $currentPage, [
				'path' => LengthAwarePaginator::resolveCurrentPath(),
				'pageName' => 'page',
			]);
		
			$paginatedAlugueis->appends($request->except('page'));

			return Inertia::render("meusAlugueis", [
				"hasRole" => $usuario != null,
				'categoria' => $user->role_id,    
				'usuario_nome' => $usuario_nome,
				'usuario_id' => $usuario_id,
				'Users' => $Users,
				"Filtros" => $data["ConfigCarros"],
				'meusAlugueis' => $paginatedAlugueis,
				"Registros" => $Registros,
			]);
		}
		

		public function telaAluguel(Request $request){
			$tokenDoCarro = $request->route('id');
			$carro = DB::table('config_carros')->where('token', $tokenDoCarro)->first();
			$carro_modelo = $carro->modelo;
			$carro_id = $carro->id;
			$hasRole = session('hasRole');
			$usuario = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
			$Users = DB::table('users')->get();
			$usuario_id = $usuario->id;
			$usuario_nome = $usuario->name;
			return Inertia::render("telaAluguelCarros", [
				'hasRole' => $hasRole,
				'usuario_id' => $usuario_id,
				'categoria' => $usuario->role_id,	
				'Users' => $Users,
				'usuario_nome' => $usuario_nome,
				'carro_modelo' => $carro_modelo,
				'valor_diaria' => $carro->valor_diaria,
				'carro_id' => $carro_id,
			]);
		}

		public function alugado(Request $request){
			$valorFormatado = explode('$', $request->valor);
			$carro_id = $request->carro_id;
			$date = Carbon::parse($request->inicio_aluguel)->format('d/m/Y');
			$dataInicialParaSomar = Carbon::parse($request->inicio_aluguel);
			$diasParaAdicionar = ((int)$request->dias);
			$dataFinal = $dataInicialParaSomar->addDays($diasParaAdicionar);
			$dataFinalFormatada = $dataFinal->format('d/m/Y');
			$aluguel = new AluguelCarro();
			$aluguel->carro_id = $carro_id;
			$aluguel->user_id = $request->usuario_id;
			$aluguel->inicio_aluguel = $date; 
			$aluguel->fim_aluguel = $dataFinalFormatada; 
			$aluguel->valor_total = $valorFormatado[1]; 
			$aluguel->created_at = now();
			$aluguel->updated_at = now();
			$aluguel->save();
			ConfigCarross::where('id', $carro_id)->update(['alugado'=> 1]);
			return redirect()->route('home');
		}
	
		public function alugando($IDConfigCarros){
		
			$Modulo = "ConfigCarros";
	
			try {
				$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
				return redirect()->route('telaAluguelCarros', ['id' => $IDConfigCarros])
				->with(['carroId' => $IDConfigCarros, 'hasRole' => $usuario != null]);
			} catch (Exception $e) {
	
				$Error = $e->getMessage();
				$Error = explode("MESSAGE:", $Error);
	
				$Pagina = $_SERVER["REQUEST_URI"];
	
				$Erro = $Error[0];
				$Erro_Completo = $e->getMessage();
				$LogsErrors = new logsErrosController;
				$Registra = $LogsErrors->RegistraErro($Pagina, $Modulo, $Erro, $Erro_Completo);
	
				abort(403, "Erro localizado e enviado ao LOG de Erros");
			}
	
		}

		public function store(Request $request)
		{
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("create.ConfigCarros");
		
			if (!$permUser) {
					return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{

			
			$data = Session::all();
	
			$url = null;
			$rules = "png,jpg,jpeg";
			$FormatosLiberados = explode(",", $rules);    
			if($request->hasFile("anexo")){
				if($request->file("anexo")->isValid()){
					if (in_array($request->file("anexo")->extension(),$FormatosLiberados)) {
						$ext = $request->file("anexo")->extension();						
						$anexo = $request->file("anexo")->store("ConfigCarros/1");
						$data = date("d_m_Y H_i_s");
						$NovoNome = "AnexoEnviado_($data).$ext";
						Storage::move($anexo, "ConfigCarros/1/$NovoNome");
						$url = "ConfigCarros/1/".$NovoNome;						
						$url = str_replace("/","-",$url);		
					} else {
						$ext = $request->file("anexo")->extension();
						return redirect()->route("form.store.ConfigCarros")->withErrors(["msg" => "Atenção o formato enviado na anexo foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
						}
					}					
			}

			$save = new stdClass;
			$save->modelo = $request->modelo;
		 	$save->anexo = $url;
$save->placa = $request->placa;
$save->marca = $request->marca;
$save->valor_diaria = $request->valor_diaria;
$save->alugado = 0;
$save->vendido = 0;
$save->ano = $request->ano;
$save->cor = $request->cor;
$save->valor_compra = $request->valor_compra;
$save->valor_para_venda = $request->valor_para_venda;
$save->observacao = $request->observacao;
$save->status = $request->status;
$save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));

			$save = collect($save)->toArray();

			DB::table("config_carros")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();
			$Acao = "Inseriu um Novo Registro no Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->route("list.ConfigCarros");
			
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

			return redirect()->route("list.ConfigCarros");
			
		}
	
		


		public function edit($IDConfigCarros)
		{
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("edit.ConfigCarros");

			if (!$permUser) {
					return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{

			
	
			$AcaoID = $this->return_id($IDConfigCarros);

			
				  
			$ConfigCarros = DB::table("config_carros")
			->where("token", $IDConfigCarros)
			->first();   
			$Acao = "Abriu a Tela de Edição do Módulo de ConfigCarros";
			$Logs = new logs; 
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,$AcaoID);
			return Inertia::render("ConfigCarros/Edit", [
				"ConfigCarros" => $ConfigCarros,
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
			abort(403, "Erro localizado e enviado ao LOG de Erros");
		}

		}
	
	
		public function update(Request $request, $id)
		{
		  
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("edit.ConfigCarros");
		
			if (!$permUser) {
					return redirect()->route("list.Dashboard",["id"=>"1"]);
			}


			try{

				if(!isset($id)){ $id = 0; }
				$AnexoExiste = DB::table("config_carros")->where("token",$id)->first();
				$url = null;
				$rules = "png,jpg,jpeg";
				$FormatosLiberados = explode(",", $rules);    
				if($request->hasFile("anexo")){
					if($request->file("anexo")->isValid()){
						if (in_array($request->file("anexo")->extension(),$FormatosLiberados)) {
							$ext = $request->file("anexo")->extension();
							$anexo = $request->file("anexo")->store("ConfigCarros/1");
							$data = date("d_m_Y H_i_s");
							$NovoNome = "AnexoEnviado_($data).$ext";
							Storage::move($anexo, "ConfigCarros/1/$NovoNome");
							$url = "ConfigCarros/1/".$NovoNome;						
							$url = str_replace("/","-",$url);
							if($AnexoExiste){	
							$AnexoAntigo = str_replace("-","/",$AnexoExiste->anexo);			
							Storage::delete($AnexoAntigo);
							}
						} else {
							$ext = $request->file("anexo")->extension();
							return redirect()->route("form.store.ConfigCarros",["id"=>$id])->withErrors(["msg" => "Atenção o formato enviado na anexo foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
							}
						}					
				}
				$AcaoID = $this->return_id($id);
		
				
	
				$save = new stdClass;
				$save->modelo = $request->modelo;
				if($url){ $save->anexo = $url;}
$save->placa = $request->placa;
$save->marca = $request->marca;
$save->ano = $request->ano;
$save->cor = $request->cor;
$save->valor_diaria = $request->valor_diaria;
$save->valor_compra = $request->valor_compra;
$save->valor_para_venda = $request->valor_para_venda;
$save->observacao = $request->observacao;
$save->status = $request->status;
$save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));
				
				$save = collect($save)->filter(function ($value) {
					return !is_null($value);
				});
				$save = $save->toArray();
		
				DB::table("config_carros")
					->where("token", $id)
					->update($save);

				

				$Acao = "Editou um registro no Módulo de ConfigCarros";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,$AcaoID);
				
			return redirect()->route("list.ConfigCarros");

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


	
	
	
		public function delete($IDConfigCarros)
		{
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("delete.ConfigCarros");
		
			if (!$permUser) {
					return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{

			$AcaoID = $this->return_id($IDConfigCarros);
	
			DB::table("config_carros")
				->where("token", $IDConfigCarros)
				->update([
					"deleted" => "1",
				]);

		
			
			$Acao = "Excluiu um registro no Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
	
			return redirect()->route("list.ConfigCarros");

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

		

		public function deleteSelected($IDConfigCarros=null)
		{
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("delete.ConfigCarros");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{
		
			$IDsRecebidos = explode(",",$IDConfigCarros);
			$total = count(array_filter($IDsRecebidos));		
			if($total > 0){			
			foreach($IDsRecebidos as $id){
			$AcaoID = $this->return_id($id);
			DB::table("config_carros")
				->where("token", $id)
				->update([
					"deleted" => "1",
				]);
			$Acao = "Excluiu um registro no Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
			}	
			}
	
			return redirect()->route("list.ConfigCarros");

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

		public function deletarTodos()
		{
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("delete.ConfigCarros");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{			
	
			DB::table("config_carros")			
				->update([
					"deleted" => "1",
				]);
			$Acao = "Excluiu TODOS os registros no Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,0);
			
			
	
			return redirect()->route("list.ConfigCarros");

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

		public function RestaurarTodos()
		{
			$Modulo = "ConfigCarros";

			$permUser = Auth::user()->hasPermissionTo("delete.ConfigCarros");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{			
	
			DB::table("config_carros")			
				->update([
					"deleted" => "0",
				]);
			$Acao = "Restaurou TODOS os registros no Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,0);
			
			
	
			return redirect()->route("list.ConfigCarros");

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

		public function DadosRelatorio(){
			$data = Session::all();
			
			$ConfigCarros = DB::table("config_carros")
			
			->select(DB::raw("config_carros.*, DATE_FORMAT(config_carros.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			 
			"))			
			->where("config_carros.deleted","0");

			
if(isset($data["ConfigCarros"]["modelo"])){				
					$AplicaFiltro = $data["ConfigCarros"]["modelo"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.modelo",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["placa"])){				
					$AplicaFiltro = $data["ConfigCarros"]["placa"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.placa",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["marca"])){				
					$AplicaFiltro = $data["ConfigCarros"]["marca"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.marca",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["ano"])){				
					$AplicaFiltro = $data["ConfigCarros"]["ano"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.ano",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["cor"])){				
					$AplicaFiltro = $data["ConfigCarros"]["cor"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.cor",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_compra"])){				
					$AplicaFiltro = $data["ConfigCarros"]["valor_compra"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.valor_compra",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["valor_para_venda"])){				
					$AplicaFiltro = $data["ConfigCarros"]["valor_para_venda"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["observacao"])){				
					$AplicaFiltro = $data["ConfigCarros"]["observacao"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.observacao",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["status"])){				
					$AplicaFiltro = $data["ConfigCarros"]["status"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.status",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["created_at"])){				
					$AplicaFiltro = $data["ConfigCarros"]["created_at"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.created_at",  "like", "%" . $AplicaFiltro . "%");			
				}

			$ConfigCarros = $ConfigCarros->get();

			$Dadosconfig_carros = [];
			foreach($ConfigCarros as $config_carross){
				if($config_carross->status == "0"){
					$config_carross->status = "Ativo";
				}
				if($config_carross->status == "1"){
					$config_carross->status = "Inativo";
				}
				$Dadosconfig_carros[] = [	
					
'modelo' => $config_carross->modelo,
'placa' => $config_carross->placa,
'marca' => $config_carross->marca,
'ano' => $config_carross->ano,
'cor' => $config_carross->cor,
'valor_compra' => $config_carross->valor_compra,
'valor_para_venda' => $config_carross->valor_para_venda,
'observacao' => $config_carross->observacao,
'status' => $config_carross->status,
'data_final' => $config_carross->data_final
				];
			}
			return $Dadosconfig_carros;
		}

		public function exportarRelatorioExcel(){

			$permUser = Auth::user()->hasPermissionTo("create.ConfigCarros");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			
			$filePath = "Relatorio_ConfigCarros.xlsx";

			if (Storage::disk("public")->exists($filePath)) {
				Storage::disk("public")->delete($filePath);
				// Arquivo foi deletado com sucesso
			}	
					
			$cabecalhoAba1 = array('modelo','placa','marca','ano','cor','valor de compra','observacao','status','Data de Cadastro');

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$config_carros = $this->DadosRelatorio();

			// Define o título da primeira aba
			$spreadsheet->setActiveSheetIndex(0);
			$spreadsheet->getActiveSheet()->setTitle("ConfigCarros");

			// Adiciona os cabeçalhos da tabela na primeira aba
			$spreadsheet->getActiveSheet()->fromArray($cabecalhoAba1, null, "A1");
		
			// Adiciona os dados da tabela na primeira aba
			$spreadsheet->getActiveSheet()->fromArray($config_carros, null, "A2");
			
			// Definindo a largura automática das colunas na primeira aba
			foreach ($spreadsheet->getActiveSheet()->getColumnDimensions() as $col) {
				$col->setAutoSize(true);
			}

			// Habilita a funcionalidade de filtro para as células da primeira aba
			$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		
			// Define o nome do arquivo	
			$nomeArquivo = "Relatorio_ConfigCarros.xlsx";		
			// Cria o arquivo
			$writer = IOFactory::createWriter($spreadsheet, "Xlsx");
			$writer->save($nomeArquivo);
			$barra = "'/'";
			$barra = str_replace("'","",$barra);		
			$writer->save(storage_path("app".$barra."relatorio".$barra.$nomeArquivo));
		
			return redirect()->route("download2.files",["path"=>$nomeArquivo]);
			
		}
	}
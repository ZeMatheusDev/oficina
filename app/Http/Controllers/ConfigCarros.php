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
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use Illuminate\Support\Facades\Storage;
	use stdClass;
	
	class ConfigCarros extends Controller
	{
		public function index(Request $request)
		{
			$Modulo = "ConfigCarros";
			
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
			
			
			
if(isset($data["ConfigCarros"]["nome"])){				
					$AplicaFiltro = $data["ConfigCarros"]["nome"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.nome",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["placa"])){				
					$AplicaFiltro = $data["ConfigCarros"]["placa"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.placa",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["modelo"])){				
					$AplicaFiltro = $data["ConfigCarros"]["modelo"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.modelo",  "like", "%" . $AplicaFiltro . "%");			
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
			$Cidades = DB::table("util_cidades")->where("deleted", '0')->get();  

			return Inertia::render("ConfigCarros/List", [
				"columnsTable" => $columnsTable,
				"ConfigCarros" => $ConfigCarros,
				"Cidades" => $Cidades,
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
	
		public function create()
		{        
			$Modulo = "ConfigCarros";
			$permUser = Auth::user()->hasPermissionTo("create.ConfigCarros");
		
			if (!$permUser) {
					return redirect()->route("list.Dashboard",["id"=>"1"]);
			}
			try{

			$Cidades = DB::table("util_cidades")->where("deleted", '0')->get();  			

			$Acao = "Abriu a Tela de Cadastro do Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			return Inertia::render("ConfigCarros/Create",[
				'Cidades' => $Cidades,
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
			$save->nome = $request->nome;
			$save->cidade = $request->cidade;
		 	$save->anexo = $url;
$save->placa = $request->placa;
$save->modelo = $request->modelo;
$save->ano = $request->ano;
$save->cor = $request->cor;
$save->valor_compra = $request->valor_compra;
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
			$Cidades = DB::table("util_cidades")->where("deleted", '0')->get();  
			$Acao = "Abriu a Tela de Edição do Módulo de ConfigCarros";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,$AcaoID);
			return Inertia::render("ConfigCarros/Edit", [
				"ConfigCarros" => $ConfigCarros,
				"Cidades" => $Cidades,
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
				$AnexoExiste = DB::table("config_motos")->where("token",$id)->first();
				$url = null;
				$rules = "png,jpg,jpeg";
				$FormatosLiberados = explode(",", $rules);    
				if($request->hasFile("anexo")){
					if($request->file("anexo")->isValid()){
						if (in_array($request->file("anexo")->extension(),$FormatosLiberados)) {
							$ext = $request->file("anexo")->extension();
							$anexo = $request->file("anexo")->store("ConfigMotos/1");
							$data = date("d_m_Y H_i_s");
							$NovoNome = "AnexoEnviado_($data).$ext";
							Storage::move($anexo, "ConfigMotos/1/$NovoNome");
							$url = "ConfigMotos/1/".$NovoNome;						
							$url = str_replace("/","-",$url);
							if($AnexoExiste){	
							$AnexoAntigo = str_replace("-","/",$AnexoExiste->anexo);			
							Storage::delete($AnexoAntigo);
							}
						} else {
							$ext = $request->file("anexo")->extension();
							return redirect()->route("form.store.ConfigMotos",["id"=>$id])->withErrors(["msg" => "Atenção o formato enviado na anexo foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
							}
						}					
				}
				$AcaoID = $this->return_id($id);
		
				
	
				$save = new stdClass;
				$save->nome = $request->nome;
				if($url){ $save->anexo = $url;}
$save->placa = $request->placa;
$save->cidade = $request->cidade;
$save->modelo = $request->modelo;
$save->ano = $request->ano;
$save->cor = $request->cor;
$save->valor_compra = $request->valor_compra;
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

			
if(isset($data["ConfigCarros"]["nome"])){				
					$AplicaFiltro = $data["ConfigCarros"]["nome"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.nome",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["placa"])){				
					$AplicaFiltro = $data["ConfigCarros"]["placa"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.placa",  "like", "%" . $AplicaFiltro . "%");			
				}
if(isset($data["ConfigCarros"]["modelo"])){				
					$AplicaFiltro = $data["ConfigCarros"]["modelo"];			
					$ConfigCarros = $ConfigCarros->Where("config_carros.modelo",  "like", "%" . $AplicaFiltro . "%");			
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
					
'nome' => $config_carross->nome,
'placa' => $config_carross->placa,
'modelo' => $config_carross->modelo,
'ano' => $config_carross->ano,
'cor' => $config_carross->cor,
'valor_compra' => $config_carross->valor_compra,
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
					
			$cabecalhoAba1 = array('nome','placa','modelo','ano','cor','valor_compra','observacao','status','Data de Cadastro');

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
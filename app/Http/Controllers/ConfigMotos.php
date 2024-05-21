<?php

namespace App\Http\Controllers;

use App\Models\AluguelMoto;
use App\Models\HistoricoAluguelMoto;
use Exception;
use App\Models\DisabledColumns;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Office;
use App\Models\ConfigMotoss;
use App\Models\VendaMotos;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use stdClass;

class ConfigMotos extends Controller
{

	public function index(Request $request)
	{
		$Modulo = "ConfigMotos";
		$permUser = Auth::user()->hasPermissionTo("list.ConfigMotos");
		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}
		try {

			$dataDeHoje = Carbon::now();
	
			$motosAlugadas = DB::table('aluguel_motos')->get();
			
			foreach($motosAlugadas as $moto){
				$fimAluguel = Carbon::createFromFormat('d/m/Y', $moto->fim_aluguel);
				if($fimAluguel < $dataDeHoje){
					$historico = new HistoricoAluguelMoto();
					$historico->moto_id = $moto->moto_id;
					$historico->user_id = $moto->user_id;
					$historico->inicio_aluguel = $moto->inicio_aluguel;
					$historico->fim_aluguel = $moto->fim_aluguel;
					$historico->valor_total = $moto->valor_total;
					$historico->save();
					ConfigMotoss::where('id', $moto->moto_id)->update(['alugado' => 0]);
					AluguelMoto::where('id', $moto->id)->delete();
				}
			}
			$data = Session::all();

			if (!isset($data["ConfigMotos"]) || empty($data["ConfigMotos"])) {
				session(["ConfigMotos" => array("status" => "0", "orderBy" => array("column" => "created_at", "sorting" => "1"), "limit" => "10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if ($request->input()) {
				$Limpar = false;
				if ($request->input("limparFiltros") == true) {
					$Limpar = true;
				}

				$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigMotos"]);
				if ($arrayFilter) {
					session(["ConfigMotos" => $arrayFilter]);

					$data = Session::all();
				}

			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigMotos")
				->first()
				?->columns;

			$ConfigMotos = DB::table("config_motos")

				->select(DB::raw("config_motos.*, DATE_FORMAT(config_motos.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));

			if (isset($data["ConfigMotos"]["orderBy"])) {
				if(isset($data["ConfigMotos"]["orderBy"]["column"])){
					$Coluna = $data["ConfigMotos"]["orderBy"]["column"];
				
				$ConfigMotos =  $ConfigMotos->orderBy("config_motos.$Coluna", $data["ConfigMotos"]["orderBy"]["sorting"] ? "asc" : "desc");
				}
			} else {
				$ConfigMotos =  $ConfigMotos->orderBy("config_motos.created_at", "desc");
			}

			//MODELO DE FILTRO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM IF PARA APLICAR O FILTRO, EXCLUIR O FILTRO DE ID, DELETED E UPDATED_AT

			if (isset($data["ConfigMotos"]["modelo"])) {
				$AplicaFiltro = $data["ConfigMotos"]["modelo"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.modelo",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["marca"])) {
				$AplicaFiltro = $data["ConfigMotos"]["marca"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.marca",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["cor"])) {
				$AplicaFiltro = $data["ConfigMotos"]["cor"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.cor",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["placa"])) {
				$AplicaFiltro = $data["ConfigMotos"]["placa"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.placa",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["ano"])) {
				$AplicaFiltro = $data["ConfigMotos"]["ano"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.ano",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_compra"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_compra"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_compra",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_para_venda"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_para_venda"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_diaria"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_diaria"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_diaria",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["observacoes"])) {
				$AplicaFiltro = $data["ConfigMotos"]["observacoes"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.observacoes",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["status"])) {
				$AplicaFiltro = $data["ConfigMotos"]["status"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.status",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["created_at"])) {
				$AplicaFiltro = $data["ConfigMotos"]["created_at"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.created_at",  "like", "%" . $AplicaFiltro . "%");
			}

			$ConfigMotos = $ConfigMotos->where("config_motos.deleted", "0")->join('companies', 'config_motos.empresa_id', '=', 'companies.id')->select('companies.name as empresa_nome', 'companies.cidade as cidade', 'config_motos.*');

			$ConfigMotos = $ConfigMotos->paginate(($data["ConfigMotos"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);

			$Acao = "Acessou a listagem do Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$empresaSelecionada = session()->all()['empresa_nome'];
			return Inertia::render("ConfigMotos/List", [
				"columnsTable" => $columnsTable,
				"empresaSelecionada" => $empresaSelecionada,
				"ConfigMotos" => $ConfigMotos,
				"hasRole" => $usuario != null,
				"Filtros" => $data["ConfigMotos"],
				"Registros" => $Registros,
			]);
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

	public function vendaMotos(Request $request){
		$Modulo = "ConfigMotos";

		$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

		$dataDeHoje = Carbon::now();
	
		$motosAlugadas = DB::table('aluguel_motos')->get();
		
		foreach($motosAlugadas as $moto){
			$fimAluguel = Carbon::createFromFormat('d/m/Y', $moto->fim_aluguel);
			if($fimAluguel < $dataDeHoje){
				$historico = new HistoricoAluguelMoto();
				$historico->moto_id = $moto->moto_id;
				$historico->user_id = $moto->user_id;
				$historico->inicio_aluguel = $moto->inicio_aluguel;
				$historico->fim_aluguel = $moto->fim_aluguel;
				$historico->valor_total = $moto->valor_total;
				$historico->save();
				ConfigMotoss::where('id', $moto->moto_id)->update(['alugado' => 0]);
				AluguelMoto::where('id', $moto->id)->delete();
			}
		}

		try{
			$verificarMotosNaoAlugadas = DB::table('config_motos')->where('alugado', 0)->get();
			$data = Session::all();

			if (!isset($data["ConfigMotos"]) || empty($data["ConfigMotos"])) {
				session(["ConfigMotos" => array("status" => "0", "orderBy" => array("column" => "created_at", "sorting" => "1"), "limit" => "10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if ($request->input()) {
				$Limpar = false;
				if ($request->input("limparFiltros") == true) {
					$Limpar = true;
				}

				$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigMotos"]);
				if ($arrayFilter) {
					session(["ConfigMotos" => $arrayFilter]);

					$data = Session::all();
				}

			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigMotos")
				->first()
				?->columns;

			$ConfigMotos = DB::table("config_motos")

				->select(DB::raw("config_motos.*, DATE_FORMAT(config_motos.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));

			if (isset($data["ConfigMotos"]["orderBy"])) {
				if(isset($data["ConfigMotos"]["orderBy"]["column"])){
					$Coluna = $data["ConfigMotos"]["orderBy"]["column"];
				
				$ConfigMotos =  $ConfigMotos->orderBy("config_motos.$Coluna", $data["ConfigMotos"]["orderBy"]["sorting"] ? "asc" : "desc");
				}
			} else {
				$ConfigMotos =  $ConfigMotos->orderBy("config_motos.created_at", "desc");
			}

			//MODELO DE FILTRO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM IF PARA APLICAR O FILTRO, EXCLUIR O FILTRO DE ID, DELETED E UPDATED_AT

			if (isset($data["ConfigMotos"]["modelo"])) {
				$AplicaFiltro = $data["ConfigMotos"]["modelo"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.modelo",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["marca"])) {
				$AplicaFiltro = $data["ConfigMotos"]["marca"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.marca",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["cor"])) {
				$AplicaFiltro = $data["ConfigMotos"]["cor"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.cor",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["placa"])) {
				$AplicaFiltro = $data["ConfigMotos"]["placa"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.placa",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["ano"])) {
				$AplicaFiltro = $data["ConfigMotos"]["ano"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.ano",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_diaria"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_diaria"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_diaria",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_compra"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_compra"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_compra",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_para_venda"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_para_venda"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["observacoes"])) {
				$AplicaFiltro = $data["ConfigMotos"]["observacoes"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.observacoes",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["status"])) {
				$AplicaFiltro = $data["ConfigMotos"]["status"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.status",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["created_at"])) {
				$AplicaFiltro = $data["ConfigMotos"]["created_at"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.created_at",  "like", "%" . $AplicaFiltro . "%");
			}

			$ConfigMotos = $ConfigMotos->where("config_motos.alugado", "0")->where("config_motos.deleted", "0")->where("config_motos.vendido", "0")->where('config_motos.empresa_id', session()->all()['empresa']);

			$ConfigMotos = $ConfigMotos->paginate(($data["ConfigMotos"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
			
			$Acao = "Acessou a listagem do Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$empresaSelecionada = session()->all()['empresa_nome'];

			return Inertia::render("vendaMotos", [
				"columnsTable" => $columnsTable,
				"ConfigMotos" => $ConfigMotos,
				"hasRole" => $usuario != null,
				"Filtros" => $data["ConfigMotos"],
				"Registros" => $Registros,
				"empresaSelecionada" => $empresaSelecionada,


			]);

		}catch (Exception $e) {

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

	public function Registros()
	{

		$mes = date("m");
		$Total = DB::table("config_motos")
			->where("config_motos.deleted", "0")
			->count();

		$Ativos = DB::table("config_motos")
			->where("config_motos.deleted", "0")
			->where("config_motos.status", "0")
			->count();

		$Inativos = DB::table("config_motos")
			->where("config_motos.deleted", "0")
			->where("config_motos.status", "1")
			->count();

		$EsseMes = DB::table("config_motos")
			->where("config_motos.deleted", "0")
			->whereMonth("config_motos.created_at", $mes)
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
		$Modulo = "ConfigMotos";
		$permUser = Auth::user()->hasPermissionTo("create.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}
		try {

			$Acao = "Abriu a Tela de Cadastro do Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$empresaSelecionada = session()->all()['empresa_nome'];

			return Inertia::render("ConfigMotos/Create", [
				"hasRole" => $usuario != null,
				"empresaSelecionada" => $empresaSelecionada,

			]);
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

	public function return_id($id)
	{
		$ConfigMotos = DB::table("config_motos");
		$ConfigMotos = $ConfigMotos->where("deleted", "0");
		$ConfigMotos = $ConfigMotos->where("token", $id)->first();

		return $ConfigMotos->id;
	}

	public function store(Request $request)
	{
		$Modulo = "ConfigMotos";
		$permUser = Auth::user()->hasPermissionTo("create.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {


			$data = Session::all();
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
					} else {
						$ext = $request->file("anexo")->extension();
						return redirect()->route("form.store.ConfigMotos")->withErrors(["msg" => "Atenção o formato enviado na anexo foi: $ext, só são permitidos os seguintes formatos: $rules ."]);
						}
					}					
			}



			$save = new stdClass;
			//MODELO DE INSERT PARA VOCE FAZER COM TODAS AS COLUNAS DO BANCO DE DADOS, MENOS ID, DELETED E UPDATED_AT
			$save->modelo = $request->modelo;
			$save->placa = $request->placa;
			$save->ano = $request->ano;
			$save->empresa_id = session()->all()['empresa'];
			$save->valor_diaria = $request->valor_diaria;
			$save->marca = $request->marca;
			$save->anexo = $url;
			$save->cor = $request->cor;
			$save->valor_compra = $request->valor_compra;
			$save->valor_para_venda = $request->valor_para_venda;
			$save->observacoes = $request->observacoes;
			

			//ESSAS AQUI SEMPRE TERÃO POR PADRÃO
			$save->status = $request->status;
			$save->token = md5(date("Y-m-d H:i:s") . rand(0, 999999999));

			$save = collect($save)->toArray();
			DB::table("config_motos")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inseriu um Novo Registro no Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(2, $Modulo, $Acao, $lastId);

			return redirect()->route("list.ConfigMotos");
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

		return redirect()->route("list.ConfigMotos");
	}




	public function edit($IDConfigMotos)
	{
		$Modulo = "ConfigMotos";

		$permUser = Auth::user()->hasPermissionTo("edit.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			$AcaoID = $this->return_id($IDConfigMotos);

			$ConfigMotos = DB::table("config_motos")
				->where("token", $IDConfigMotos)
				->first();

			$Acao = "Abriu a Tela de Edição do Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao, $AcaoID);
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$empresaSelecionada = session()->all()['empresa_nome'];
			
			return Inertia::render("ConfigMotos/Edit", [
				"ConfigMotos" => $ConfigMotos,
				"empresaSelecionada" => $empresaSelecionada,

				"hasRole" => $usuario != null,
			]);
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


	public function update(Request $request, $id)
	{

		$Modulo = "ConfigMotos";

		$permUser = Auth::user()->hasPermissionTo("edit.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}


		try {

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
			if($url){ $save->anexo = $url;}
			$save->modelo = $request->modelo;
			$save->marca = $request->marca;
			$save->placa = $request->placa;
			$save->ano = $request->ano;
			$save->cor = $request->cor;
			$save->valor_diaria = $request->valor_diaria;
			$save->valor_compra = $request->valor_compra;
			$save->valor_para_venda = $request->valor_para_venda;
			$save->observacoes = $request->observacoes;
			//MODELO DE INSERT PARA VOCE FAZER COM TODAS AS COLUNAS DO BANCO DE DADOS, MENOS ID, DELETED E UPDATED_AT
			

			//ESSAS AQUI SEMPRE TERÃO POR PADRÃO
			$save->status = $request->status;

			$save = collect($save)->toArray();
			DB::table("config_motos")
				->where("token", $id)
				->update($save);



			$Acao = "Editou um registro no Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(3, $Modulo, $Acao, $AcaoID);

			return redirect()->route("list.ConfigMotos");
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

	public function telaAluguel(Request $request){
		$tokenDaMoto = $request->route('id');
		$moto = DB::table('config_motos')->where('token', $tokenDaMoto)->first();
		$moto_modelo = $moto->modelo;
		$moto_id = $moto->id;
		$hasRole = session('hasRole');
		$usuario = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
		$usuario_id = $usuario->id;
		$Users = DB::table('users')->get();
		$empresaSelecionada = session()->all()['empresa_nome'];
		$usuario_nome = $usuario->name;
		return Inertia::render("telaAluguel", [
			'hasRole' => $hasRole,
			'usuario_id' => $usuario_id,
			'usuario_nome' => $usuario_nome,
			"empresaSelecionada" => $empresaSelecionada,
			'categoria' => $usuario->role_id,	
			'Users' => $Users,
			'moto_modelo' => $moto_modelo,
			'valor_diaria' => $moto->valor_diaria,
			'moto_id' => $moto_id,
		]);
	}

	public function alugando($IDConfigMotos){
		
		$Modulo = "ConfigMotos";

		try {
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			return redirect()->route('telaAluguel', ['id' => $IDConfigMotos])
			->with(['motoId' => $IDConfigMotos, 'hasRole' => $usuario != null]);
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

	public function comprando($IDConfigMotos){
		
		$Modulo = "ConfigMotos";

		try {
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			return redirect()->route('telaCompraMoto', ['id' => $IDConfigMotos])
			->with(['motoId' => $IDConfigMotos, 'hasRole' => $usuario != null]);
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

	public function compradoMotos(Request $request){
		ConfigMotoss::where('id', $request->moto_id)->update(['vendido' => 1]);
		$verificarValorCompra = DB::table('config_motos')->where('id', $request->moto_id)->first();
		$valorFormatado = explode('$', $request->valor);
		$lucro = intval($valorFormatado[1]) - intval($verificarValorCompra->valor_compra);
		$venda = new VendaMotos();
		$venda->moto_id = $request->moto_id;
		$venda->user_id = $request->usuario_id;
		$venda->lucro = $lucro;
		$venda->save();
		return redirect()->route('home');
	}

	public function telaCompraMoto(Request $request){
		$tokenDaMoto = $request->route('id');
		$moto = DB::table('config_motos')->where('token', $tokenDaMoto)->first();
		$moto_modelo = $moto->modelo;
		$moto_id = $moto->id;
		$hasRole = session('hasRole');
		$usuario = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
		$usuario_id = $usuario->id;
		$usuario_nome = $usuario->name;
		$empresaSelecionada = session()->all()['empresa_nome'];
		$Users = DB::table('users')->get();

		return Inertia::render("telaCompraMoto", [
			'hasRole' => $hasRole,
			'usuario_id' => $usuario_id,
			'usuario_nome' => $usuario_nome,
			'empresaSelecionada' => $empresaSelecionada,
			'Users' => $Users,
			'moto_modelo' => $moto_modelo,
			'valor_para_venda' => $moto->valor_para_venda,
			'categoria' => $usuario->role_id,	
			'moto_id' => $moto_id,
		]);
	}

	public function delete($IDConfigMotos)
	{	

		$Modulo = "ConfigMotos";

		$permUser = Auth::user()->hasPermissionTo("delete.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			$AcaoID = $this->return_id($IDConfigMotos);

			DB::table("config_motos")
				->where("token", $IDConfigMotos)
				->update([
					"deleted" => "1",
				]);



			$Acao = "Excluiu um registro no Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, $AcaoID);

			return redirect()->route("list.ConfigMotos");
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



	public function deleteSelected($IDConfigMotos = null)
	{
		$Modulo = "ConfigMotos";

		$permUser = Auth::user()->hasPermissionTo("delete.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			$IDsRecebidos = explode(",", $IDConfigMotos);
			$total = count(array_filter($IDsRecebidos));
			if ($total > 0) {
				foreach ($IDsRecebidos as $id) {
					$AcaoID = $this->return_id($id);
					DB::table("config_motos")
						->where("token", $id)
						->update([
							"deleted" => "1",
						]);
					$Acao = "Excluiu um registro no Módulo de ConfigMotos";
					$Logs = new logs;
					$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, $AcaoID);
				}
			}

			return redirect()->route("list.ConfigMotos");
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

	public function deletarTodos()
	{
		$Modulo = "ConfigMotos";

		$permUser = Auth::user()->hasPermissionTo("delete.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			DB::table("config_motos")
				->update([
					"deleted" => "1",
				]);
			$Acao = "Excluiu TODOS os registros no Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, 0);



			return redirect()->route("list.ConfigMotos");
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

	public function RestaurarTodos()
	{
		$Modulo = "ConfigMotos";

		$permUser = Auth::user()->hasPermissionTo("delete.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			DB::table("config_motos")
				->update([
					"deleted" => "0",
				]);
			$Acao = "Restaurou TODOS os registros no Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, 0);



			return redirect()->route("list.ConfigMotos");
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

	public function DadosRelatorio()
	{
		$data = Session::all();

		$ConfigMotos = DB::table("config_motos")

			->select(DB::raw("config_motos.*, DATE_FORMAT(config_motos.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			 
			"))
			->where("config_motos.deleted", "0");

		//MODELO DE FILTRO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM IF PARA APLICAR O FILTRO, EXCLUIR O FILTRO DE ID, DELETED E UPDATED_AT

		
		if (isset($data["ConfigMotos"]["modelo"])) {
			$AplicaFiltro = $data["ConfigMotos"]["modelo"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.modelo",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["marca"])) {
			$AplicaFiltro = $data["ConfigMotos"]["marca"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.marca",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["cor"])) {
			$AplicaFiltro = $data["ConfigMotos"]["cor"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.cor",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["placa"])) {
			$AplicaFiltro = $data["ConfigMotos"]["placa"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.placa",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["ano"])) {
			$AplicaFiltro = $data["ConfigMotos"]["ano"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.ano",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["valor_diaria"])) {
			$AplicaFiltro = $data["ConfigMotos"]["valor_diaria"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.valor_diaria",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["observacoes"])) {
			$AplicaFiltro = $data["ConfigMotos"]["observacoes"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.observacoes",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["status"])) {
			$AplicaFiltro = $data["ConfigMotos"]["status"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.status",  "like", "%" . $AplicaFiltro . "%");
		}

		if (isset($data["ConfigMotos"]["created_at"])) {
			$AplicaFiltro = $data["ConfigMotos"]["created_at"];
			$ConfigMotos = $ConfigMotos->Where("config_motos.created_at",  "like", "%" . $AplicaFiltro . "%");
		}
	
		$ConfigMotos = $ConfigMotos->get();

		$Dadosconfig_motos = [];
		foreach ($ConfigMotos as $config_motoss) {
			if ($config_motoss->status == "0") {
				$config_motoss->status = "Ativo";
			}
			if ($config_motoss->status == "1") {
				$config_motoss->status = "Inativo";
			}
			$Dadosconfig_motos[] = [
				//MODELO DE CA,MPO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM, EXCLUIR O ID, DELETED E UPDATED_AT
				'modelo' => $config_motoss->modelo,	
				'marca' => $config_motoss->marca,	
				'cor' => $config_motoss->cor,				
				'placa' => $config_motoss->placa,							
				'ano' => $config_motoss->ano,							
				'observacoes' => $config_motoss->observacoes,				
				'valor_compra' => $config_motoss->valor_compra,	
				'valor_para_venda' => $config_motoss->valor_para_venda,				
				'status' => $config_motoss->status,				
				'Data de Cadastro' => $config_motoss->created_at,			
				'valor_diaria' => $config_motoss->valor_diaria,	
					

			
			];
		}
		return $Dadosconfig_motos;
	}

	public function aluguelMotos(Request $request){
		$Modulo = "ConfigMotos";

		$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

		$dataDeHoje = Carbon::now();
	
		$motosAlugadas = DB::table('aluguel_motos')->get();
		
		foreach($motosAlugadas as $moto){
			$fimAluguel = Carbon::createFromFormat('d/m/Y', $moto->fim_aluguel);
			if($fimAluguel < $dataDeHoje){
				$historico = new HistoricoAluguelMoto();
				$historico->moto_id = $moto->moto_id;
				$historico->user_id = $moto->user_id;
				$historico->inicio_aluguel = $moto->inicio_aluguel;
				$historico->fim_aluguel = $moto->fim_aluguel;
				$historico->valor_total = $moto->valor_total;
				$historico->save();
				ConfigMotoss::where('id', $moto->moto_id)->update(['alugado' => 0]);
				AluguelMoto::where('id', $moto->id)->delete();
			}
		}

		try{
			$verificarMotosNaoAlugadas = DB::table('config_motos')->where('alugado', 0)->get();
			$data = Session::all();

			if (!isset($data["ConfigMotos"]) || empty($data["ConfigMotos"])) {
				session(["ConfigMotos" => array("status" => "0", "orderBy" => array("column" => "created_at", "sorting" => "1"), "limit" => "10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if ($request->input()) {
				$Limpar = false;
				if ($request->input("limparFiltros") == true) {
					$Limpar = true;
				}

				$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["ConfigMotos"]);
				if ($arrayFilter) {
					session(["ConfigMotos" => $arrayFilter]);

					$data = Session::all();
				}

			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.ConfigMotos")
				->first()
				?->columns;

			$ConfigMotos = DB::table("config_motos")

				->select(DB::raw("config_motos.*, DATE_FORMAT(config_motos.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));

			if (isset($data["ConfigMotos"]["orderBy"])) {
				if(isset($data["ConfigMotos"]["orderBy"]["column"])){
					$Coluna = $data["ConfigMotos"]["orderBy"]["column"];
				
				$ConfigMotos =  $ConfigMotos->orderBy("config_motos.$Coluna", $data["ConfigMotos"]["orderBy"]["sorting"] ? "asc" : "desc");
				}
			} else {
				$ConfigMotos =  $ConfigMotos->orderBy("config_motos.created_at", "desc");
			}

			//MODELO DE FILTRO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM IF PARA APLICAR O FILTRO, EXCLUIR O FILTRO DE ID, DELETED E UPDATED_AT

			if (isset($data["ConfigMotos"]["modelo"])) {
				$AplicaFiltro = $data["ConfigMotos"]["modelo"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.modelo",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["marca"])) {
				$AplicaFiltro = $data["ConfigMotos"]["marca"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.marca",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["cor"])) {
				$AplicaFiltro = $data["ConfigMotos"]["cor"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.cor",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["placa"])) {
				$AplicaFiltro = $data["ConfigMotos"]["placa"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.placa",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["ano"])) {
				$AplicaFiltro = $data["ConfigMotos"]["ano"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.ano",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_diaria"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_diaria"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_diaria",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_compra"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_compra"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_compra",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["valor_para_venda"])) {
				$AplicaFiltro = $data["ConfigMotos"]["valor_para_venda"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.valor_para_venda",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["observacoes"])) {
				$AplicaFiltro = $data["ConfigMotos"]["observacoes"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.observacoes",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["status"])) {
				$AplicaFiltro = $data["ConfigMotos"]["status"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.status",  "like", "%" . $AplicaFiltro . "%");
			}

			if (isset($data["ConfigMotos"]["created_at"])) {
				$AplicaFiltro = $data["ConfigMotos"]["created_at"];
				$ConfigMotos = $ConfigMotos->Where("config_motos.created_at",  "like", "%" . $AplicaFiltro . "%");
			}

			$ConfigMotos = $ConfigMotos->where("config_motos.alugado", "0")->where("config_motos.deleted", "0")->where("config_motos.vendido", "0")->where('empresa_id', session()->all()['empresa']);

			$ConfigMotos = $ConfigMotos->paginate(($data["ConfigMotos"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
			
			$Acao = "Acessou a listagem do Módulo de ConfigMotos";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$Registros = $this->Registros();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$empresaSelecionada = session()->all()['empresa_nome'];

			return Inertia::render("aluguelMotos", [
				"columnsTable" => $columnsTable,
				"ConfigMotos" => $ConfigMotos,
				"empresaSelecionada" => $empresaSelecionada,
				"hasRole" => $usuario != null,
				"Filtros" => $data["ConfigMotos"],
				"Registros" => $Registros,

			]);

		}catch (Exception $e) {

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

	public function alugado(Request $request){
		$moto_id = $request->moto_id;
		$valorFormatado = explode('$', $request->valor);
		$date = Carbon::parse($request->inicio_aluguel)->format('d/m/Y');
		$dataInicialParaSomar = Carbon::parse($request->inicio_aluguel);
		$diasParaAdicionar = ((int)$request->dias);
		$dataFinal = $dataInicialParaSomar->addDays($diasParaAdicionar);
		$dataFinalFormatada = $dataFinal->format('d/m/Y');
		$aluguel = new AluguelMoto();
		$aluguel->moto_id = $moto_id;
		$aluguel->user_id = $request->usuario_id;
		$aluguel->inicio_aluguel = $date; 
		$aluguel->fim_aluguel = $dataFinalFormatada; 
		$aluguel->valor_total = $valorFormatado[1]; 
		$aluguel->created_at = now();
		$aluguel->updated_at = now();
		$aluguel->save();
		ConfigMotoss::where('id', $moto_id)->update(['alugado'=> 1]);
		return redirect()->route('home');
	}

	public function exportarRelatorioExcel()
	{

		$permUser = Auth::user()->hasPermissionTo("create.ConfigMotos");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}


		$filePath = "Relatorio_ConfigMotos.xlsx";

		if (Storage::disk("public")->exists($filePath)) {
			Storage::disk("public")->delete($filePath);
			// Arquivo foi deletado com sucesso
		}

		$cabecalhoAba1 = array('modelo', 'marca', 'cor', 'placa', 'ano', 'observacoes', 'valor de compra', 'status', 'Data de Cadastro', 'Valor diaria');
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$config_motos = $this->DadosRelatorio();
		// Define o título da primeira aba
		$spreadsheet->setActiveSheetIndex(0);
		$spreadsheet->getActiveSheet()->setTitle("ConfigMotos");

		// Adiciona os cabeçalhos da tabela na primeira aba
		$spreadsheet->getActiveSheet()->fromArray($cabecalhoAba1, null, "A1");

		// Adiciona os dados da tabela na primeira aba
		$spreadsheet->getActiveSheet()->fromArray($config_motos, null, "A2");

		// Definindo a largura automática das colunas na primeira aba
		foreach ($spreadsheet->getActiveSheet()->getColumnDimensions() as $col) {
			$col->setAutoSize(true);
		}

		// Habilita a funcionalidade de filtro para as células da primeira aba
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());


		// Define o nome do arquivo	
		$nomeArquivo = "Relatorio_ConfigMotos.xlsx";
		// Cria o arquivo
		$writer = IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->save($nomeArquivo);
		$barra = "'/'";
		$barra = str_replace("'", "", $barra);
		$writer->save(storage_path("app" . $barra . "relatorio" . $barra . $nomeArquivo));

		return redirect()->route("download2.files", ["path" => $nomeArquivo]);
	}
}

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

class @@TROCARAQUI@@@ extends Controller
{
	public function index(Request $request)
	{
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("list.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {



			$data = Session::all();

			if (!isset($data["@@TROCARAQUI@@@"]) || empty($data["@@TROCARAQUI@@@"])) {
				session(["@@TROCARAQUI@@@" => array("status" => "0", "orderBy" => array("column" => "created_at", "sorting" => "1"), "limit" => "10")]);
				$data = Session::all();
			}

			$Filtros = new Security;
			if ($request->input()) {
				$Limpar = false;
				if ($request->input("limparFiltros") == true) {
					$Limpar = true;
				}

				$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["@@TROCARAQUI@@@"]);
				if ($arrayFilter) {
					session(["@@TROCARAQUI@@@" => $arrayFilter]);
					$data = Session::all();
				}
			}


			$columnsTable = DisabledColumns::whereRouteOfList("list.@@TROCARAQUI@@@")
				->first()
				?->columns;

			$@@TROCARAQUI@@@ = DB::table("NomeDaTabela")

				->select(DB::raw("NomeDaTabela.*, DATE_FORMAT(NomeDaTabela.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			
			"));

			if (isset($data["@@TROCARAQUI@@@"]["orderBy"])) {
				$Coluna = $data["@@TROCARAQUI@@@"]["orderBy"]["column"];
				$@@TROCARAQUI@@@ =  $@@TROCARAQUI@@@->orderBy("NomeDaTabela.$Coluna", $data["@@TROCARAQUI@@@"]["orderBy"]["sorting"] ? "asc" : "desc");
			} else {
				$@@TROCARAQUI@@@ =  $@@TROCARAQUI@@@->orderBy("NomeDaTabela.created_at", "desc");
			}

			//MODELO DE FILTRO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM IF PARA APLICAR O FILTRO, EXCLUIR O FILTRO DE ID, DELETED E UPDATED_AT

			if (isset($data["@@TROCARAQUI@@@"]["nome"])) {
				$AplicaFiltro = $data["@@TROCARAQUI@@@"]["nome"];
				$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->Where("NomeDaTabela.nome",  "like", "%" . $AplicaFiltro . "%");
			}


			$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->where("NomeDaTabela.deleted", "0");

			$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->paginate(($data["@@TROCARAQUI@@@"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);

			$Acao = "Acessou a listagem do Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);
			$Registros = $this->Registros();

			return Inertia::render("@@TROCARAQUI@@@/List", [
				"columnsTable" => $columnsTable,
				"@@TROCARAQUI@@@" => $@@TROCARAQUI@@@,

				"Filtros" => $data["@@TROCARAQUI@@@"],
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

	public function Registros()
	{

		$mes = date("m");
		$Total = DB::table("NomeDaTabela")
			->where("NomeDaTabela.deleted", "0")
			->count();

		$Ativos = DB::table("NomeDaTabela")
			->where("NomeDaTabela.deleted", "0")
			->where("NomeDaTabela.status", "0")
			->count();

		$Inativos = DB::table("NomeDaTabela")
			->where("NomeDaTabela.deleted", "0")
			->where("NomeDaTabela.status", "1")
			->count();

		$EsseMes = DB::table("NomeDaTabela")
			->where("NomeDaTabela.deleted", "0")
			->whereMonth("NomeDaTabela.created_at", $mes)
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
		$Modulo = "@@TROCARAQUI@@@";
		$permUser = Auth::user()->hasPermissionTo("create.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}
		try {



			$Acao = "Abriu a Tela de Cadastro do Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao);

			return Inertia::render("@@TROCARAQUI@@@/Create", []);
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
		$@@TROCARAQUI@@@ = DB::table("NomeDaTabela");
		$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->where("deleted", "0");
		$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->where("token", $id)->first();

		return $@@TROCARAQUI@@@->id;
	}

	public function store(Request $request)
	{
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("create.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {


			$data = Session::all();




			$save = new stdClass;
			//MODELO DE INSERT PARA VOCE FAZER COM TODAS AS COLUNAS DO BANCO DE DADOS, MENOS ID, DELETED E UPDATED_AT
			$save->nome = $request->nome;
			

			//ESSAS AQUI SEMPRE TERÃO POR PADRÃO
			$save->status = $request->status;
			$save->token = md5(date("Y-m-d H:i:s") . rand(0, 999999999));

			$save = collect($save)->toArray();
			DB::table("NomeDaTabela")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inseriu um Novo Registro no Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(2, $Modulo, $Acao, $lastId);

			return redirect()->route("list.@@TROCARAQUI@@@");
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

		return redirect()->route("list.@@TROCARAQUI@@@");
	}




	public function edit($ID@@TROCARAQUI@@@)
	{
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("edit.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {



			$AcaoID = $this->return_id($ID@@TROCARAQUI@@@);



			$@@TROCARAQUI@@@ = DB::table("NomeDaTabela")
				->where("token", $ID@@TROCARAQUI@@@)
				->first();

			$Acao = "Abriu a Tela de Edição do Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(1, $Modulo, $Acao, $AcaoID);

			return Inertia::render("@@TROCARAQUI@@@/Edit", [
				"@@TROCARAQUI@@@" => $@@TROCARAQUI@@@,

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

		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("edit.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}


		try {


			$AcaoID = $this->return_id($id);



			$save = new stdClass;

			$save->nome = $request->nome;
			//MODELO DE INSERT PARA VOCE FAZER COM TODAS AS COLUNAS DO BANCO DE DADOS, MENOS ID, DELETED E UPDATED_AT
			$save->nome = $request->nome;
			

			//ESSAS AQUI SEMPRE TERÃO POR PADRÃO
			$save->status = $request->status;

			$save = collect($save)->toArray();
			DB::table("NomeDaTabela")
				->where("token", $id)
				->update($save);



			$Acao = "Editou um registro no Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(3, $Modulo, $Acao, $AcaoID);

			return redirect()->route("list.@@TROCARAQUI@@@");
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





	public function delete($ID@@TROCARAQUI@@@)
	{
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("delete.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			$AcaoID = $this->return_id($ID@@TROCARAQUI@@@);

			DB::table("NomeDaTabela")
				->where("token", $ID@@TROCARAQUI@@@)
				->update([
					"deleted" => "1",
				]);



			$Acao = "Excluiu um registro no Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, $AcaoID);

			return redirect()->route("list.@@TROCARAQUI@@@");
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



	public function deleteSelected($ID@@TROCARAQUI@@@ = null)
	{
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("delete.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			$IDsRecebidos = explode(",", $ID@@TROCARAQUI@@@);
			$total = count(array_filter($IDsRecebidos));
			if ($total > 0) {
				foreach ($IDsRecebidos as $id) {
					$AcaoID = $this->return_id($id);
					DB::table("NomeDaTabela")
						->where("token", $id)
						->update([
							"deleted" => "1",
						]);
					$Acao = "Excluiu um registro no Módulo de @@TROCARAQUI@@@";
					$Logs = new logs;
					$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, $AcaoID);
				}
			}

			return redirect()->route("list.@@TROCARAQUI@@@");
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
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("delete.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			DB::table("NomeDaTabela")
				->update([
					"deleted" => "1",
				]);
			$Acao = "Excluiu TODOS os registros no Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, 0);



			return redirect()->route("list.@@TROCARAQUI@@@");
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
		$Modulo = "@@TROCARAQUI@@@";

		$permUser = Auth::user()->hasPermissionTo("delete.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}

		try {

			DB::table("NomeDaTabela")
				->update([
					"deleted" => "0",
				]);
			$Acao = "Restaurou TODOS os registros no Módulo de @@TROCARAQUI@@@";
			$Logs = new logs;
			$Registra = $Logs->RegistraLog(4, $Modulo, $Acao, 0);



			return redirect()->route("list.@@TROCARAQUI@@@");
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

		$@@TROCARAQUI@@@ = DB::table("NomeDaTabela")

			->select(DB::raw("NomeDaTabela.*, DATE_FORMAT(NomeDaTabela.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
			 
			"))
			->where("NomeDaTabela.deleted", "0");

		//MODELO DE FILTRO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM IF PARA APLICAR O FILTRO, EXCLUIR O FILTRO DE ID, DELETED E UPDATED_AT

		
		if (isset($data["@@TROCARAQUI@@@"]["nome"])) {
			$AplicaFiltro = $data["@@TROCARAQUI@@@"]["nome"];
			$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->Where("NomeDaTabela.nome",  "like", "%" . $AplicaFiltro . "%");
		}
	

		$@@TROCARAQUI@@@ = $@@TROCARAQUI@@@->get();

		$DadosNomeDaTabela = [];
		foreach ($@@TROCARAQUI@@@ as $NomeDaTabelas) {
			if ($NomeDaTabelas->status == "0") {
				$NomeDaTabelas->status = "Ativo";
			}
			if ($NomeDaTabelas->status == "1") {
				$NomeDaTabelas->status = "Inativo";
			}
			$DadosNomeDaTabela[] = [
				//MODELO DE CA,MPO PARA VOCE COLOCAR AQUI, PARA CADA COLUNA DO BANCO DE DADOS DEVERÁ TER UM, EXCLUIR O ID, DELETED E UPDATED_AT
				'nome' => $NomeDaTabelas->nome,				
			
			];
		}
		return $DadosNomeDaTabela;
	}

	public function exportarRelatorioExcel()
	{

		$permUser = Auth::user()->hasPermissionTo("create.@@TROCARAQUI@@@");

		if (!$permUser) {
			return redirect()->route("list.Dashboard", ["id" => "1"]);
		}


		$filePath = "Relatorio_@@TROCARAQUI@@@.xlsx";

		if (Storage::disk("public")->exists($filePath)) {
			Storage::disk("public")->delete($filePath);
			// Arquivo foi deletado com sucesso
		}

		$cabecalhoAba1 = array('nome', 'placa', 'modelo', 'ano', 'cor', 'valor_compra', 'observacao', 'status', 'Data de Cadastro');

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$NomeDaTabela = $this->DadosRelatorio();

		// Define o título da primeira aba
		$spreadsheet->setActiveSheetIndex(0);
		$spreadsheet->getActiveSheet()->setTitle("@@TROCARAQUI@@@");

		// Adiciona os cabeçalhos da tabela na primeira aba
		$spreadsheet->getActiveSheet()->fromArray($cabecalhoAba1, null, "A1");

		// Adiciona os dados da tabela na primeira aba
		$spreadsheet->getActiveSheet()->fromArray($NomeDaTabela, null, "A2");

		// Definindo a largura automática das colunas na primeira aba
		foreach ($spreadsheet->getActiveSheet()->getColumnDimensions() as $col) {
			$col->setAutoSize(true);
		}

		// Habilita a funcionalidade de filtro para as células da primeira aba
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());


		// Define o nome do arquivo	
		$nomeArquivo = "Relatorio_@@TROCARAQUI@@@.xlsx";
		// Cria o arquivo
		$writer = IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->save($nomeArquivo);
		$barra = "'/'";
		$barra = str_replace("'", "", $barra);
		$writer->save(storage_path("app" . $barra . "relatorio" . $barra . $nomeArquivo));

		return redirect()->route("download2.files", ["path" => $nomeArquivo]);
	}
}

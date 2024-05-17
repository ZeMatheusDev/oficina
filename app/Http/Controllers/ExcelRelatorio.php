<?php 

	namespace App\Http\Controllers;
	use Exception;
	use App\Models\DisabledColumns;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Storage;

	use App\Models\Office;
	use Illuminate\Http\Request;
	use Illuminate\Support\Arr;
	use Inertia\Inertia;
	use stdClass;
	use PHPExcel;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;

	
    class ExcelRelatorio extends Controller
	{

		public function exportarRelatorioExcel($Data_Inicial=null,$Data_Final=null,$Cliente=null,$Motoristas=null,$CentroCusto=null){

			$DataInicial = date('Y-m-01');
			$DataFinal = date("Y-m-t");
			$Clientes = null;
			$Motorista = null;
			$CentroCustos = null;
			if($Data_Inicial && $Data_Inicial != '0'){
				$DataInicial = $Data_Inicial;
			}
			if($Data_Final && $Data_Final != '0'){
				$DataFinal = $Data_Final;
			}
	
			if($Motoristas){
				$Motorista = $Motoristas;		
			}
	
			if($Cliente){
				$Clientes = $Cliente;
			}

			if($CentroCusto){
				$CentroCustos = $CentroCusto;
			}
	
			$filePath = 'relatorio_os.xlsx';

			if (Storage::disk('public')->exists($filePath)) {
				Storage::disk('public')->delete($filePath);
				// Arquivo foi deletado com sucesso
			}
		
			$Dados = $this->DadosRecebidos($DataInicial,$DataFinal,$Clientes,$Motorista,$CentroCustos);
		
			
			$Aba1 = count($Dados->recebiveis);
						
			$cabecalhoAba1 = array('Empresa Vinculada','Número OS','Solicitante','Centro de Custo','Data Inicio','Data Término','Motorista','Cliente','Nota Fiscal','Nota de Débito');

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Define o título da primeira aba
			$spreadsheet->setActiveSheetIndex(0);
			$spreadsheet->getActiveSheet()->setTitle('Recebíveis - Clientes');

			// Adiciona os cabeçalhos da tabela na primeira aba
			$spreadsheet->getActiveSheet()->fromArray($cabecalhoAba1, null, 'A1');
			if ($Aba1 > 0) {
			// Adiciona os dados da tabela na primeira aba
			$spreadsheet->getActiveSheet()->fromArray($Dados->recebiveis, null, 'A2');
			}
			// Definindo a largura automática das colunas na primeira aba
			foreach ($spreadsheet->getActiveSheet()->getColumnDimensions() as $col) {
				$col->setAutoSize(true);
			}

			// Habilita a funcionalidade de filtro para as células da primeira aba
			$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

			$cabecalhoAba2 = array('Empresa Vinculada','Nome Completo','Nome Fila','CPF','Valor O.S.','Vales','Valores Transeferidos','Total','Comprovante de Transferência','Nota Motorista');
			$Aba2 = count($Dados->custos);

			// Define o título da segunda aba
			$spreadsheet->createSheet();
			$spreadsheet->setActiveSheetIndex(1);
			$spreadsheet->getActiveSheet()->setTitle('Custos - Motoristas'); 

			// Adiciona os cabeçalhos da tabela na segunda aba
			$spreadsheet->getActiveSheet()->fromArray($cabecalhoAba2, null, 'A1');

			// Adiciona os dados da tabela na segunda aba
			if ($Aba2 > 0) {
				$spreadsheet->getActiveSheet()->fromArray($Dados->custos, null, 'A2');
			}
				// Definindo a largura automática das colunas na segunda aba
				foreach ($spreadsheet->getActiveSheet()->getColumnDimensions() as $col) {
					$col->setAutoSize(true);
				}

				// Habilita a funcionalidade de filtro para as células da segunda aba
				$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());


			// Define o nome do arquivo
			$data = date('d_m_Y_H_i_s');
			$nomeArquivo = "relatorio_os.xlsx";
			$local = 'relatorio/'.$nomeArquivo;
			// Cria o arquivo
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save($nomeArquivo);
			$barra = "'/'";
			$barra = str_replace("'","",$barra);
		//	$writer->save(Storage::disk('relatorio')->path($nomeArquivo));
			$writer->save(storage_path('app'.$barra.'relatorio'.$barra.$nomeArquivo));
		
			return redirect()->route("download2.files",["path"=>$nomeArquivo]);
			
		}


		public function DadosRecebidos($DataInicial,$DataFinal,$Cliente,$Motoristas2,$CentroCusto=null){

		$Sessoes = Session::all();   
		$Sessoes['empresa'] = explode(',',$Sessoes['empresa']);

	
		$Recebiveis = DB::table("ordem_servico")
		->leftjoin('motorista as m', 'ordem_servico.motorista', '=', 'm.id')
		->leftjoin('cliente as cl', 'ordem_servico.cliente', '=', 'cl.id')
		->leftjoin('companies as emp', 'ordem_servico.empresa_vinculada', '=', 'emp.id')
		->select(DB::raw("ordem_servico.id, ordem_servico.numero as numero_os, m.nome_fila as nome_motorista, cl.nome_fantasia as nome_cliente, ordem_servico.nome_solicitante,DATE_FORMAT(ordem_servico.data_inicio, '%d/%m/%Y') as data_inicio, DATE_FORMAT(ordem_servico.data_termino, '%d/%m/%Y') as data_termino, ordem_servico.centro_custo, emp.name as empresa_vinculada"))
		->where("ordem_servico.data_inicio",'>=',$DataInicial)
		->where("ordem_servico.data_termino",'<=',$DataFinal)
		->where("ordem_servico.status",'1')	
		->whereIn("ordem_servico.empresa_vinculada",$Sessoes['empresa']);
		
		if(($Motoristas2) && ($Motoristas2 != '0')){
		$Recebiveis = $Recebiveis->where("ordem_servico.motorista",$Motoristas2);
		}
		
		if(($Cliente) && ($Cliente != '0')){
			$Recebiveis = $Recebiveis->where("ordem_servico.cliente",$Cliente);
		}
		if(($CentroCusto) && ($CentroCusto != '0')){
		
			$DadosCentro = DB::table("ordem_servico")->where('id',$CentroCusto)->where('deleted',"0")->first();				
				if($DadosCentro){
					$Recebiveis = $Recebiveis->Where("ordem_servico.centro_custo", $DadosCentro->centro_custo);						
				}							
		}	
	
		$Recebiveis = $Recebiveis->where("ordem_servico.deleted", "0")->get();


		$TotalRecebidos = [];
		
	
		foreach($Recebiveis as $Recebidos){
			$NF = floatval(round(DB::table("ordem_servico_controle")->where("id_ordem", $Recebidos->id)->where("tipo", '0')->SUM('total'), 2));
            $ND = floatval(round(DB::table("ordem_servico_controle")->where("id_ordem", $Recebidos->id)->where("tipo", '1')->SUM('total'), 2));

			$NotaFiscal = $NF;
			$NotaDebito = $ND;

				$TotalRecebidos[] = [
					$Recebidos->empresa_vinculada,
					$Recebidos->numero_os,
					$Recebidos->nome_solicitante,
					$Recebidos->centro_custo,
					$Recebidos->data_inicio,
					$Recebidos->data_termino,
					$Recebidos->nome_motorista,
					$Recebidos->nome_cliente,
					$NotaFiscal,
					$NotaDebito,
				];
				
		}

		$Custos = [];

			
			$Motorista = DB::table("motorista")
			->where("status","0")
			->where("deleted","0");
			if($Motoristas2){
				$Motorista = $Motorista->where("id",$Motoristas2);
			}	

			$Motorista = $Motorista->orderBy("nome_fila","asc")->get();
			foreach($Motorista as $Motoristas){
				
				
					$Empresas = DB::table("companies")->where("deleted", "0")->where("status", "1")->get();

					foreach($Empresas as $Empresa){


				$TotalOrdens = DB::table("ordem_servico")
				->where("motorista",$Motoristas->id)
				->where("empresa_vinculada",$Empresa->id)
				->where("deleted", "0")
				->where("status", "1")
				->where("data_pag_motorista",'>=',$DataInicial)
				->where("data_pag_motorista",'<=',$DataFinal);
				if(($Cliente) && ($Cliente != '0')){
					$TotalOrdens = $TotalOrdens->where("cliente",$Cliente);
				}
				if(($CentroCusto) && ($CentroCusto != '0')){				
					$DadosCentro = DB::table("ordem_servico")->where('id',$CentroCusto)->where('deleted',"0")->first();				
						if($DadosCentro){
							$TotalOrdens = $TotalOrdens->Where("centro_custo", $DadosCentro->centro_custo);						
						}							
				}
				$TotalOrdens = $TotalOrdens->count();  

				$Financeiro = DB::table("ordem_servico_folha")
				->leftJoin("ordem_servico as ordem","ordem_servico_folha.id_ordem","=","ordem.id")
				->where("ordem.motorista",$Motoristas->id)
				->where("ordem.empresa_vinculada",$Empresa->id)
				->where("ordem.status", '1')
				->where("ordem.deleted", "0")
				->where("ordem.data_inicio",'>=',$DataInicial)
				->where("ordem.data_termino",'<=',$DataFinal);
				// if(($Cliente) && ($Cliente != '0')){
				// 	$Financeiro = $Financeiro->where("ordem.cliente",$Cliente);
				// }
				// if(($CentroCusto) && ($CentroCusto != '0')){
				
				// 	$DadosCentro = DB::table("ordem_servico")->where('id',$CentroCusto)->where('deleted',"0")->first();				
				// 		if($DadosCentro){
				// 			$Financeiro = $Financeiro->Where("ordem.centro_custo", $DadosCentro->centro_custo);						
				// 		}							
				// }
				$Financeiro = $Financeiro->SUM('ordem_servico_folha.total');
				
				$Vale = DB::table("motorista_vale")
				->where("id_motorista",$Motoristas->id)
				->where("id_empresa",$Empresa->id)
				->where("deleted", "0")
				->where("data_vale",'>=',$DataInicial)
				->where("data_vale",'<=',$DataFinal)
				->SUM('valor');

				$ValorComprovantes = DB::table("financeiro_comprovantes")
				->where("id_motorista",$Motoristas->id)
				->where("deleted", "0")
				->where("periodo_inicial",'>=',$DataInicial)
				->where("periodo_inicial",'<=',$DataFinal)
				->SUM('valor');

				$Comprovantes = DB::table("financeiro_comprovantes")
				->where("id_motorista",$Motoristas->id)
				->where("deleted", "0")
				->where("periodo_inicial",'>=',$DataInicial)
				->where("periodo_inicial",'<=',$DataFinal)
				->first();

				$comp = 'Não Anexado.';
				$nf = 'Não Anexado.';
				if($Comprovantes){
					if($Comprovantes->comprovante_transferencia){
					$comp = 'Anexado!';
					}	
					if($Comprovantes->comprovante_transferencia){
						$nf = 'Anexado!';
						}	

				}
				

				if(($Vale) || ($Financeiro)){

				$nomeEmpresa = $Empresa->name;		
				
				$total = $Financeiro - $Vale - $ValorComprovantes;
				if($total == null){
					$total = 0;					
				}
				
				$total = round($total, 2);
				if ($total === "") {
					$total = 0;
				}

				if($ValorComprovantes == null){
					$ValorComprovantes = 0;
				}
				
					$ValorComprovantes = round($ValorComprovantes, 2);
				if ($ValorComprovantes === "") {
					$ValorComprovantes = 0;
				}

				if($Vale == null){
					$Vale = 0;
				}
				
					$Vale = round($Vale, 2);
				if ($Vale == "") {
					$Vale = 0;
				}

				
				$Financeiro = round($Financeiro, 2);
				if ($Financeiro == "") {
					$Financeiro = 0;
				}
				
				$Custos[] = [
					'nome_empresa' => $nomeEmpresa,
                    'nome' => $Motoristas->nome_completo,
                    'nome_fila' => $Motoristas->nome_fila,
                    'cpf' => $Motoristas->cpf,
                    'total_apagar' => $Financeiro,
                    'vale' => $Vale,
					'valor_comp' => $ValorComprovantes,
                    'total_geral' => $total,
                    'transferencia' => $comp,
                    'nf' => $nf,
				];
				
				}
				
			}

		}

			$save = new stdClass;
			$save->recebiveis = $TotalRecebidos;
			$save->custos = $Custos;

			return $save;

	}

	public function DadosRelatorioMotorista(){
		$data = Session::all();
		
		$Motorista = DB::table("motorista")
		
		->select(DB::raw("motorista.*, DATE_FORMAT(motorista.created_at, '%d/%m/%Y - %H:%i:%s') as data_final
		, DATE_FORMAT(motorista.data_nascimento, '%d/%m/%Y') as data_nascimento
		, DATE_FORMAT(motorista.data_expedicao, '%d/%m/%Y') as data_expedicao"))			
		->where("motorista.deleted","0");
		$Motorista = $Motorista->get();

		$Dadosmotorista = [];
		foreach($Motorista as $motoristas){
			if($motoristas->status == "0"){
				$motoristas->status = "Ativo";
			}
			if($motoristas->status == "1"){
				$motoristas->status = "Inativo";
			}
			if($motoristas->tipo == "0"){
				$motoristas->tipo = "Funcionário";
			}
			if($motoristas->tipo == "1"){
				$motoristas->tipo = "Prestador de Serviço";
			}

			$Dadosmotorista[] = [	
				'nome_completo' => $motoristas->nome_completo,
				'cpf' => $motoristas->cpf,
				'rg' => $motoristas->rg,
				'data_expedicao' => $motoristas->data_expedicao,
				'data_nascimento' => $motoristas->data_nascimento,
				'email' => $motoristas->email,
				'tel1' => $motoristas->tel1,
				'tel2' => $motoristas->tel2,
				'cel1' => $motoristas->cel1,
				'cel2' => $motoristas->cel2,
				'cnh' => $motoristas->cnh,
				'validade' => $motoristas->validade,
				'prazo_ctr' => $motoristas->prazo_ctr,
				'nome_fila' => $motoristas->nome_fila,
				'tipo' => $motoristas->tipo,
				'banco' => $motoristas->banco,
				'ag_pix' => $motoristas->ag_pix,
				'conta_pix' => $motoristas->conta_pix,
				'obs' => $motoristas->obs,
				'cep' => $motoristas->cep,
				'endereco' => $motoristas->endereco,
				'complemento' => $motoristas->complemento,
				'numero' => $motoristas->numero,
				'bairro' => $motoristas->bairro,
				'cidade' => $motoristas->cidade,
				'estado' => $motoristas->estado,
				'status' => $motoristas->status,
				'data_final' => $motoristas->data_final
			];
		}
		return $Dadosmotorista;
	}

	public function exportarMotoristaExcel(){

		$permUser = Auth::user()->hasPermissionTo("list.Motorista");
	
		if (!$permUser) {
			return redirect()->route("list.Dashboard",["id"=>"1"]);
		}

		
		$filePath = "Relatorio_Motorista.xlsx";

		if (Storage::disk("public")->exists($filePath)) {
			Storage::disk("public")->delete($filePath);
			// Arquivo foi deletado com sucesso
		}	
				
		$cabecalhoAba1 = array('Nome Completo','CPF','RG','Data Expedição','Data de Nascimento','E-mail','Telefone 1','Telefone 2','Celular 1','Celular 2','CNH','Validade','Prazo CTR','Nome Fila','Tipo','Banco','Agência (PIX)','Conta (PIX)','Observação','CEP','Endereço','Complemento','Número','Bairro','Cidade','Estado','Status','Data de Cadastro');

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$motorista = $this->DadosRelatorioMotorista();

		// Define o título da primeira aba
		$spreadsheet->setActiveSheetIndex(0);
		$spreadsheet->getActiveSheet()->setTitle("Motorista");

		// Adiciona os cabeçalhos da tabela na primeira aba
		$spreadsheet->getActiveSheet()->fromArray($cabecalhoAba1, null, "A1");
	
		// Adiciona os dados da tabela na primeira aba
		$spreadsheet->getActiveSheet()->fromArray($motorista, null, "A2");
		
		// Definindo a largura automática das colunas na primeira aba
		foreach ($spreadsheet->getActiveSheet()->getColumnDimensions() as $col) {
			$col->setAutoSize(true);
		}

		// Habilita a funcionalidade de filtro para as células da primeira aba
		$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

	

		// Define o nome do arquivo
		$data = date('d_m_Y_H_i_s');
		$nomeArquivo = "Relatorio_Motorista.xlsx";	
		$local = 'relatorio/'.$nomeArquivo;
		// Cria o arquivo
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save($nomeArquivo);
		$barra = "'/'";
		$barra = str_replace("'","",$barra);
	//	$writer->save(Storage::disk('relatorio')->path($nomeArquivo));
		$writer->save(storage_path('app'.$barra.'relatorio'.$barra.$nomeArquivo));
	
		return redirect()->route("download2.files",["path"=>$nomeArquivo]);
	}
}


	

	
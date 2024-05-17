<?php

	namespace App\Http\Controllers;
	
	use App\Models\DisabledColumns;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;
	use App\Models\Office;
	use Illuminate\Http\Request;
	use Illuminate\Support\Arr;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use Inertia\Inertia;
	use stdClass;
	
	class SMTP extends Controller
	{
		public function index(Request $request)
		{
			$Modulo = "SMTP";

			$permUser = Auth::user()->hasPermissionTo("list.SMTP");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}

			try{
				$data = Session::all();
				if(!isset($data["SMTP"]) || empty($data["SMTP"])){
							session(["SMTP" => array("status"=>"0", "orderBy"=>array("column"=>"created_at","sorting"=>"1"),"limit"=>"10")]);
							$data = Session::all();
						}
			
						$Filtros = new Security;
						if($request->input()){
						$Limpar = false;
						if($request->input("limparFiltros") == true){
							$Limpar = true;
						}
			
						$arrayFilter = $Filtros->TratamentoDeFiltros($request->input(), $Limpar, ["SMTP"]);	
						if($arrayFilter){
			
						session(["SMTP" => $arrayFilter]);
						$data = Session::all();
						}
						}
				$columnsTable = DisabledColumns::whereRouteOfList("list.SMTP")
				->first()
				?->columns;
	
			$SMTP = DB::table("smtp")
			->select(DB::raw("*, DATE_FORMAT(created_at, '%d/%m/%Y') as data_final"));
			
if(isset($data["SMTP"]["orderBy"])){				
	$Coluna = $data["SMTP"]["orderBy"]["column"];			
	$SMTP =  $SMTP->orderBy("smtp.$Coluna",$data["SMTP"]['orderBy']["sorting"] ? "asc" : "desc");
} else {
	$SMTP =  $SMTP->orderBy("smtp.created_at", "desc");
}




			if(isset($data["SMTP"]["searchBy"])){				
				$AplicaFiltro = $data["SMTP"]["searchBy"];			
				$SMTP = $SMTP->Where("smtp.email", "like", "%" . $AplicaFiltro . "%");	
				$SMTP = $SMTP->Where("smtp.password", "like", "%" . $AplicaFiltro . "%");			
				$SMTP = $SMTP->Where("smtp.smtp", "like", "%" . $AplicaFiltro . "%");	
				$SMTP = $SMTP->Where("smtp.porta", "like", "%" . $AplicaFiltro . "%");	
				$SMTP = $SMTP->Where("smtp.host", "like", "%" . $AplicaFiltro . "%");	
				$SMTP = $SMTP->Where("smtp.created_at", "like", "%" . $AplicaFiltro . "%");	
			

			}


	
			$SMTP = $SMTP->where("deleted", "0");
	
			$SMTP = $SMTP->paginate(($data["SMTP"]["limit"] ?: 10))
				->appends(["page", "orderBy", "searchBy", "limit"]);
	
			$Acao = "Acessou a listagem do Módulo de SMTP";
			$Registros = $this->Registros();
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
	
			return Inertia::render("SMTP/List", [
				"columnsTable" => $columnsTable,
				"Registros" => $Registros,
				"SMTP" => $SMTP,
				"Filtros" => $data["SMTP"],
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
			$Total = DB::table("smtp")	
			->where("smtp.deleted", "0")
			->count();

			$Ativos = DB::table("smtp")	
			->where("smtp.deleted", "0")
			->where("smtp.status", "0")
			->count();

			$Inativos = DB::table("smtp")	
			->where("smtp.deleted", "0")
			->where("smtp.status", "1")
			->count();

			$EsseMes = DB::table("smtp")	
			->where("smtp.deleted", "0")
			->whereMonth("smtp.created_at", $mes)
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
			$Modulo = "SMTP";
			$permUser = Auth::user()->hasPermissionTo("create.SMTP");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}
			try{

			
			$Acao = "Abriu a Tela de Cadastro do Módulo de SMTP";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);

			return Inertia::render("SMTP/Create");

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

		public function EmailCadastrado()
		{ 
			$SMTP = DB::table("smtp");
			$SMTP = $SMTP->where("deleted", "0")->first();

			return $SMTP;
		}
	
		public function store(Request $request)
		{
			$Modulo = "SMTP";

			$permUser = Auth::user()->hasPermissionTo("create.SMTP");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{

			
			$data = Session::all();
	
			$save = new stdClass;
			$save->email = $request->email;
			$save->password = $request->password;
			$save->smtp = $request->smtp;
			$save->porta = $request->porta;
			$save->host = $request->host;
			$save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));

			$save = collect($save)->toArray();
			DB::table("smtp")
				->insert($save);
			$lastId = DB::getPdo()->lastInsertId();

			$Acao = "Inseriu um Novo Registro no Módulo de SMTP";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(2,$Modulo,$Acao,$lastId);

			return redirect()->route("list.SMTP");
			
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

			return redirect()->route("list.SMTP");
			
		}
	


		public function edit()
		{
			$Modulo = "SMTP";

			$permUser = Auth::user()->hasPermissionTo("edit.SMTP");
			$permUser2 = Auth::user()->hasPermissionTo("create.SMTP");
			$permUser3 = Auth::user()->hasPermissionTo("list.SMTP");

			if ((!$permUser) && (!$permUser2) && (!$permUser3)) {
				return redirect()->route("list.Dashboard",["id"=>"1"]);
			}		

			try{
				  
			$SMTP = DB::table("smtp")
			->where("deleted", '0')
			->first();   

			$Acao = "Abriu a Tela de Edição do Módulo de SMTP";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao,0);
	
			return Inertia::render("SMTP/Edit", [
				"SMTP" => $SMTP
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
	
	
		public function update(Request $request)
		{
		  
			$Modulo = "SMTP";

			$permUser = Auth::user()->hasPermissionTo("edit.SMTP");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}


			try{

				
	
				$save = new stdClass;
				$save->email = $request->email;
				$save->password = $request->password;
				$save->smtp = $request->smtp;
				$save->porta = $request->porta;
				$save->host = $request->host;
				$save->token = md5(date("Y-m-d H:i:s").rand(0,999999999));
				
				$save = collect($save)->toArray();
		
				if($request->token){
				DB::table("smtp")
					->where("token", $request->token)
					->update($save);
				} else {
				DB::table("smtp")
					->insert($save);
				}
				

				$Acao = "Editou um registro no Módulo de SMTP";
				$Logs = new logs; 
				$Registra = $Logs->RegistraLog(3,$Modulo,$Acao,0);
				
			return redirect()->route("list.SMTP");

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


	
	
		public function delete($IDSMTP)
		{
			$Modulo = "SMTP";

			$permUser = Auth::user()->hasPermissionTo("delete.SMTP");
		
			if (!$permUser) {
				abort(403, "Usuário não autorizado.");
			}

			try{

			$AcaoID = $this->return_id($IDSMTP);
	
			DB::table("smtp")
				->where("token", $IDSMTP)
				->delete();

		
			
			$Acao = "Excluiu um registro no Módulo de SMTP";
			$Logs = new logs; 
			$Registra = $Logs->RegistraLog(4,$Modulo,$Acao,$AcaoID);
	
			return redirect()->route("list.SMTP");

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



		
public function EnviaEmail($Titulo,$mensagem,$email){		
	Date_default_timezone_set('America/Sao_Paulo');
	$MensagemFinal = $this->GeraMensagem($mensagem);

	$mail = new PHPMailer(true);

try {
	$DadosSMTP = $this->EmailCadastrado();
	
	$mail = new PHPMailer;
	$mail->isSMTP();  
	$mail->Debugoutput = 'html';
	$mail->Host = $DadosSMTP->host;
	$mail->Port = $DadosSMTP->porta;
	$mail->SMTPAuth = true;
    $mail->Username   = $DadosSMTP->email;                     
    $mail->Password   = $DadosSMTP->password;                 
	$mail->FromName = utf8_decode("Projeto Estagio ProConph");
	$mail->From     = $DadosSMTP->email;
	$mail->WordWrap = 50;                              // set word wrap
	$mail->IsHTML(true);
	$mail->SetLanguage("br", "phpMailer\language\\");    
	$mail->Subject  =  utf8_decode(''.$Titulo.'');			
	$mail->MsgHTML($MensagemFinal);
	$mail->AltBody  =  "Mensagem em HTML, por favor ative a opção em seu leitor de correio eletrônico.";
    $mail->isHTML(true);                                  //Set email format to HTML   
    $mail->Body    = $MensagemFinal;
	$mail->AddAddress($email);
    $mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}

		
	 }

	 
    
public function GeraMensagem($Dados){
	Date_default_timezone_set('America/Sao_Paulo');
	$mensagem = utf8_decode('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">                
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="icon" type="image/png" sizes="16x16" href="https://gestaolocacoes.proconph.com.br/images/logo_menu.png">
		<title>ProConph</title>
		<link rel="canonical" href="https://gestaolocacoes.proconph.com.br/images/logo_menu.png" />
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	</head>
 
	<body style="margin:0px; background: #f8f8f8; ">
		<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #003774;">
			<div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
				<table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
					<tbody>
						<tr>
							<td style="vertical-align: top; padding-bottom:30px;" align="center"><a href="#" target="_blank">
								<img src="https://gestaolocacoes.proconph.com.br/images/logo_menu.png" style="border:none; width: 35%;" ></a>
							</td>
						</tr>
					</tbody>
				</table>
				<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
				  <tbody>
					  <tr>
						  <td style="background:#0145a3; padding:20px; color:#fff; text-align:center;"> </td>
					  </tr>
				  </tbody>
				  </table>
				<div style="padding: 40px; background: #fff;">			
				'.$Dados.'

				<center>
						<a href="https://gestaolocacoes.proconph.com.br/login" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background:#0145a3; border-radius: 60px; text-decoration:none;">Login</a>
					</center><br>

				</div>  
				<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
					  <tbody>
						  <tr>
							  <td style="background:#000; padding:20px; color:#fff; text-align:center;"> </td>
						  </tr>
					  </tbody>
					  </table>          
			</div>
		</div>
	</body>
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	</html>');

	return $mensagem;

}


	}
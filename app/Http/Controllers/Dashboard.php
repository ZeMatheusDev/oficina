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
	
	class Dashboard extends Controller
	{

		public function index(Request $request, $id=0)
		{
			$Modulo = "Dashboard";

			try{

			
			$Acao = "Acessou a listagem do Módulo de Dashboard Financeiro";
			$Logs = new logs; 
			// $Registros = $this->Registros();
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
	
			return Inertia::render("Dashboard/Dashboard",
			[	"AlertaError"=>$id,	]);

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


		public function Calendario(Request $request)
		{
			$Modulo = "Calendário";
			$permUser = Auth::user()->hasPermissionTo("list.DashboardCalendario");
		
			if (!$permUser) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}

			try{
				$solicitationEvents = [];
				
			return Inertia::render("Dashboard/Calendario",['Solicitations' => $solicitationEvents]);

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
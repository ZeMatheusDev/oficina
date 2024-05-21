<?php

	namespace App\Http\Controllers;
	use Exception;
	use App\Models\DisabledColumns;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Session;
	use App\Models\Office;
	use App\Models\User;
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
			
			$empresas = DB::table('companies')->get();
			$distanciaMinima = PHP_FLOAT_MAX; 
			$empresaMaisProxima = null;
			$coordenadasUsuario = DB::table('users')->where('id', Auth::user()->id)->first();
			$latitudeUsuario = $coordenadasUsuario->latitude;
			$longitudeUsuario = $coordenadasUsuario->longitude;

			foreach($empresas as $empresa){
				foreach($empresas as $empresa) {
					$distancia = $this->calcularDistancia($latitudeUsuario, $longitudeUsuario, $empresa->latitude, $empresa->longitude);
					if ($distancia < $distanciaMinima) {
						$distanciaMinima = $distancia;
						$empresaMaisProxima = $empresa;
					}
				}
				
			}
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
			$Acao = "Acessou a listagem do Módulo de Dashboard Financeiro";
			$Logs = new logs; 
			// $Registros = $this->Registros();
			$Registra = $Logs->RegistraLog(1,$Modulo,$Acao);
			$empresaSelecionada = session()->all()['empresa_nome'];
			return Inertia::render("Dashboard/Dashboard",
			[	"AlertaError"=>$id,
				"hasRole" => $usuario != null,
				"empresaMaisProxima" => $empresaMaisProxima->name,
				"Empresas" => $empresas,
				"empresaSelecionada" => $empresaSelecionada,
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

		function calcularDistancia($latitude1, $longitude1, $latitude2, $longitude2) {
			// Raio da Terra em quilômetros
			$raioTerra = 6371;
			
			// Conversão de graus para radianos
			$latitude1 = deg2rad($latitude1);
			$longitude1 = deg2rad($longitude1);
			$latitude2 = deg2rad($latitude2);
			$longitude2 = deg2rad($longitude2);
		
			// Diferença de coordenadas
			$deltaLatitude = $latitude2 - $latitude1;
			$deltaLongitude = $longitude2 - $longitude1;
		
			// Fórmula da distância euclidiana
			$a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) + cos($latitude1) * cos($latitude2) * sin($deltaLongitude / 2) * sin($deltaLongitude / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$distancia = $raioTerra * $c;
		
			return $distancia;
		}

		public function atualizarLoc(Request $request, $latitude, $longitude){
			User::where('id', Auth::user()->id)->update(['latitude' => $latitude, 'longitude' => $longitude]);
			return response()->json('Atualizado com sucesso');
		}


		public function atualizarEmpresa($empresa_id){
			session(['empresa' => $empresa_id]);
			$empresa_nome = DB::table('companies')->where('id', $empresa_id)->first();
			session(['empresa_nome' => $empresa_nome->name]);
			return response()->json($empresa_nome->name);
		}

		public function Calendario(Request $request)
		{
			$Modulo = "Calendário";
			$permUser = Auth::user()->hasPermissionTo("list.DashboardCalendario");
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();

			if (!$permUser) {
				return redirect()->route("list.Dashboard",['id'=>'1']);
			}

			try{
				$solicitationEvents = [];
			$empresaSelecionada = session()->all()['empresa_nome'];
				
			return Inertia::render("Dashboard/Calendario",['Solicitations' => $solicitationEvents, "hasRole" => $usuario != null, 'empresaSelecionada' => $empresaSelecionada]);

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
<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Exception;
use App\Models\DisabledColumns;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Models\aluguelCarro;
use App\Models\HistoricoAluguelCarro;
use App\Models\ConfigCarross;
use App\Models\Consertos;
use App\Models\HistoricoConsertos;
use App\Models\vendaCarros;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use stdClass;

class Conserto extends Controller
{
    public function index(Request $request){
        $Modulo = "Conserto";

			try{
			$data = Session::all();
			$usuario = DB::table('model_has_roles')->where('model_id', Auth::user()->id)->where('role_id', 6)->first();
            $user = DB::table('users')->where('users.id', Auth::user()->id)->join('model_has_roles', 'model_id', 'users.id')->first();
            $usuario_id = $user->id;
            $problemas_motos = DB::table('problemas_motos')->get();
            $problemas_carros = DB::table('problemas_carros')->get();
		    $Users = DB::table('users')->get();
		    $usuario_nome = $user->name;
            
			return Inertia::render("conserto", [
				"hasRole" => $usuario != null,
                "problemas_motos" => $problemas_motos,
			    'categoria' => $user->role_id,	
			    'usuario_nome' => $usuario_nome,
                'usuario_id' => $usuario_id,
                "problemas_carros" => $problemas_carros,
			    'Users' => $Users,
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

    public function consertar(Request $request){
        if($request->tipoVeiculo == 'carro'){
            $consertoNome = DB::table('problemas_carros')->where('id', $request->problemaCarro_id)->first();
        }
        elseif($request->tipoVeiculo == 'moto'){
            $consertoNome = DB::table('problemas_Motos')->where('id', $request->problemaMoto_id)->first();
        }
		$valorFormatado = explode('$', $request->valor);
        $conserto = new Consertos();
        $conserto->problema = $consertoNome->tipo_problema;
        $conserto->veiculo = $request->tipoVeiculo;
        $conserto->valor_cobrado = $valorFormatado[1];
        $conserto->placa = $request->placa;
        $conserto->usuario_id = $request->usuario_id;
        $conserto->data_finalizacao = $request->data_finalizacao;
        $conserto->save();
        return redirect()->route('home');
    }

	public function buscarDadosConserto($usuario_id){
		$alugueisCarros = DB::table('consertos')
			->join('users', 'consertos.usuario_id', '=', 'users.id')
			->where('consertos.usuario_id', $usuario_id)
			->select('consertos.*', 'users.name as user_name')
			->get();
		return response()->json($alugueisCarros);
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

    public function meusConsertos(Request $request){
		$Modulo = "ConfigCarros";
		$dataDeHoje = Carbon::now();
		$consertos = DB::table('consertos')->get();
		foreach($consertos as $conserto){
			$fimConserto = Carbon::createFromFormat('d/m/Y', $conserto->data_finalizacao);
			if($fimConserto < $dataDeHoje){
				$historico = new HistoricoConsertos();
				$historico->problema = $conserto->problema;
				$historico->valor_cobrado = $conserto->valor_cobrado;
				$historico->veiculo = $conserto->veiculo;
				$historico->placa = $conserto->placa;
				$historico->usuario_id = $conserto->usuario_id;
				$historico->data_finalizacao = $conserto->data_finalizacao;
				$historico->save();
				Consertos::where('id', $conserto->id)->delete();
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
        $meusConsertos = DB::table('consertos')->where('usuario_id', $usuario_id);
        $meusConsertos = $meusConsertos->paginate(($data["ConfigCarros"]["limit"] ?: 10))
        ->appends(["page", "orderBy", "searchBy", "limit"]);
        return Inertia::render("meusConsertos", [
            "hasRole" => $usuario != null,
            'categoria' => $user->role_id,	
            'usuario_nome' => $usuario_nome,
            'usuario_id' => $usuario_id,
            'Users' => $Users,
			"Filtros" => $data["ConfigCarros"],
            'meusConsertos' => $meusConsertos,
			"Registros" => $Registros,

        ]);
	
     
    }

    public function todosConsertos(){

    }
}

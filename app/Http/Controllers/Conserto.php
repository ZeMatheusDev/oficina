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
        $conserto = new Consertos();
        $conserto->problema = $consertoNome->tipo_problema;
        $conserto->veiculo = $request->tipoVeiculo;
        $conserto->valor_cobrado = $request->valor;
        $conserto->placa = $request->placa;
        $conserto->usuario_id = $request->usuario_id;
        $conserto->data_finalizacao = $request->data_finalizacao;
        $conserto->save();
        return redirect()->route('home');
    }
}

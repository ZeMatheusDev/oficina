<?php


namespace App\Http\Controllers;
use Exception;

use App\Models\DisabledColumns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use stdClass;

class Utils extends Controller
{
    public function getAddressViaCep($cep)
    {

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) < 8) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'viacep.com.br/ws/' . $cep . '/json/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    public function toggleColumnsTables(Request $request)
    {
        $columns = DisabledColumns::whereRouteOfList($request->route_of_list)
            ->first();

        if ($columns) {
            $columns->columns = $request->columns;
            $columns->save();
            return redirect()->back();
        }

        $request->user_id = auth()->user()->id;

        DisabledColumns::create([
            'user_id' => Auth::user()->id,
            'route_of_list' => $request->route_of_list,
            'columns' => $request->columns,
        ]);

        return redirect()->back();
    }

    public function PegaValoresServicos($id)
    {
        $id = preg_replace('/[^0-9]/', '', $id);
        $Valores = DB::table("servicos")
        ->where("deleted","0")
        ->where("status","0")
        ->where("id",$id)
        ->first();
              
        $response['success'] = true;
        $response['valor'] = $Valores;         
        return $response;
    }

    public function PegaValoresDiarias($id)
    {
        $id = preg_replace('/[^0-9]/', '', $id);
        $Valores = DB::table("tipos_diarias")
        ->where("deleted","0")
        ->where("status","0")
        ->where("id",$id)
        ->first();
              
        $response['success'] = true;
        $response['valor'] = $Valores;         
        return $response;
    }

    public function getCentrodeCustos($Empresa, $Cliente, $DataInicial, $Data_Final,)
    {

        $centros_de_custo = DB::table('ordem_servico')
        ->select(DB::raw("COALESCE(centro_custo, 'Não Informado') as centro_custo, MAX(id) as id"))
        ->where('cliente', $Cliente)
        ->where('empresa_vinculada', $Empresa)
        ->where('status', '1')
        ->where('deleted', '0')
        ->whereBetween('data_inicio', [$DataInicial, $Data_Final])
        ->whereBetween('data_termino', [$DataInicial, $Data_Final])
        ->groupBy('centro_custo')
        ->get();

    return  $centros_de_custo;

    }

    public function getCentrodeCustos2($Cliente, $DataInicial, $Data_Final,)
    {

    $centros_de_custo = DB::table('ordem_servico')
    ->select('centro_custo')
    ->where('cliente', $Cliente)
    ->where('data_inicio','>=', $DataInicial)
    ->where('data_termino', '<=', $Data_Final)
    ->where('status', '1')
    ->where('deleted', '0')
    ->groupBy('centro_custo')
    ->get();

    return  $centros_de_custo;

    }

    public function getOrdensDados($Empresa, $Cliente, $DataInicial, $Data_Final, $tipo)
    {
     

        $OrdensServicos = DB::table('ordem_servico')
        ->select(DB::raw("ordem_servico.id, ordem_servico.numero, ordem_servico.centro_custo, 
        (SELECT count(id) FROM financeiro WHERE id_ordem_servico = ordem_servico.id 
        AND nota = $tipo AND data_realizada IS NULL) as totalDisp"))
        ->where('ordem_servico.cliente', $Cliente)
        ->where('ordem_servico.empresa_vinculada', $Empresa)
        ->where('ordem_servico.data_inicio', '>=', $DataInicial)
        ->where('ordem_servico.data_termino', '<=', $Data_Final)
        ->where('ordem_servico.status', '1')
        ->where('ordem_servico.deleted', '0')
        ->having('totalDisp', '>', 0)
        ->orderBy('ordem_servico.id', 'asc')
        ->get();

    return  $OrdensServicos;

    }

    public function ValoresOrdens($Ordens,$tipo)
    {    

        

        $Ordens = isset($Ordens) ? $Ordens : ''; // verificação adicional
        $Ordens = explode(',', $Ordens);
        $Ordens = array_filter($Ordens);

      
            
        $OrdensServicos = DB::table('financeiro')
        ->whereIn('id_ordem_servico', $Ordens)
        ->where('deleted', '0')
        ->where('nota', $tipo)
        ->whereNull('data_realizada')
        ->SUM('valor');

      

        return  $OrdensServicos;

    }



    
    


    public function getMotoristaDados($Motorista, $Data_Inicial, $Data_Final,)
    {

        $data = [];
        $MinhasOrdens = '';
        $Ver_Comp = 0;
        $Ver_Valid = 0;

        $Comprovante = DB::table("financeiro_comprovantes")
			->where("deleted","0")
            ->where("id_motorista",$Motorista)
            ->whereNull("nf_motorista")
            ->first();

            if($Comprovante){
                $Ver_Comp = 1;
            }

        $Validado = DB::table("financeiro_comprovantes")
			->where("deleted","0")
            ->where("id_motorista",$Motorista)
            ->where("periodo_inicial",'=',$Data_Inicial)
            ->where("periodo_final",'=',$Data_Final)
            ->where("status",'=','0')
            ->first();

            if($Validado){
                $Ver_Valid = 1;
            }

			$Motoristas = DB::table("motorista")
			->where("status","0")
			->where("deleted","0")
            ->where("id",$Motorista)->first();
				
				$TotalOrdens = DB::table("ordem_servico")
				->where("motorista",$Motoristas->id)
				->where("deleted", "0")
				->where("status", "1")
				->where("data_inicio",'>=',$Data_Inicial)
				->where("data_termino",'<=',$Data_Final)
				->get();  
                $idOrdens = '';
                foreach($TotalOrdens as $ordens){
                    $MinhasOrdens .= ''.$ordens->numero.', ';
                    $idOrdens .= $ordens->id.',';
                }


                $Financeiro = DB::table("ordem_servico_folha")
				->leftJoin("ordem_servico as ordem","ordem_servico_folha.id_ordem","=","ordem.id")
				->where("ordem.motorista",$Motoristas->id)
				->where("ordem.status", '1')
				->where("ordem.deleted", "0")
				->where("ordem.data_inicio",'>=',$Data_Inicial)
				->where("ordem.data_termino",'<=',$Data_Final);				
				$Financeiro = $Financeiro->SUM('ordem_servico_folha.total');
			
				
				$Vale = DB::table("motorista_vale")
				->where("id_motorista",$Motoristas->id)
				->where("deleted", "0")
				->where("data_vale",'>=',$Data_Inicial)
                ->where("data_vale",'<=',$Data_Final)
				->SUM('valor');

                $Valee = DB::table("motorista_vale")
				->where("id_motorista",$Motoristas->id)
				->where("deleted", "0")
				->where("data_vale",'>=',$Data_Inicial)
                ->where("data_vale",'<=',$Data_Final)
				->get();

                $Total = $Financeiro-$Vale;
	
                $OrdemServicos = $MinhasOrdens.'  Total de Serviços: R$ '.number_format($Financeiro,2).' |  Total de Vales: R$ '.number_format($Vale,2).'';
                $idVale = '';
                foreach($Valee as $vales){
                    $idVale .= $vales->id.',';
                }
                
                $save = new stdClass;
                $save->total = $Total;
                $save->os_vinculadas = $OrdemServicos;
                $save->Ver_Comp = $Ver_Comp;
                $save->Ver_Valid = $Ver_Valid;
                $save->id_ordens = $idOrdens;
                $save->id_vale = $idVale;
				


        if(($Total)||($MinhasOrdens)){
        $response['success'] = true;
        $response['valor'] = $save;         
        return $response;
        }
    }


    public function Query($sql)
    {			
                
        DB::enableQueryLog();
        $sql = $sql->toSql();
        dd($sql);
    }

    





    
}

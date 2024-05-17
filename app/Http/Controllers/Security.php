<?php

namespace App\Http\Controllers;

use App\Actions\UserAction;
use App\Http\Requests\FormUser;
use App\Mail\SendTemporaryPassword;
use App\Models\DisabledColumns;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use \Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Session\Store;
use stdClass;


class Security extends Controller
{
    public function TratamentoDeFiltros(array $array, bool $reset = false, array $NomeVariavel = []): array
    {
        $sanitizedArray = [];
        $data = Session::all();
        $session = Session::getFacadeRoot();
        $vazio = array();
        unset($array['searchBy']);
        unset($array['page']);
        unset($array['0']);
        unset($array['1']);
        unset($array['2']);
        unset($array['3']);

        if ((isset($array['loja'])) && (is_array($array['loja']))) {
            $NovaLoja = null;
			foreach($array['loja'] as $LojaSele){
				$NovaLoja .= $LojaSele.',';
			}
			if(isset($NovaLoja)){
				$array['loja'] = substr($NovaLoja,0,-1);
			}
        }


       
        if ((isset($array['diretoria'])) && (is_array($array['diretoria']))) {
            $Novadiretoria = null;
			foreach($array['diretoria'] as $LojaSele){
				$Novadiretoria .= $LojaSele.',';
			}
			if(isset($Novadiretoria)){
				$array['diretoria'] = substr($Novadiretoria,0,-1);
			}
        }

        if (((isset($array['regional'])) && is_array($array['regional']))) {
            $Novaregional = null;
			foreach($array['regional'] as $LojaSele){
				$Novaregional .= $LojaSele.',';
			}
			if(isset($Novaregional)){
				$array['regional'] = substr($Novaregional,0,-1);
			}
        }
       
      
        $status = 0;
        if(($NomeVariavel[0] == 'GestaoRotina') || ($NomeVariavel[0] == 'Users')){
            $status = 1;
        }

      
        if($array == $vazio){            
           return array();
            $array = array("status"=>$status, "orderBy"=>array("column"=>"created_at","sorting"=>"0"), "limit"=>"10");
        }

       
      

        if ($reset) {     
            if(($NomeVariavel[0] == 'GestaoRotina') || ($NomeVariavel[0] == 'UsuariosModelo')){
                $status = 1;
            }                 
            $array = array("status"=>$status, "orderBy"=>array("column"=>"created_at","sorting"=>"0"), "limit"=>"10");

            session([$NomeVariavel[0] => array("status"=>$status, "orderBy"=>array("column"=>"created_at","sorting"=>"0"), "limit"=>"10", "tipo_tempo"=>"0", "tipo_local"=>"0")]);  

            return $array;
        }          

        if (!array_key_exists('status', $array)) 
        {             
            $data = Session::all();            
            $array['status'] = $data[$NomeVariavel[0]]['status'];
        }
       

      
        foreach ($array as $key => $value) {     
           
            if (is_array($value) && $key != 'orderBy') {
                $value = '';
            }

         
            $sanitizedValue = $value;

            if ($key != 'orderBy') {
                // remove todos os tags HTML e PHP do valor
                $sanitizedValue = strip_tags($value);

                // substitui caracteres especiais por entidades HTML
                $sanitizedValue = htmlspecialchars($sanitizedValue, ENT_QUOTES, 'UTF-8');

                // remove todos os caracteres que não são letras, números, espaço, "-", "/", ou ","
                $sanitizedValue = preg_replace('/[^a-zA-Z0-9\s\/\-,]/', '', $sanitizedValue);

                // remove espaços no começo e final do valor
                $sanitizedValue = trim($sanitizedValue);
            
            }  

            if((is_array($value) && $key == 'orderBy')){
                $session->forget("{$NomeVariavel[0]}.{$key}");
            }
            // verifica se o valor não está vazio após a sanitização
            if (isset($sanitizedValue) && $sanitizedValue !== '') {
                // armazena o valor sanitizado no array de valores sanitizados
                $sanitizedArray[$key] = $sanitizedValue;    
                // armazena o valor sanitizado na sessão, se for diferente do valor original
                if ($session->has("{$NomeVariavel[0]}.{$key}") && $session->get("{$NomeVariavel[0]}.{$key}") !== $sanitizedValue) {
                    $session->put("{$NomeVariavel[0]}.{$key}", $sanitizedValue);
                }
            } else {
                // remove o valor da sessão, se existir
                $session->forget("{$NomeVariavel[0]}.{$key}");
            }   
         
        }
      
     

        if (!array_key_exists('status', $sanitizedArray)) {           
            if(array_key_exists('status', $data[$NomeVariavel[0]])){
                $session->put("{$NomeVariavel[0]}.status", $data[$NomeVariavel[0]]['status']);
                $sanitizedArray['status'] = $data[$NomeVariavel[0]]['status'];
            } else {
                $session->put("{$NomeVariavel[0]}.status", $status);
                $sanitizedArray['status'] = $status;
            }
        }

        if (!array_key_exists('orderBy', $sanitizedArray)) {          
            if(array_key_exists('orderBy', $data[$NomeVariavel[0]])){           
                $session->put("{$NomeVariavel[0]}.orderBy", $data[$NomeVariavel[0]]['orderBy']);
                $sanitizedArray['orderBy'] = $data[$NomeVariavel[0]]['orderBy'];
            } else {
                $orderBy = array("column"=>"created_at","sorting"=>"0");
                $session->put("{$NomeVariavel[0]}.orderBy", $orderBy);
                $sanitizedArray['orderBy'] = $orderBy;
            }
        }   

        if (!array_key_exists('limit', $sanitizedArray)) {          
            if(array_key_exists('limit', $data[$NomeVariavel[0]])){           
                $session->put("{$NomeVariavel[0]}.limit", $data[$NomeVariavel[0]]['limit']);
                $sanitizedArray['limit'] = $data[$NomeVariavel[0]]['limit'];
            } else {
                $session->put("{$NomeVariavel[0]}.limit", 10);
                $sanitizedArray['limit'] = 10;
            }
        }  

  
        
        $ArraySessao = $data[$NomeVariavel[0]];
        if (!is_array($ArraySessao)) {
            $ArraySessao = array($ArraySessao);
        }

        if (!is_array($sanitizedArray)) {
            $sanitizedArray = array($sanitizedArray);
        }

       
        $result = array_replace($ArraySessao, $sanitizedArray);
        unset($result['{status}']);
        unset($result['{orderBy}']);

        // print_r($result); die;
       
        session([$NomeVariavel[0] => $result]);   

        $sanitizedArray = $result;      
        return $sanitizedArray;

    }
    

    
}

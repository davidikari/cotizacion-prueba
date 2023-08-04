<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotizadorController extends Controller
{
    public function mostrarCotizador()
    {
        $parametro1 = "Valor del primer parámetro";
        $parametro2 = "Valor del segundo parámetro";

        return view('cotizador', compact('parametro1', 'parametro2'));
    }


    public function cotizarEnvio(Request $request){
        
        $origen = $request->input('origen');
        $destino = $request->input('destino');
        $kilos = $request->input('kilos');

////// obtenemos el token//////
        $url ='https://mexlv.epresis.com/api/v1/getDataUser.json';
        $curl = curl_init();
        $data = array('tipo_tienda' => 'vtex');
        $postFields = http_build_query($data);
        curl_setopt($curl, CURLOPT_CAINFO, public_path('certificates/cacert.pem'));
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postFields
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            $token = "cURL Error #:" . $err;
        } else {
            $responseJsonDecode = json_decode($response, true);
            if (isset($responseJsonDecode['data'][2]['token'])) {
                $token = $responseJsonDecode['data'][2]['token'];
            } else {
               return response()->json(['success' => false, 'message' => 'error' ]);
            }
        }
////// obtenemos el token  fin//////
////// consultamos la cotizacion///////
        $url = "https://mexlv.epresis.com/api/v2/cotizador.json";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CAINFO, public_path('certificates/cacert.pem'));
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>" {
                            \"api_token\": \"".$token."\",
                            \"codigo_servicio\": \"SERVICIO_NORMAL\",
                            \"cp_origen\": \"".$origen."\",
                            \"cp_destino\": \"".$destino."\",
                            \"tiempo\": \"\",
                            \"destino\": \"\",
                            \"is_urgente\": false,
                            \"valor_declarado\": 100.25,
                            \"productos\": [{
                                \"bultos\": 2,
                                \"peso\": ".$kilos.",
                                \"descripcion\": \"Caja de zapatos roja\",
                                \"dimensiones\": {
                                    \"alto\": 0.25,
                                    \"largo\": 0.25,
                                    \"profundidad\": \"0.25\"
                                }
                            }]
                        }"
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            $responseJsonDecode = json_decode($response, true);
            if (isset($responseJsonDecode['importe_total_flete'])) {
                $valor = $responseJsonDecode['importe_total_flete'];
                return response()->json(['success' => true, 'message' => $valor ]);
            } else {
                return response()->json(['success' => false, 'message' => 'error' ]);
            }
        }
        return response()->json(['success' => false, 'message' => 'sin respuesta']);


        
    }
}

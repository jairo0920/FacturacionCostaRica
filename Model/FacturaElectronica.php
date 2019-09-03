<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FacturaScripts\Plugins\FacturacionCostaRica\Model;

use FacturaScripts\Core\Model\Base;
use FacturaScripts\Core\App\AppSettings;

/**
 * Description of FacturaElectronica
 *
 * @author jcruz
 */
class FacturaElectronica extends Base\ModelClass {
    //put your code here
    use Base\ModelTrait;
    
    public $coddivisa;
    
    public function clear() {
        parent::clear();
        $this->coddivisa = AppSettings::get('default', 'coddivisa');
    }
    
    public static function tableName(): string {
        return '';
    }
    
    public static function primaryColumn(): string {
        return '';
    }

    function exists_bitacora($idfactura)
    {
        return $this->db->select("SELECT * FROM " . "bitacora_envio_facturas" . " WHERE idfactura = " . $this->var2str($idfactura) . ";");
    }

    public function cron_job()
    {
        $data = $this->db->select("SELECT * FROM facturascli WHERE clave IS NOT NULL AND xml IS NOT NULL AND xmlFirmado IS NOT NULL AND status IS NULL ORDER BY idfactura ASC;");
        if($data) {
          $facturas_electronica = new tpv_facturas_electronica();
          echo "\nLlamando la funcion get_access_token('new')";
          $facturas_electronica->get_access_token('new');
           foreach($data as $d) {
             $dateActual = new DateTime();
             if ($facturas_electronica->fecha_access_token < $dateActual) {
               echo "\nLlamando la funcion get_access_token('old')";
               $facturas_electronica->get_access_token('old');
             }
             $facturas_electronica->clave = $d['clave'];
             $facturas_electronica->xmlFirmado = $d['xmlFirmado'];
             $facturas_electronica->fecha_emision = $d['fechaemision'];

             echo "\nLlamando la funcion get_EnviarTiqueteElectronico()"." ... ".$facturas_electronica->clave;
             $facturas_electronica->get_EnviarTiqueteElectronico();
             echo "\nEstado de la clave: ".$facturas_electronica->clave." ... "." Estado: ".$facturas_electronica->status;
             if ($facturas_electronica->status == 202 OR $facturas_electronica->status == 400 OR $facturas_electronica->status == 403){
                sleep(15);
                echo "\nLlamando la funcion get_ConsultarTiqueteElectronico()";
                $facturas_electronica->get_ConsultarTiqueteElectronico();
             } else {
               // code...
               $facturas_electronica->status = NULL;
               $facturas_electronica->text = NULL;
               $facturas_electronica->fechaaceptacion = NULL;
               $facturas_electronica->indestado = NULL;
               $facturas_electronica->respuestaxml = NULL;
             }
             echo "\nEstado del envio: ".$facturas_electronica->clave." ... "." indestado: ".$facturas_electronica->indestado."  .............  ";

             if ($facturas_electronica->exists_bitacora($d['idfactura'])){
               $sql = "UPDATE " . "bitacora_envio_facturas" .
                   " SET fechaenvio = " . "NOW()" .
                   ", status = " . $this->var2str($facturas_electronica->status) .
                   ", text = " . $this->var2str($facturas_electronica->text) .
                   ", fechaaceptacion = " . $this->var2str($facturas_electronica->fechaaceptacion) .
                   ", indestado = " . $this->var2str($facturas_electronica->indestado) .
                   ", respuestaxml = " . $this->var2str($facturas_electronica->respuestaxml) .
                   "  WHERE idfactura = '" . $d['idfactura'] . "';";
             } else {
               $sql = "INSERT INTO " . "bitacora_envio_facturas" . " (
                  idfactura,clave,fechaemision,emi_tipoIdentificacion,emi_numeroIdentificacion,
                  client_id,comprobantexml,fechaenvio,status,text,fechaaceptacion,
                  indestado,respuestaxml) VALUES (" . $d['idfactura'] .
                   "," . $this->var2str($facturas_electronica->clave) .
                   "," . $this->var2str($facturas_electronica->fecha_emision) .
                   "," . $this->var2str("01") .
                   "," . $this->var2str("203900137") .
                   "," . $this->var2str($facturas_electronica->client_id) .
                   "," . $this->var2str($facturas_electronica->xmlFirmado) .
                   "," . "NOW()" .
                   "," . $this->var2str($facturas_electronica->status) .
                   "," . $this->var2str($facturas_electronica->text) .
                   "," . $this->var2str($facturas_electronica->fechaaceptacion) .
                   "," . $this->var2str($facturas_electronica->indestado) .
                   "," . $this->var2str($facturas_electronica->respuestaxml) .
                   ");";
             }
             if ($facturas_electronica->status == 202 OR $facturas_electronica->status == 400 OR $facturas_electronica->status == 403){
               $sql = $sql . " UPDATE " . "facturascli" .
                   " SET status = " . $this->var2str($facturas_electronica->status) .
                   ", fechaaceptacion = " . $this->var2str($facturas_electronica->fechaaceptacion) .
                   ", indestado = " . $this->var2str($facturas_electronica->indestado) .
                   ", respuestaxml = " . $this->var2str($facturas_electronica->respuestaxml) .
                   "  WHERE idfactura = " . $d['idfactura'] . ";";
             }
             //echo $sql;
             $this->db->exec($sql);
           }
        }
    }

    function get_access_token($tipo)
    {

      $curl = curl_init();
      if ($tipo == 'new'){
        $params = array(
          'w' => 'token',
          'r' => 'gettoken',
          'grant_type'=> 'password',
          'client_id'=> $this->client_id,
          'username'=> $this->username,
          'password'=> $this->password);
      } else {
        $params = array(
          'w' => 'token',
          'r' => 'refresh',
          'grant_type'=> 'refresh_token',
          'client_id'=> $this->client_id,
          'refresh_token'=> $this->refresh_token);
      }
      $data_string = json_encode($params);
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.posventa.cr/api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $json =  json_decode($response, true);
      }

      $this->access_token = $json["resp"]["access_token"];
      $this->expires_in = $json["resp"]["expires_in"];
      $this->refresh_expires_in = $json["resp"]["refresh_expires_in"];
      $this->refresh_token = $json["resp"]["refresh_token"];
      $this->token_type = $json["resp"]["token_type"];
      $this->id_token = $json["resp"]["id_token"];
      $this->notbeforepolicy = $json["resp"]["not-before-policy"];
      $this->session_state = $json["resp"]["session_state"];

      $this->fecha_access_token = $this->convert_fecha_expira($this->expires_in);
      $this->fecha_refresh_expires_in = $this->convert_fecha_expira($this->refresh_expires_in);
    }

    private function convert_fecha_expira($expires_segundos)
    {
      $date = new DateTime();
      $date->modify('+'.$expires_segundos.' second');
      return $date;
    }

    function get_GenerarClave()
    {

      $curl = curl_init();
      $params = array(
        'w' => 'clave',
        'r' => 'clave',
        'tipoDocumento'=> $this->tipoDocumento,
        'tipoCedula'=> 'fisico',
        'cedula'=> $this->cedula,
        'codigoPais'=> '506',
        'consecutivo'=> $this->consecutivo_factura,
        'situacion'=> $this->situacion_fe,
        'codigoSeguridad'=> $this->codigoSeguridad);
      $data_string = json_encode($params);
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.posventa.cr/api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $json =  json_decode($response, true);
      }

      $this->clave = $json["resp"]["clave"];
      $this->consecutivo = $json["resp"]["consecutivo"];
      $this->length = $json["resp"]["length"];
    }

    function get_GenerarTiqueteElectronico()
    {

      $curl = curl_init();
      $params = array(
        'w' => 'genXML',
        'r' => 'gen_xml_te',
        'clave'=> $this->clave,
        'consecutivo'=> $this->consecutivo,
        'fecha_emision'=> $this->fecha_emision,//"'".$this->fechaemision."'",//$this->fecha_emision,//'2019-01-02T10:00:00-06:00',
        'emisor_nombre'=> $this->emisor_nombre,
        'emisor_tipo_indetif'=> $this->tipo_indetif_fe,
        'emisor_num_identif'=> $this->cedula,
        'nombre_comercial' => $this->nombre_comercial,
        'emisor_provincia'=> $this->idProvincia_fe,//'2',
        'emisor_canton'=> $this->idCanton_fe,//'02',
        'emisor_distrito'=> $this->idDistrito_fe,//'02',
        'emisor_barrio'=> $this->idBarrio_fe,//'13',
        'emisor_otras_senas'=> $this->direccion,//'Frente a la escuela',
        'emisor_cod_pais_tel'=> '506',
        'emisor_tel'=> $this->emisor_tel,
        'emisor_cod_pais_fax'=> '506',
        'emisor_fax'=> '00000000',
        'emisor_email'=> 'jairo@cruz.cr',
        'condicion_venta'=> '01', //Condiciones de la venta: 01 Contado, 02 Crédito, 03 Consignación, 04 Apartado, 05 Arrendamiento con opción de compra, 06 Arrendamiento en función financiera, 99 Otros
        'plazo_credito'=> '0',
        'medio_pago'=> $this->medio_pago,//'01', //>Corresponde al medio de pago empleado: 01 Efectivo, 02 Tarjeta, 03 Cheque, 04 Transferencia - depósito bancario, 05 - Recaudado por terceros, 99 Otros
        'cod_moneda'=> 'CRC',
        'tipo_cambio'=> '600',
        'total_serv_gravados'=> '0',
        'total_serv_exentos'=> $this->total_ventas,
        'total_merc_gravada'=> '0',
        'total_merc_exenta'=> '0',
        'total_gravados'=> '0',
        'total_exentos'=> $this->total_ventas,
        'total_ventas'=> $this->total_ventas,
        'total_descuentos'=> '0',
        'total_ventas_neta'=> $this->total_ventas,
        'total_impuestos'=> '0',
        'total_comprobante'=> $this->total_ventas,
        'otros'=> 'Muchas gracias',
        'detalles'=> $this->detalles,
        'omitir_receptor'=> 'true',
        'otrosType'=> 'OtroTexto');
      $data_string = json_encode($params);
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.posventa.cr/api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $json =  json_decode($response, true);
      }

      $this->xml = $json["resp"]["xml"];

    }

    function get_FirmarXML()
    {

      $curl = curl_init();
      $params = array(
        'w' => 'signXML',
        'r' => 'signFE',
        'p12Url'=> $this->P12Url,//'1f8d93299048212b7425b256742a3710',
        'inXml'=> $this->xml,
        'pinP12'=> $this->P12Code,//'9876',
        'tipodoc'=> 'TE');
      $data_string = json_encode($params);
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.posventa.cr/api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $json =  json_decode($response, true);
      }

      $this->xmlFirmado = $json["resp"]["xmlFirmado"];

    }

    function get_EnviarTiqueteElectronico()
    {

      $curl = curl_init();
      $params = array(
        'w' => 'send',
        'r' => 'sendTE',
        'clave'=> $this->clave,
        'fecha'=> $this->fecha_emision,//"'".$this->fechaemision."'",//'2019-01-05T12:00:00-06:00',
        'emi_tipoIdentificacion'=> $this->tipo_indetif_fe,//'01',
        'emi_numeroIdentificacion'=> $this->cedula,
        'comprobanteXml'=> $this->xmlFirmado,
        'token'=> $this->access_token,
        'client_id'=> $this->client_id);
      $data_string = json_encode($params);
      //print_r($data_string);
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.posventa.cr/api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $json =  json_decode($response, true);
      }
      //print_r($json);

      $this->status = $json["resp"]["Status"];
      $this->text = $json["resp"]["text"];

    }

    function get_ConsultarTiqueteElectronico()
    {

      $curl = curl_init();
      $params = array(
        'w' => 'consultar',
        'r' => 'consultarCom',
        'client_id'=> $this->client_id,
        'token'=> $this->access_token,
        'clave'=> $this->clave);
      $data_string = json_encode($params);
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.posventa.cr/api.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($data_string))
        ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $json =  json_decode($response, true);
      }

      $this->fechaaceptacion = $json["resp"]["fecha"];
      $this->indestado = $json["resp"]["ind-estado"];
      if ($this->indestado == "aceptado") {
        $this->respuestaxml = $json["resp"]["respuesta-xml"];
      }

    }

    function var2str($val)
    {
        return "'" . $this->db->escape_string($val) . "'";
    }
    
}

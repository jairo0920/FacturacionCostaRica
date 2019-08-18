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
 * Description of EmpresaExtra
 *
 * @author jcruz
 */
class EmpresaExtra extends Base\ModelClass {
    //put your code here
    use Base\ModelTrait;
    
    //Credenciales
    public $client_id;
    public $username;
    public $password;
    public $P12Url;
    public $P12Code;
    public $cedula;
    public $regimenMH;

    public $tipoDocumento;
    public $consecutivo_factura;
    public $codigoSeguridad;
    public $clave;
    public $consecutivo;
    public $length;
    public $fecha_emision;
    public $total_ventas;
    public $detalles;
    public $medio_pago;
    public $coddivisa;

    //Variables del gettoken
    public $access_token;
    public $expires_in;
    public $refresh_expires_in;
    public $refresh_token;
    public $token_type;
    public $id_token;
    public $notbeforepolicy;
    public $session_state;

    public $fecha_access_token;
    public $fecha_refresh_expires_in;
    //Fin Variables del gettoken

    public $status;
    public $text;
    public $indestado;
    public $respuestaxml;
    public $fechaaceptacion;

    public $xml;
    public $xmlFirmado;

    public $id;
    public $columna1;

    public $emisor_nombre;
    public $nombre_comercial;
    public $direccion;
    public $emisor_tel;
    public $idProvincia_fe;
    public $idCanton_fe;
    public $idDistrito_fe;
    public $idBarrio_fe;
    public $tipo_indetif_fe;
    public $cod_pais_tel_fe;
    public $cod_cod_pais_fax_fe;
    public $ENV_fe;
    public $situacion_fe;
    public $prodP12Url_fe;
    public $stagP12Url_fe;
    public $stagUserName_fe;
    public $stagPassword_fe;
    public $prodUserName_fe;
    public $prodPassword_fe;
    public $prodP12Code_fe;
    public $stagP12Code_fe;
    
    public function clear() {
        parent::clear();
        $this->coddivisa = AppSettings::get('default', 'coddivisa');
    }

    public static function primaryColumn(): string {
        return 'idempresaextra';
    }
    
    public static function tableName(): string {
        return 'empresasextras';
    }
}

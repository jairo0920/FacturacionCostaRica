<?php
namespace FacturaScripts\Plugins\FacturacionCostaRica\Model;

use FacturaScripts\Core\Model\Base;
use FacturaScripts\Core\App\AppSettings;

class Project extends Base\ModelClass {
    //put your code here
    use Base\ModelTrait;
    
    public $codcliente;
    public $creationdate;
    public $description;
    public $idproject;
    public $name;
    public $total;
    public $user;
    public $verified;
    public $coddivisa;

    public function clear() {
        parent::clear();
        $this->creationdate = date('d-m-y');
        $this->total = 0;
        $this->verified = false;
        $this->coddivisa = AppSettings::get('default', 'coddivisa');
    }

    public static function primaryColumn(): string {
        return 'idproject';
    }
    
    public static function tableName(): string {
        return 'projects';
    }
}

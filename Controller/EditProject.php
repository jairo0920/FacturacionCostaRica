<?php

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;
/**
 * Description of EditProject
 *
 * @author jcruz
 */
class EditProject extends EditController {
    //put your code here
    public function getModelClassName() {
        return 'Project';
    }
}

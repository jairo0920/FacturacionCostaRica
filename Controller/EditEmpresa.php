<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Controller\EditEmpresa as ParentController;

/**
 * Description of EditEmpresa
 *
 * @author jcruz
 */
class EditEmpresa extends ParentController {
    //put your code here
    protected function createViews() {
        parent::createViews();
        $this->addListView('ListEmpresaExtra', 'EmpresaExtra', 'Factura Electronica');
    }
    
    protected function loadData($viewName, $view) {
        switch ($viewName) {
            case 'ListEmpresaExtra':
                $idempresa = $this->getViewModelValue('EditEmpresa', 'idempresa');
                $where = [new DataBaseWhere('idempresa', $idempresa)];
                $view->loadData('', $where);
                break;
            default:
                parent::loadData($viewName, $view);
                break;
        }
    }
}

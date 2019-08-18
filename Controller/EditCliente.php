<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Controller\EditCliente as ParentController;

/**
 * Description of EditCliente
 *
 * @author jcruz
 */
class EditCliente extends ParentController {
    //put your code here
    protected function createViews() {
        parent::createViews();
        $this->addListView('ListProject', 'Project', 'Proyectos');
    }
    
    protected function loadData($viewName, $view) {
       switch ($viewName) {
           case 'ListProject':
               $codcliente = $this->getViewModelValue('EditCliente', 'codcliente');
               $where = [new DataBaseWhere('codcliente', $codcliente)];
               $view->loadData('', $where);
               break;

           default:
               parent::loadData($viewName, $view);
               break;
       }
    }
}

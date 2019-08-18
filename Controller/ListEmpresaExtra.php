<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

/**
 * Description of ListEmpresaExtra
 *
 * @author jcruz
 */
class ListEmpresaExtra extends ListController {
    //put your code here
    public function getPageData(): array {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'admin';
        //$pageData['menu'] = 'Fact. Costa Rica';
        $pageData['submenu'] = 'Fact. Costa Rica';
        $pageData['title'] = 'Empresa';
        $pageData['icon'] = 'fas fa-building';
        return $pageData;
    }
    
    protected function createViews() {
        $this->createViewsEmpresaExtra();
    }
    
    protected function createViewsEmpresaExtra($viewName = 'ListEmpresaExtra') {
        $this->addView($viewName, 'EmpresaExtra');
        /*$this->addSearchFields($viewName, ['name', 'description']);
        $this->addOrderBy($viewName, ['name'], 'name');
        $this->addOrderBy($viewName, ['idproject'], 'id', 2);
        $this->addOrderBy($viewName, ['creationdate'], 'date');
        
        //Filtros
        $this->addFilterCheckbox($viewName, 'verified');
        
        */
    }
}

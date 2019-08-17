<?php

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListProject extends ListController {
    //put your code here
    public function getPageData(): array {
        $pageData = parent::getPageData();
        $pageData['menu'] = 'admin';
        //$pageData['menu'] = 'Fact. Costa Rica';
        $pageData['submenu'] = 'Fact. Costa Rica';
        $pageData['title'] = 'Configuracion';
        $pageData['icon'] = 'fas fa-fist-raised';
        return $pageData;
    }
    
    protected function createViews() {
        $this->addView('ListProject', 'Project');
        $this->addSearchFields('ListProject', ['name', 'description']);
        $this->addOrderBy('ListProject', ['name'], 'name');
        $this->addOrderBy('ListProject', ['idproject'], 'id', 2);
        $this->addOrderBy('ListProject', ['creationdate'], 'date');
    }
}

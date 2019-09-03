<?php

namespace FacturaScripts\Plugins\FacturacionCostaRica\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListProject extends ListController {
    //put your code here
    public function getPageData(): array {
        $pageData = parent::getPageData();
        //$pageData['menu'] = 'admin';
        //$pageData['menu'] = 'Fact. Costa Rica';
        //$pageData['submenu'] = 'Fact. Costa Rica';
        //$pageData['title'] = 'Configuracion';
        //$pageData['icon'] = 'fas fa-fist-raised';
        $pageData['showonmenu'] = false;
        return $pageData;
    }
    
    protected function createViews() {
        $this->createViewsProject();
    }
    
    protected function createViewsProject($viewName = 'ListProject') {
        $this->addView($viewName, 'Project');
        $this->addSearchFields($viewName, ['name', 'description']);
        $this->addOrderBy($viewName, ['name'], 'name');
        $this->addOrderBy($viewName, ['idproject'], 'id', 2);
        $this->addOrderBy($viewName, ['creationdate'], 'date');
        
        //Filtros
        $this->addFilterCheckbox($viewName, 'verified');
    }
}

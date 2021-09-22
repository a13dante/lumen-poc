<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Traits\CommonQueryLogic;
use App\Traits\CommonShowOperation;
use App\Traits\CommonIndexOperation;
use App\Traits\CommonDeleteOperation;
use App\Traits\CommonStoreOperation;

class Controller extends BaseController
{
    use CommonQueryLogic, CommonShowOperation, CommonIndexOperation, CommonDeleteOperation, CommonStoreOperation;

    public $showModels = ['User'];
    public $indexModels = ['User'];
    public $deleteModels = ['User'];
    public $storeModels = ['User'];
}

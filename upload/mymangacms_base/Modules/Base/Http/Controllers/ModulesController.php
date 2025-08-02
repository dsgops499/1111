<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Base\DataTables\ModulesDataTable;
use Nwidart\Modules\Repository;

/**
 * Modules Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class ModulesController extends Controller {

    private $repo;

    public function __construct(Repository $repo)
    {
        $this->middleware('noajax');
        $this->middleware('permission:settings.edit_general');
        $this->repo = $repo;
    }

    /**
     * @var Repository
     */
    public function index(ModulesDataTable $dataTable) {
        return $dataTable->render('base::admin.modules.index');
    }
    
    public function show($name) {
        $module = $this->repo->find($name);
        return view('base::admin.modules.show', ['module' => $module]);
    }
    
    public function update($name) {
        if(request()->input('action')=='1') {
            $this->repo->enable($name);
            $msg = trans('messages.admin.modules.enable-msg');
        } else {
            $this->repo->disable($name);
            $msg = trans('messages.admin.modules.disable-msg');
        }
        return redirect()->route('admin.modules.index')
            ->withSuccess($msg);
    }
}

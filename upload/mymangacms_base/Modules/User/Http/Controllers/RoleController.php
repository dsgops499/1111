<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\User\DataTables\RoleDataTable;
use Modules\User\Contracts\RoleRepository;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Http\Requests\RolesRequest;

class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $role;
    private $permissions;

    public function __construct(PermissionManager $permissions, RoleRepository $role)
    {
        $this->permissions = $permissions;
        $this->role = $role;
        $this->middleware('permission:user.roles.index', ['only' => ['index']]);
        $this->middleware('permission:user.roles.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user.roles.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user.roles.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('user::admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('user::admin.roles.create',
                ['permissions' => $this->permissions->all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RolesRequest $request
     * @return Response
     */
    public function store(RolesRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->role->create($data);

        return redirect()->route('admin.role.index')
            ->withSuccess(trans('messages.admin.users.role.create-success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function edit($id)
    {
        if (!$role = $this->role->find($id)) {
            return redirect()->route('admin.role.index')
                ->withError(trans('role not found'));
        }

        return view('user::admin.roles.edit', 
            ['permissions' => $this->permissions->all(), 'role'=>$role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int          $id
     * @param  RolesRequest $request
     * @return Response
     */
    public function update($id, RolesRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);

        $this->role->update($id, $data);

        return redirect()->route('admin.role.index')
            ->withSuccess(trans('messages.admin.users.role.update-success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->role->delete($id);

        return redirect()->route('admin.role.index')
            ->withSuccess(trans('messages.admin.users.role.delete-success'));
    }
    
    private function mergeRequestWithPermissions(RolesRequest $request)
    {
        $permissions = $this->permissions->clean($request->permissions);

        return array_merge($request->all(), ['permissions' => $permissions]);
    }
}

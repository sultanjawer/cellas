<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
	public function index(Request $request)
	{
		abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'User Management';
		$page_title = 'Role';
		$page_subtitle = 'Manage';
		$page_heading = 'User Roles';
		$heading_class = 'fal fa-briefcase';
		$page_desc = 'Manage User Roles. Caution! better leave this page if you have no idea about this page.';

		if ($request->ajax()) {
			$query = Role::with(['permissions'])->select(sprintf('%s.*', (new Role())->table));
			$table = Datatables::of($query);

			$table->addColumn('placeholder', '&nbsp;');
			$table->addColumn('actions', '&nbsp;');

			$table->editColumn('actions', function ($row) {
				$viewGate = 'role_show';
				$editGate = 'role_edit';
				$deleteGate = 'role_delete';
				$crudRoutePart = 'roles';

				return view('partials.datatablesActions', compact(
					'viewGate',
					'editGate',
					'deleteGate',
					'crudRoutePart',
					'row'
				));
			});

			$table->editColumn('title', function ($row) {
				return $row->title ? $row->title : '';
			});
			$table->editColumn('permissions', function ($row) {
				$labels = [];
				foreach ($row->permissions as $permission) {
					$labels[] = sprintf('<span class="label label-info label-many">%s</span>', $permission->title);
				}

				return implode(' ', $labels);
			});

			$table->rawColumns(['actions', 'placeholder', 'permissions']);

			return $table->make(true);
		}
		$grpTitle = trans('cruds');

		$breadcrumb = trans('cruds.role.title') . " " . trans('global.list');
		return view('admin.roles.index', compact('grpTitle', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function create()
	{
		abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'User Management';
		$page_title = 'Role';
		$page_subtitle = 'New';
		$page_heading = 'New User Roles';
		$heading_class = 'fal fa-briefcase';
		$page_desc = 'Add/Create User Role. Caution! better leave this page if you have no idea about this page.';

		$permissions = Permission::pluck('title', 'id');
		$permi = Permission::all();
		$grpTitle = trans('cruds');

		$breadcrumb = trans('global.create') . " " .  trans('cruds.role.title');
		return view('admin.roles.create', compact('permissions',  'grpTitle', 'permi', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function store(StoreRoleRequest $request)
	{
		$role = Role::create($request->all());
		$role->permissions()->sync($request->input('permissions', []));

		session()->flash('message', trans('global.create_success'));
		return redirect()->route('admin.roles.index');
	}

	public function edit(Role $role)
	{
		abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$permissions = Permission::pluck('title', 'id');
		$permi = Permission::all();
		$role->load('permissions');
		$grpTitle = trans('cruds');
		$mnfound = false;

		$module_name = 'User Management';
		$page_title = 'Role';
		$page_subtitle = 'Edit';
		$page_heading = 'Edit User Roles';
		$heading_class = 'fal fa-edit';
		$page_desc = 'Edit User Role. Caution! better leave this page if you have no idea about this page.';

		$breadcrumb = trans('global.edit') . " " .  trans('cruds.role.title');
		return view('admin.roles.edit', compact('permissions', 'role', 'grpTitle', 'permi', 'mnfound', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function update(UpdateRoleRequest $request, Role $role)
	{
		//dd($request->all());
		$role->update($request->all());
		$role->permissions()->sync($request->input('permissions', []));
		session()->flash('message', trans('global.update_success'));

		return redirect()->route('admin.roles.index');
	}

	public function show(Role $role)
	{
		abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'User Management';
		$page_title = 'Roles';
		$page_subtitle = 'User';
		$page_heading = 'User Roles';
		$heading_class = 'fal fa-briefcase';
		$page_desc = 'Edit User Role. Caution! better leave this page if you have no idea about this page.';

		$role->load('permissions');
		$breadcrumb = trans('global.show') . " " .  trans('cruds.role.title');
		return view('admin.roles.show', compact('role', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function destroy(Role $role)
	{
		abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$role->delete();

		return back();
	}

	public function massDestroy(MassDestroyRoleRequest $request)
	{
		Role::whereIn('id', request('ids'))->delete();

		return response(null, Response::HTTP_NO_CONTENT);
	}
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{

	public function index(Request $request)
	{
		abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'User Management';
		$page_title = 'Permissions';
		$page_subtitle = 'Manage';
		$page_heading = 'User Permission';
		$heading_class = 'fal fa-lock';
		$page_desc = 'Manage User Permissions. Caution! better leave this page if you have no idea about this page.';

		if ($request->ajax()) {
			$query = Permission::query()->orderBy('created_at', 'desc')->select(sprintf('%s.*', (new Permission())->table));
			$table = Datatables::of($query);

			$table->addColumn('placeholder', '&nbsp;');
			$table->addColumn('actions', '&nbsp;');

			$table->editColumn('actions', function ($row) {
				$viewGate = 'permission_show';
				$editGate = 'permission_edit';
				$deleteGate = 'permission_delete';
				$crudRoutePart = 'permissions';

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
			$table->editColumn('perm_type', function ($row) {
				return $row->perm_type ? Permission::PERM_TYPE_SELECT[$row->perm_type] : '';
			});
			$table->editColumn('grp_title', function ($row) {
				$grpTitle = trans('cruds');
				$out = '';
				foreach ($grpTitle as $key => $label) {
					if ($label['title'] == $row->grp_title) {
						$out = $label['title'];
						break;
					}
				}

				return $row->grp_title ? $out : '';
			});

			$table->rawColumns(['actions', 'placeholder']);

			return $table->make(true);
		}

		$breadcrumb = trans('cruds.permission.title') . " " . trans('global.list');
		return view('admin.permissions.index', compact(
			'breadcrumb',
			'module_name',
			'page_title',
			'page_subtitle',
			'page_heading',
			'heading_class',
			'page_desc'
		));
	}

	public function create()
	{
		abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$grpTitle = trans('cruds');

		$module_name = 'User Management';
		$page_title = 'Permissions';
		$page_subtitle = 'New';
		$page_heading = 'New User Permission';
		$heading_class = 'fal fa-lock';
		$page_desc = 'Manage User Permissions. Caution! better leave this page if you have no idea about this page.';

		$breadcrumb = trans('global.create') . " " .  trans('cruds.permission.title');
		return view('admin.permissions.create', compact('grpTitle', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function store(StorePermissionRequest $request)
	{
		$permission = Permission::create($request->all());

		return redirect()->route('admin.permissions.index');
	}

	public function edit(Permission $permission)
	{
		abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'User Management';
		$page_title = 'Permissions';
		$page_subtitle = 'New';
		$page_heading = 'New User Permission';
		$heading_class = 'fal fa-lock';
		$page_desc = 'Manage User Permissions. Caution! better leave this page if you have no idea about this page.';

		$grpTitle = trans('cruds');

		$breadcrumb = trans('global.edit') . " " .  trans('cruds.permission.title');
		return view('admin.permissions.edit', compact('permission', 'grpTitle', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function update(UpdatePermissionRequest $request, Permission $permission)
	{
		$permission->update($request->all());

		return redirect()->route('admin.permissions.index');
	}

	public function show(Permission $permission)
	{
		abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$grpTitle = trans('cruds');

		$module_name = 'User Management';
		$page_title = 'User';
		$page_subtitle = 'Permission';
		$page_heading = 'View User Permission';
		$heading_class = 'fal fa-lock';
		$page_desc = 'Manage User Permissions. Caution! better leave this page if you have no idea about this page.';

		$breadcrumb = trans('global.show') . " " .  trans('cruds.permission.title');
		return view('admin.permissions.show', compact('permission', 'grpTitle', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function destroy(Permission $permission)
	{
		abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$permission->delete();

		return back();
	}

	public function massDestroy(MassDestroyPermissionRequest $request)
	{
		Permission::whereIn('id', request('ids'))->delete();

		return response(null, Response::HTTP_NO_CONTENT);
	}
}

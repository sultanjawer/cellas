<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AuditLogsController extends Controller
{
	public function index(Request $request)
	{
		abort_if(Gate::denies('audit_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'User Management';
		$page_title = 'Activity';
		$page_subtitle = 'Logs';
		$page_heading = 'Activity Logs';
		$heading_class = 'fal fa-user-cog';
		$page_desc = 'SysApp Activity logs';

		if ($request->ajax()) {
			// $query = AuditLog::query()->select(sprintf('%s.*', (new AuditLog())->table));
			$query = AuditLog::with(['user_info'])->select(sprintf('%s.*', (new AuditLog())->table));
			$table = Datatables::of($query);

			$table->addColumn('placeholder', '&nbsp;');
			$table->addColumn('actions', '&nbsp;');

			$table->editColumn('actions', function ($row) {
				$viewGate = 'audit_log_show';
				$editGate = 'audit_log_edit';
				$deleteGate = 'audit_log_delete';
				$crudRoutePart = 'audit-logs';

				return view('partials.datatablesActions', compact(
					'viewGate',
					'editGate',
					'deleteGate',
					'crudRoutePart',
					'row'
				));
			});

			$table->editColumn('id', function ($row) {
				return $row->id ? $row->id : '';
			});
			$table->editColumn('description', function ($row) {
				return $row->description ? $row->description : '';
			});
			$table->editColumn('subject_id', function ($row) {
				return $row->subject_id ? $row->subject_id : '';
			});
			$table->editColumn('subject_type', function ($row) {
				return $row->subject_type ? $row->subject_type : '';
			});
			$table->editColumn('user_id', function ($row) {
				// return $row->user_id ? $row->user_id : '';
				return $row->user_id ? ($row->user_info->name ?? '') : '';
			});
			$table->editColumn('host', function ($row) {
				return $row->host ? $row->host : '';
			});

			$table->rawColumns(['actions', 'placeholder']);

			return $table->make(true);
		}
		$breadcrumb = trans('cruds.auditLog.title') . " " . trans('global.list');
		return view('admin.auditLogs.index', compact('breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	public function show(AuditLog $auditLog)
	{
		abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$auditLog->load('user_info');
		$module_name = 'User Management';
		$page_title = 'Activity';
		$page_subtitle = 'Logs';
		$page_heading = 'Activity Logs';
		$heading_class = 'fal fa-user-cog';
		$page_desc = 'SysApp Activity logs';
		$breadcrumb = "View Audit Log";
		$breadcrumb = trans('global.show')  . " " . trans('cruds.auditLog.title');
		return view('admin.auditLogs.show', compact('auditLog', 'breadcrumb', 'module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}
}

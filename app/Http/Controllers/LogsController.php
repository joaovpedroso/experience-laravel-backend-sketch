<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

class LogsController extends Controller
{
    public function index()
    {
        //get all activity logs
        $logs = Activity::orderBy('created_at', 'desc')->get();


        //options for filtering
        $users   = $logs->groupBy('causer_id');
        $modules = $logs->groupBy('subject_type');
        $actions = $logs->groupBy('description');


        //filters
        //id 5or title
        if ($title = request('title')) {
            $logs = $logs->filter(function($log, $key) use($title) {
                $validId = $log->subject->id == $title;

                $pattern = "/($title)/i";
                $validName = preg_match($pattern, $log->subject->name);
                $validTitle = preg_match($pattern, $log->subject->title);

                return $validId || $validName || $validTitle;
            });
        }

        //users
        if ($usersFilter = request('users')) {
            $logs = $logs->filter(function($log, $key) use($usersFilter) {
                return in_array($log->causer_id, $usersFilter);
            });
        }

        //modules
        if ($modulesFilter = request('modules')) {
            $logs = $logs->filter(function($log, $key) use($modulesFilter) {
                return in_array($log->subject_type, $modulesFilter);
            });
        }

        //actions
        if ($actionsFilter = request('actions')) {
            $logs = $logs->filter(function($log, $key) use($actionsFilter) {
                return in_array($log->description, $actionsFilter);
            });
        }

        //starting date
        $start_date = request("start_date");
        if (isset($start_date) and $start_date != "") {
            $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $start_date.' 00:00:00');
            $logs = $logs->filter(function($log, $key) use($start_date) {
                return $log->created_at->gte($start_date);
            });
        }

        //ending date
        $end_date = request("end_date");
        if (isset($end_date) and $end_date != "") {
            $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $end_date.' 23:59:59');
            $logs = $logs->filter(function($log, $key) use($end_date) {
                return $log->created_at->lte($end_date);
            });
        }

        $logs = $this->paginate($logs);

        return view("backend.logs.index", [
            'logs' => $logs,
            'users' => $users,
            'modules' => $modules,
            'actions' => $actions,
        ]);
    }

    // PAGINATION
    private function paginate($logs)
    {
        $perPage = config('helpers.qtdPerPag');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $logs->slice(($currentPage - 1) * $perPage, $perPage);
        $logs = new LengthAwarePaginator($currentPageItems, $logs->count(), $perPage);
        $logs->setPath(LengthAwarePaginator::resolveCurrentPath());

        return $logs;
    }
}

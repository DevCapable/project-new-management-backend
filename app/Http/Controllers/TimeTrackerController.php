<?php

namespace App\Http\Controllers;

use App\Models\TimeTracker;
use App\Models\TrackPhoto;
use App\Models\Utility;
use Illuminate\Http\Request;

class TimeTrackerController extends Controller
{

    public function index($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $treckers = TimeTracker::where('workspace_id', $currentWorkspace->id)->get();
        return view('time_trackers.index', compact('currentWorkspace', 'treckers'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show(TimeTracker $timeTracker)
    {

    }

    public function edit(TimeTracker $timeTracker)
    {

    }

    public function update(Request $request, TimeTracker $timeTracker)
    {

    }

    public function destroy($timetracker_id)
    {
        // if(Auth::user()->can('delete timesheet'))
        // {
        $timetrecker = TimeTracker::find($timetracker_id);
        $timetrecker->delete();
        return redirect()->back()->with('success', __('TimeTracker successfully deleted.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function getTrackerImages(Request $request, $slug)
    {

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $tracker = TimeTracker::find($request->id);
        $images = TrackPhoto::where('track_id', $request->id)->get();

        return view('time_trackers.images', compact('images', 'tracker', 'currentWorkspace'));
    }
    public function removeTrackerImages(Request $request)
    {

        $images = TrackPhoto::find($request->id);

        if ($images) {

            $url = $images->img_path;
            if ($images->delete()) {
                \Storage::delete($url);

                return Utility::success_res(__('Tracker Photo remove successfully.'));
            } else {
                return Utility::error_res(__('opps something wren wrong.'));
            }
        } else {
            return Utility::error_res(__('opps something wren wrong.'));
        }
    }
    public function removeTracker(Request $request)
    {
        $track = TimeTracker::find($request->input('id'));
        if ($track) {
            $track->delete();
            return Utility::success_res(__('Track remove successfully.'));
        } else {
            return Utility::error_res(__('Track not found.'));
        }
    }
}

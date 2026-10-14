<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Auth;
use Helper;
use App\Models\Notice;
use Illuminate\Support\Facades\Cache;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!$this->user || Helper::hasRight('notice.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('backend.pages.notice.index');
    }

    public function getList(Request $request)
    {
        $data = Notice::query();

        if (!empty($request->title)) {
            $data->where('title', 'like', "%" . $request->title . "%");
        }

        if ($request->status) {
            $data->where('status', ($request->status == 1) ? 1 : 0);
        }

        return Datatables::of($data)

            ->editColumn('description', function ($row) {
                $description = e(\Str::limit(strip_tags($row->description), 80));
                return ($row->description) ? $description : '-';
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge bg-primary w-70">Active</span>';
                } else {
                    return '<span class="badge bg-danger w-70">Inactive</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Helper::hasRight('notice.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a>';
                }
                if (Helper::hasRight('notice.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['status', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $notice = new Notice();
        $notice->title = $request->title;
        $notice->description = $request->description;
        $notice->status = ($request->status) ? 1 : 0;

        if ($notice->save()) {
            Cache::forget('active_notice');
            return response()->json([
                'type' => 'success',
                'message' => 'Notice created successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function edit($id)
    {
        $notice = Notice::find($id);
        return view('backend.pages.notice.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $notice = Notice::find($id);
        if (!$notice) {
            return response()->json([
                'type' => 'error',
                'message' => 'Notice not found.',
            ]);
        }

        $notice->title = $request->title;
        $notice->description = $request->description;
        $notice->status = ($request->status) ? 1 : 0;

        if ($notice->save()) {
            Cache::forget('active_notice');
            return response()->json([
                'type' => 'success',
                'message' => 'Notice updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
}

    public function delete($id)
    {
        $notice = Notice::find($id);
        if ($notice) {
            $notice->delete();
            Cache::forget('active_notice');
            return json_encode(['success' => 'Notice deleted successfully.']);
        } else {
            return json_encode(['error' => 'Notice not found.']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Applicant;
use App\Models\Document;
use App\Models\HrManager;
use App\Models\HrStaff;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $searchReq = Request::input('search');

        $documents = Document::query()
        ->when($searchReq, function($query, $search) {
            $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(title) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(description) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(filename) LIKE LOWER(?)', ['%' . $search . '%'])
                        ->orWhereRaw('LOWER(path) LIKE LOWER(?)', ['%' . $search . '%']);
            });

            // Use this if we like to perform a case-insensitive and approximate matching search

            // $query->where(function ($query) use ($search) {
            //     $lowerSearch = strtolower($search);
            //     $query->whereRaw('LOWER(first_name) LIKE LOWER(?)', ['%' . $search . '%'])
            //           ->orWhereRaw('SOUNDEX(last_name) = SOUNDEX(?)', [$lowerSearch]);
            // })
            // ->orWhere(function ($query) use ($search) {
            //     $lowerSearch = strtolower($search);
            //     $query->whereRaw('LOWER(last_name) LIKE LOWER(?)', ['%' . $search . '%'])
            //           ->orWhereRaw('SOUNDEX(first_name) = SOUNDEX(?)', [$lowerSearch]);
            // });
        })
        ->orderBy('id', 'asc')
        ->paginate(10)
        ->withQueryString()
        ->through(fn($file) => [
            'id' => $file->id,
            'title' => $file->title,
            'description' => $file->description,
            'filename' => $file->filename,
            'path' => $file->path,
            'created_at' => $file->created_at,
        ]);

        if (empty($searchReq)) {
            unset($filters['search']);
        }

        $currentPage = $documents->currentPage();
        $lastPage = $documents->lastPage();
        $firstPage = 1;

        $previousPage = $currentPage - 1 > 0 ? $currentPage - 1 : null;
        $nextPage = $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;

        $links = [];

        if ($previousPage !== null) {
            $links[] = [
                'url' => $documents->url($previousPage),
                'label' => 'Previous',
            ];
        }

        $links[] = [
            'url' => $documents->url(1),
            'label' => 1,
        ];

        if ($currentPage > 3) {
            $links[] = [
                'url' => $documents->url($currentPage - 1),
                'label' => '...',
            ];
        }

        $rangeStart = max(2, $currentPage - 1);
        $rangeEnd = min($lastPage - 1, $currentPage + 1);

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $links[] = [
                'url' => $documents->url($i),
                'label' => $i,
            ];
        }


        if ($currentPage < $lastPage - 2) {
            $links[] = [
                'url' => $documents->url($currentPage + 1),
                'label' => '...',
            ];
        }

        if ($firstPage !== $lastPage) {
            $links[] = [
                'url' => $documents->url($lastPage),
                'label' => $lastPage,
            ];
        }

        if ($nextPage !== null) {
            $links[] = [
                'url' => $documents->url($nextPage),
                'label' => 'Next',
            ];
        }

        return Inertia::render('Documents', [
            'documents' => $documents,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'links' => $links,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Upload the file to digital ocean cloud storage.
     */
    public function upload()
    {
        if(request()->hasFile('documentUpload'))
        {
            $originalFilename = request()->file('documentUpload')->getClientOriginalName();
            $storedPath = request()->file('documentUpload')->store('uploads/documents', 'public');

            return [
                'filename' => $originalFilename,
                'path' => $storedPath,
            ];
        }

        return '';
    }

    public function uploadRevert() {
        if($file = request()->get('path')) {
            $path = storage_path('app/public/' . $file);

            if(file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $documentValidate = Request::validate([
            'title' => ['required', 'max:50'],
            'description' => ['required'],
            'filename' => ['required'],
            'path' => ['required'],
        ]);

        $path = storage_path('app/public/' . $documentValidate['path']);

        $cloudPath = Storage::disk('spaces')->putFileAs('uploads/documents', $path, $documentValidate['filename']);

        $user = Document::create([
            'title' => $documentValidate['title'],
            'description' => $documentValidate['description'],
            'filename' => $documentValidate['filename'],
            'path' => env('DO_URL') . '/' . $cloudPath,
        ]);

        unlink($path);

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(Files $files)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Files $files)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $documentValidate = Request::validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);

        $document = Document::findOrFail($id);

        $userInfo = null;
        $userRole = '';

        if(auth()->user()->user_type === User::ADMIN) {
            $userInfo = Admin::where('user_id', auth()->user()->id)->first();
            $userRole = "Admin";
        } else if(auth()->user()->user_type === User::HR_MANAGER) {
            $userInfo = HrManager::where('user_id', auth()->user()->id)->first();
            $userRole = "Hr Manager";
        } else if(auth()->user()->user_type === User::HR_STAFF) {
            $userInfo = HrStaff::where('user_id', auth()->user()->id)->first();
            $userRole = "Hr Staff";
        } else if(auth()->user()->user_type === User::APPLICANT) {
            $userInfo = Applicant::where('user_id', auth()->user()->id)->first();
            $userRole = "Applicant";
        }

        if($documentValidate['title'] !== $document->title) {
            activity()
            ->performedOn(Document::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Document's title from {$document->title} to {$documentValidate['title']}");

            $document->title = $documentValidate['title'];
        }

        if($documentValidate['description'] !== $document->description) {
            activity()
            ->performedOn(Document::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Document's description from {$document->description} to {$documentValidate['description']}");

            $document->description = $documentValidate['description'];
        }

        if(request()->path !== $document->path) {
            $path = request()->path;

            $storagePath = storage_path('app/public/' . $path);

            $cloudPathToDelete = Storage::disk('spaces')->delete('uploads/documents/' . $document->filename);
            $cloudPathToReplace = Storage::disk('spaces')->putFileAs('uploads/documents', $storagePath, request()->filename);

            $cloudStoragePath = env('DO_URL') . '/' . $cloudPathToReplace;

            activity()
            ->performedOn(Document::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Document's path from {$document->path} to {$cloudStoragePath}");

            $document->path = $cloudStoragePath;

            unlink($storagePath);
        }

        if(request()->filename !== $document->filename) {
            $filename = request()->filename;

            activity()
            ->performedOn(Document::where('id', $id)->first())
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties(['ipAddress' => request()->ip(), 'user' => $userInfo->first_name . ' ' . $userInfo->last_name, 'role' => $userRole])
            ->log("Updated a Document's filename from {$document->filename} to {$filename}");

            $document->filename = $filename;
        }

        $document->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        $cloudPath = Storage::disk('spaces')->delete('uploads/documents/' . $document->filename);

        $document->delete();
    }
}

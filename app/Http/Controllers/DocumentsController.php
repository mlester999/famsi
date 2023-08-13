<?php

namespace App\Http\Controllers;

use App\Models\Documents;
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

        $documents = Documents::query()
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

        $user = Documents::create([
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
    public function update(Request $request, Files $files)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $file = Files::findOrFail($id);

        $file->delete();
    }
}

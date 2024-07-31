<?php

namespace LaravelGuru\LaravelFilehandler\Http\Controllers;

use LaravelGuru\LaravelFilehandler\Http\Resources\FileResource;
use LaravelGuru\LaravelFilehandler\Models\File;
use LaravelGuru\LaravelFilehandler\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class FileController extends Controller
{
    protected $fileService;

    /**
     * FileController constructor.
     * This injects the FileService class to be consumed by the FileController class
     *
     * @param FileService $fileService
     * @return void
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            abort(403, 'Only authenticated users can create new posts.');
        }

        $documents = File::query()->where('user_id', auth()->id())->where('folder', 'documents')->paginate(9)->onEachSide(1);
        return FileResource::collection($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            abort(403, 'Only authenticated users can create new posts.');
        }

        $files = $request->file('files');
        $user_id = auth()->id();

        if (!$files || empty($files)) {
            return response()->json([
                'success' => false,
                'message' => 'No files were uploaded',
            ], 400);
        }

        try {
            $documents = $this->upload($user_id, $files);

            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully',
                'documents' => FileResource::collection($documents),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload files to the DB and storage
     * This method is responsible for uploading files to the storage and update in the database
     *
     * @param int $user_id
     * @param array $files
     *
     * @return mixed
     */
    private function upload($user_id, $files)
    {
        return DB::transaction(function () use ($user_id, $files) {
            return collect($files)->map(function ($file) use ($user_id) {
                return $this->fileService->upload('documents', $user_id, $file)->getData();
            });
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        if (!auth()->check()) {
            abort(403, 'Only authenticated users can create new posts.');
        }
        return new FileResource($file);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        if (!auth()->check()) {
            abort(403, 'Only authenticated users can create new posts.');
        }

        $document = $request->file('file');

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No file was uploaded',
            ], 400);
        }

        try {
            $this->fileService->modify($file->id, $document);

            return response()->json([
                'success' => true,
                'message' => 'File updated successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        try {
            if (!auth()->check()) {
                abort(403, 'Only authenticated users can create new posts.');
            }
            $this->fileService->delete($file->id);
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

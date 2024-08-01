<?php

namespace LaravelGuru\LaravelFilehandler\Services;

use LaravelGuru\LaravelFilehandler\Http\Resources\FileResource;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class FileService
{

    private $fileManagerService;
    private static $instance;

    /**
     * Get the instance of the FileService class.
     *
     * This method returns the instance of the FileService class.
     * If the instance does not exist, it creates a new instance and returns it.
     *
     * @param FileManager|null $fileManager The file manager service.
     * @return FileService Returns the instance of the FileService class.
     */
    public static function getInstance(FileManager $fileManager = null)
    {
        if (is_null(self::$instance)) {
            if (is_null($fileManager)) {
                $fileManager = new FileManager();
            }
            self::$instance = new self($fileManager);
        }
        return self::$instance;
    }

    /**
     * Create a new instance of the FileService class.
     *
     * This method creates a new instance of the FileService class.
     *
     * @param FileManager $fileManager The file manager service.
     * @return void
     */
    private function __construct(FileManager $fileManager)
    {
        $this->fileManagerService = $fileManager;
    }

    /**
     * Uploads a file to storage.
     *
     * This method is responsible for uploading a file to the storage system.
     * It takes care of handling the file upload process and storing the file in the appropriate location.
     *
     * @param string $folder The folder where the file is stored.
     * @param int $user_id   The ID of the user who uploaded the file.
     * @return JsonResponse  JSON Response of the path of the uploaded file and file.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function upload($folder, $user_id, $file): JsonResponse
    {
        if ($user_id == null || $folder == null) {
            throw new InvalidArgumentException('Missing required arguments: Reference ID and folder are both required to update a file association.');
        }
        $file = $this->fileManagerService->upload($folder, $user_id, $file);

        return response()->json(new FileResource($file));
    }

    /**
     * Retrieves the path of the latest version of a file in a specified folder.
     *
     * This method first calls the `get_path_by` method of the `fileManagerService` with the provided folder and ID.
     * It then initializes a variable to hold the latest version of the file.
     *
     * The method then loops through the array of files returned by `get_path_by`.
     * For each file, it extracts the numeric part from the version string using a regular expression.
     * If the `latestVersion` variable is null or the current file's version is greater than the latest version,
     * it updates the `latestVersion` variable with the current file.
     *
     * Finally, the method returns the path of the latest version of the file.
     *
     * @param string $folder  The name of the folder where the file is located.
     * @param int $user_id    The ID of the user who uploaded the file.
     * @return JsonResponse   returns the attributes of the latest version of the file.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function get($folder, $user_id, $trash = 'none'): JsonResponse
    {
        $fileArray = self::getAll($folder, $user_id, $trash);
        $latestVersion = null;
        foreach ($fileArray as $file) {
            $fileVersion = $file['version'];
            if ($latestVersion === null || strnatcmp($fileVersion, $latestVersion['version']) > 0) {
                $latestVersion = $file;
            }
        }

        return $latestVersion;
    }

    /**
     * Retrieves all files from a specified folder in storage.
     *
     * This method is responsible for fetching all files that are stored in a specific folder within the storage system.
     * The folder is identified by the provided $folder parameter.
     *
     * The method returns an array of files. Each file in the array is represented as an associative array containing
     * the file's properties, such as its id, path
     *
     * @param string $folder The path of the folder within the storage system from which to retrieve files.
     * @param int $user_id   The ID of the user who uploaded the file.
     * @return array         An array of associative arrays, each representing a file in the specified folder. If $id is provided, the array may contain a single file.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function getAll($folder, $user_id, $trash = 'none'): array
    {
        if ($user_id == null || $folder == null) {
            throw new InvalidArgumentException('Missing required arguments: Reference ID and folder are both required to update a file association.');
        }

        return $this->fileManagerService->get_path_by($folder, $user_id, $trash);
    }

    /**
     * Delete file from storage
     *
     * This method is responsible for deleting a file from the storage.
     * It performs the necessary operations to delete the file and returns
     * true if the file was successfully deleted.
     *
     * @param string $folder The folder where the file is stored.
     * @param int $user_id   The ID of the user who uploaded the file.
     * @return JsonResponse  Returns a JSON response indicating the status of the file deletion.
     *
     *  @throws InvalidArgumentException If the required arguments are missing.
     */
    public function deleteAll($folder, $user_id, $preserve = false): JsonResponse
    {
        if ($user_id == null || $folder == null) {
            throw new InvalidArgumentException('Missing required arguments: Reference ID and folder are both required to update a file association.');
        }
        $files = $this->getAll($folder, $user_id);
        foreach ($files as $file) {
            if (is_array($file)) {
                $this->fileManagerService->delete($file['id'], $preserve);
            }
        }
        return response()->json(["message" => "Files deleted successfully"], 200);
    }

    /**
     * Delete file from storage
     *
     * This method is responsible for deleting a file from the storage.
     * It performs the necessary operations to delete the file and returns
     * true if the file was successfully deleted.
     *
     * @param int $id        The ID of the file to be deleted.
     * @param bool $preserve (default: false) Whether to preserve the file in storage.
     * @return JsonResponse  Returns a JSON response indicating the status of the file deletion.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function delete($id, $preserve = false): JsonResponse
    {
        if ($id == null) {
            throw new InvalidArgumentException('Missing required arguments: ID is required to delete a file association.');
        }

        return $this->fileManagerService->delete($id, $preserve);
    }

    /**
     * Delete file from storage
     *
     * This method is responsible for deleting a file from the storage.
     * It performs the necessary operations to delete the file and returns
     * true if the file was successfully deleted.
     *
     * @param int $id       The ID of the file to be deleted.
     * @return JsonResponse Returns a JSON response indicating the status of the file deletion.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function deleteFile(string $path): JsonResponse
    {
        if ($path == null) {
            throw new InvalidArgumentException('Missing required arguments: Path is required to delete a file association.');
        }
        return $this->fileManagerService->deleteFile($path);
    }

    /**
     * Update file in storage
     *
     * This method is responsible for updating a file in the storage.
     * It performs the necessary operations to update the file and returns
     * the path value of the updated file.
     *
     * @param string $folder The folder where the file is stored.
     * @param int $user_id   The ID of the user who uploaded the file.
     * @param mixed $file    The updated file.
     * @param bool $preserve (default: false) Whether to preserve the file in storage.
     * @return JsonResponse  Returns the path of the updated file.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function update($folder, $user_id, $file = null, $preserve = false): JsonResponse
    {
        if ($user_id == null || $folder == null) {
            throw new InvalidArgumentException('Missing required arguments: Reference ID and folder are both required to update a file association.');
        }

        $file = $this->fileManagerService->update($folder, $user_id, $file, $preserve);
        return response()->json(["file" => $file]);
    }

    public function modify(int $id, $file = null)
    {
        $this->fileManagerService->modify($id, $file);
        return response()->json(["message" => "File modified successfully"], 200);
    }

    /**
     * Restore file from trash
     *
     * This method is responsible for restoring a file from the trash.
     * It performs the necessary operations to restore the file and returns
     * true if the file was successfully restored.
     *
     * @param string $folder The folder where the file is stored.
     * @param int $user_id   The ID of the user who uploaded the file.
     * @return JsonResponse  Returns a JSON response indicating the status of the file restoration.
     *
     * @throws InvalidArgumentException If the required arguments are missing.
     */
    public function restore($folder, $user_id): JsonResponse
    {
        if ($user_id == null || $folder == null) {
            throw new InvalidArgumentException('Missing required arguments: Reference ID and folder are both required to update a file association.');
        }
        $files = $this->getAll($folder, $user_id, 'only');
        foreach ($files as $file) {
            if (is_array($file)) {
                $this->fileManagerService->restore($file['id']);
            }
        }
        return response()->json(["message" => "Files restored successfully"], 200);
    }
}

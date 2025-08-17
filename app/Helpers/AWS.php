<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AWS
{
    protected string $resumeFolder;
    protected string $imageFolder;
    protected string $companyFolder;
    protected bool $status;
    protected string $disk;

    public function __construct(string $disk = 's3')
    {
        $this->disk = $disk;
        if (app()->environment('local')) {
            $this->resumeFolder = 'testing/resumes/employee_cv';
            $this->imageFolder = 'testing/images/pictures/kw';
            $this->companyFolder = 'testing/images/company/pictures/kw';
        } else {
            $this->resumeFolder = 'live/resumes/employee_cv';
            $this->imageFolder = 'live/images/pictures/kw';
            $this->companyFolder = 'live/images/company/pictures/kw';
        }
        $this->status = $this->areAwsCredentialsConfigured();
    }

    protected function areAwsCredentialsConfigured(): bool
    {
        return !empty(env('AWS_ACCESS_KEY_ID')) &&
            !empty(env('AWS_SECRET_ACCESS_KEY')) &&
            !empty(env('AWS_DEFAULT_REGION')) &&
            !empty(env('AWS_BUCKET'));
    }


    public function uploadStorageFileToAWS(string $fileName): array|bool
    {
        if (!$this->status) {
            return [
                'status' => false,
                'message' => "AWS status not active",
            ];
        }
        

        $sourcePath = public_path('storage/' . $fileName);
        
        if (!file_exists($sourcePath)) {
            return [
                'status' => false,
                'message' => "Source file does not exist at path: {$sourcePath}",
            ];
        }

        $pathParts = explode('/', $fileName);
        if(str_contains($pathParts[1], 'default') || str_contains($pathParts[1], 'avatar'))
        {
            return [
                'status' => true,
                'message' => "Default image not uploaded on aws",
            ];
        }

        $userId = $pathParts[1];
        $justFileName = $pathParts[2];

        $destinationPath = "{$this->resumeFolder}/{$userId}/{$justFileName}";

        if (count($pathParts) < 3) {
            return [
                'status' => false,
                'message' => "Invalid file path format: {$fileName}",
            ];
        }

        if (Storage::disk($this->disk)->exists($destinationPath)) {
            return [
                'status' => true,
                'message' => 'File Already Uploaded',
            ];
        }
        
        $fileObject = new UploadedFile($sourcePath, $justFileName, null, null, true);

        try 
        {
            $fileObject->storeAs("{$this->resumeFolder}/{$userId}", $justFileName, $this->disk);
            return [
                'status' => true,
                'message' => "File successfully uploaded",
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => "Error on uplaoding file on AWS",
            ];
        }
    }

    public function deleteStorageFileFromAWS($fileName){
        $pathParts = explode('/', $fileName);
        if(str_contains($pathParts[1], 'default') || str_contains($pathParts[1], 'avatar'))
        {
            return true;
        }
        $userId = $pathParts[1];
        $justFileName = $pathParts[2];

        $destinationPath = "{$this->resumeFolder}/{$userId}/{$justFileName}";
        if (Storage::disk('s3')->exists($destinationPath)) {
            Storage::disk('s3')->delete($destinationPath);
            return true;
        } else {
            return false;
        }
    }

    public function uploadStorageImageToAWS(string $fileName): array|bool
    {
        if (!$this->status) {
            return [
                'status' => false,
                'message' => "AWS status not active",
            ];
        }
        

        $sourcePath = public_path('storage/' . $fileName);
        
        if (!file_exists($sourcePath)) {
            return [
                'status' => false,
                'message' => "Source image does not exist at path: {$sourcePath}",
            ];
        }

        $pathParts = explode('/', $fileName);

        if(str_contains($pathParts[1], 'default') || str_contains($pathParts[1], 'avatar'))
        {
            return [
                'status' => true,
                'message' => "Default image not uploaded on aws",
            ];
        }
        
        $userId = $pathParts[2];
        $justFileName = $pathParts[3];

        if(str_contains($justFileName, 'default')){
            return [
                'status' => true,
                'message' => "Default image not uploaded on aws",
            ];
        }

        $destinationPath = "{$this->imageFolder}/{$userId}/{$justFileName}";

        if (count($pathParts) < 3) {
            return [
                'status' => false,
                'message' => "Invalid image path format: {$fileName}",
            ];
        }

        if (Storage::disk($this->disk)->exists($destinationPath)) {
            return [
                'status' => true,
                'message' => 'Image Already Uploaded',
            ];
        }

        
        $fileObject = new UploadedFile($sourcePath, $justFileName, null, null, true);

        try 
        {
            $fileObject->storeAs("{$this->imageFolder}/{$userId}", $justFileName, $this->disk);
            return [
                'status' => true,
                'message' => "Image successfully uploaded",
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => "Error on uplaoding image on AWS",
            ];
        }
    }

    public function uploadStorageCompanyImageToAWS(string $fileName): array|bool
    {
        if (!$this->status) {
            return [
                'status' => false,
                'message' => "AWS status not active",
            ];
        }
        

        $sourcePath = public_path('storage/' . $fileName);
        
        if (!file_exists($sourcePath)) {
            return [
                'status' => false,
                'message' => "Source image does not exist at path: {$sourcePath}",
            ];
        }

        $pathParts = explode('/', $fileName);
        if(str_contains($pathParts[1], 'default') || str_contains($pathParts[1], 'avatar'))
        {
            return [
                'status' => true,
                'message' => "Default image not uploaded on aws",
            ];
        }
        $userId = $pathParts[2];
        $justFileName = $pathParts[3];

        if(str_contains($justFileName, 'default')){
            return [
                'status' => true,
                'message' => "Default image not uploaded on aws",
            ];
        }

        $destinationPath = "{$this->companyFolder}/{$userId}/{$justFileName}";

        if (count($pathParts) < 3) {
            return [
                'status' => false,
                'message' => "Invalid image path format: {$fileName}",
            ];
        }

        if (Storage::disk($this->disk)->exists($destinationPath)) {
            return [
                'status' => true,
                'message' => 'Image Already Uploaded',
            ];
        }

        
        $fileObject = new UploadedFile($sourcePath, $justFileName, null, null, true);

        try 
        {
            $fileObject->storeAs("{$this->companyFolder}/{$userId}", $justFileName, $this->disk);
            return [
                'status' => true,
                'message' => "Image successfully uploaded",
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => "Error on uplaoding image on AWS",
            ];
        }
    }

    //Delete folder from AWS
    public function deleteStorageResumeFolderFromAWS($userId)
    {
        $destinationPath = "{$this->resumeFolder}/{$userId}";
        if (Storage::disk($this->disk)->exists($destinationPath)) {
            Storage::disk($this->disk)->deleteDirectory($destinationPath);
        }
        return true; 
    }

    public function deleteStorageImageFolderFromAWS($userId)
    {
        $destinationPath = "{$this->imageFolder}/{$userId}";
        if (Storage::disk($this->disk)->exists($destinationPath)) {
            Storage::disk($this->disk)->deleteDirectory($destinationPath);
        }
        return true;
    }

    public function deleteStorageCompanyImageFolderFromAWS($userId)
    {
        $destinationPath = "{$this->companyFolder}/{$userId}";
        if (Storage::disk($this->disk)->exists($destinationPath)) {
            Storage::disk($this->disk)->deleteDirectory($destinationPath);
        }
        return true;
    }

    //Delete resume from local
    public function deleteLocalResumeAndNoContactCV($user)
    {
        $remoteFileUrl_employee_cv = 'public/storage/' . $user->employee_cv;
        $remoteFileUrl_cv_no_contact = 'public/storage/' . $user->cv_no_contact;
        if (!empty($user->employee_cv)) {
            if (file_exists($remoteFileUrl_employee_cv)) {
                unlink($remoteFileUrl_employee_cv);
            }
        }
        if (!empty($user->cv_no_contact)) {
            if (file_exists($remoteFileUrl_cv_no_contact)) {
                unlink($remoteFileUrl_cv_no_contact);
            }
        }
        return true;
    }

    public function deleteLocalImageAndThumbnail($user)
    {
        $findstring = "default";
        $remoteFileUrl_file = 'public/storage/' . $user->file;
        $remoteFileUrl_thumbnail = 'public/storage/' . $user->thumbnail;
        if (stripos($remoteFileUrl_file, $findstring) == false) {
            if (is_file($remoteFileUrl_file) && file_exists($remoteFileUrl_file)) {
                unlink($remoteFileUrl_file);
            }
        }

        if (stripos($remoteFileUrl_thumbnail, $findstring) == false) {
            if (is_file($remoteFileUrl_thumbnail) && file_exists($remoteFileUrl_thumbnail)) {
                unlink($remoteFileUrl_thumbnail);
            }
        }
        return true;
    }

    public function deleteLocalCompanyLogoAndThumbnail($company)
    {
        $remoteFileUrl_logo = 'public/storage/' . $company->logo;
        $remoteFileUrl_thumbnail = 'public/storage/' . $company->thumbnail;
        if (is_file($remoteFileUrl_logo) && file_exists($remoteFileUrl_logo)) {
            unlink($remoteFileUrl_logo);
        }

        if (is_file($remoteFileUrl_thumbnail) && file_exists($remoteFileUrl_thumbnail)) {
            unlink($remoteFileUrl_thumbnail);
        }
        return true;
    }

    // public function uploadLocalFileToAws(int $questionId, string $fileName): string|bool
    // {
    //     if (!$this->status) {
    //         throw new \RuntimeException("AWS status not active");
    //     }
        
    //     $localPath = "question_attachments/{$fileName}";
    //     // dd($localPath);
        
    //     $sourcePath = Storage::disk('public')->path("question_attachments/{$fileName}");
    //     // dd($sourcePath);

    //     if (!file_exists($sourcePath)) {
    //         throw new \RuntimeException("Source file does not exist at path: {$sourcePath}");
    //     }

    //     $destinationFolder = "{$this->destinationFolder}";
    //     // dd($destinationFolder);
        
    //     // $filePath = "{$destinationFolder}/BxIIwCddWknJULVnIfjrCkMs1TdICRm8aTWf4PRg.jpg";
    //     $filePath = "{$this->destinationFolder}/{$fileName}";
    //     // dd($filePath);

    //     if (Storage::disk($this->disk)->exists($filePath)) {
    //         // dd($fileName);
    //         return $fileName; // File already exists on S3
    //     }

    //     $fileObject = new UploadedFile($sourcePath, $fileName, null, null, true);

    //     // dd($fileObject);
    //     try {
    //         $path = $fileObject->storeAs($destinationFolder, $fileName, $this->disk);
    //         if (Storage::disk('public')->exists($localPath)) {
    //             Storage::disk('public')->delete($localPath);
    //         }
    //         return $path;
    //     } catch (\Exception $e) {
    //         if (Storage::disk('public')->exists($localPath)) {
    //             Storage::disk('public')->delete($localPath);
    //         }
    //         // Log the error for debugging
    //         // logger()->error("AWS S3 upload failed: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // public function deleteFileToAws(int $questionId, string $fileName): string|bool
    // {
    //     // dd($questionId,$fileName);
    //     $filePath = "{$this->destinationFolder}/{$questionId}/{$fileName}";

    //     if (Storage::disk('s3')->exists($filePath)) {
    //         Storage::disk('s3')->delete($filePath);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}

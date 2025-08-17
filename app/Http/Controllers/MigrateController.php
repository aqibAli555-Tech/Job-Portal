<?php

namespace App\Http\Controllers;

use App\Helpers\AWS;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;

class MigrateController extends Controller
{
    public function index()
    {
        $total_cv = $this->getTotal();
        $pending_cv = $this->getPending();
        $total_uploaded_aws_cv = $this->getCompleted();
        $get_total_error_cvs = $this->getErrorCVCount();
        $contact_cv_not_found = $this->getErrorCVCount(2);
        $no_contact_cv_not_found = $this->getErrorCVCount(3);
        $contact_cv_aws_error = $this->getErrorCVCount(4);
        $no_contact_cv_aws_error = $this->getErrorCVCount(5);
        return view('migrate.index', compact('total_cv', 'pending_cv', 'total_uploaded_aws_cv','get_total_error_cvs','contact_cv_not_found','no_contact_cv_not_found','contact_cv_aws_error','no_contact_cv_aws_error'));
    }

    public function getUsers(){
        $users = User::select('id')->where('is_resume_uploaded_on_aws', 0)->whereNotNull('cv_no_contact')->whereNotNull('employee_cv')->where('is_approved_no_contact_cv', 1)->where('user_type_id', 2)->orderBy('id', 'desc')->limit(10)->get();
        $users->makeHidden(['created_at_formatted', 'photo_url']);
        $data = [
            'users' => $users,
            'pending' => $this->getPending(),
            'completed' => $this->getCompleted(),
        ];
        return response()->json($data);
    }

    public function move_file_to_aws(Request $request)
    {
        $id = $request->get('id');
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $data = [
                'status' => false,
                'message' => 'Missing or invalid ID',
                'pending' => $this->getPending(),
                'completed' => $this->getCompleted(),
            ];
            return response()->json($data);
        }

        $user = User::where('id', $id)
            ->whereNotNull('cv_no_contact')
            ->where('employee_cv', '!=', '')
            ->whereNotNull('employee_cv')
            ->where('is_approved_no_contact_cv', 1)
            ->where('user_type_id', 2)
            ->first();

        if (empty($user)){
            $data = [
                'status' => false,
                'message' => 'Invalid User',
                'pending' => $this->getPending(),
                'completed' => $this->getCompleted(),
            ];
            return response()->json($data);
        }

        if($user->is_resume_uploaded_on_aws == 1){
            $data = [
                'status' => false,
                'message' => "File is already uploaded to aws",
                'pending' => $this->getPending(),
                'completed' => $this->getCompleted(),
            ];
            return response()->json($data);
        }

        $relativePath = $user->cv_no_contact;
        $relativeEmployeeCVPath = $user->employee_cv;
        $sourcePath = public_path('storage/' . $relativePath);
        $sourceEmployeeCVPath = public_path('storage/' . $relativeEmployeeCVPath);

        if (file_exists($sourcePath) && file_exists($sourceEmployeeCVPath)) {
            $aws = new AWS();
            $uploadedPath = $aws->uploadStorageFileToAWS($relativePath);
            $uploadedEmployeeCVPath = $aws->uploadStorageFileToAWS($relativeEmployeeCVPath);
            if ($uploadedPath['status'] && $uploadedEmployeeCVPath['status']) {
                $user->is_resume_uploaded_on_aws = 1;
                $status = $uploadedPath['status'];
                $message = $uploadedPath['message'];
            } elseif (!$uploadedPath['status']) {
                $user->is_resume_uploaded_on_aws = 5;
                $status = $uploadedPath['status'];
                $message = "Error uploading No Contact CV :<b> ".$uploadedPath['message']."</b>";
            } elseif (!$uploadedEmployeeCVPath['status']) {
                $user->is_resume_uploaded_on_aws = 4;
                $status = $uploadedEmployeeCVPath['status'];
                $message = "Error uploading Contact CV :<b> ".$uploadedEmployeeCVPath['message']."</b>";
            }
        }else {
            // To skip this file
            if (!file_exists($sourcePath)) {
                $user->is_resume_uploaded_on_aws = 3;
                $status = false;
                $message = "No Contact CV not found on local server";
            } elseif (!file_exists($sourceEmployeeCVPath)) {
                $user->is_resume_uploaded_on_aws = 2;
                $status = false;
                $message = "Contact CV not found on local server";
            }
        }

        $user->save();

        $data = [
            'status' => $status,
            'message' => $message,
            'pending' => $this->getPending(),
            'completed' => $this->getCompleted(),
        ];

        // Return JSON response
        return response()->json($data);
    }

    public function getPending()
    {
        return User::where('user_type_id', 2)
            ->where('employee_cv', '!=', '')
            ->whereNotNull('employee_cv')
            ->whereNull('deleted_at')
            ->whereNotNull('cv_no_contact')
            ->where('is_approved_no_contact_cv', 1)
            ->where('is_resume_uploaded_on_aws', 0)
            ->where('is_active', 1)->count();
    }

    public function getTotal()
    {
        return User::where('user_type_id', 2)
            ->where('employee_cv', '!=', '')
            ->whereNotNull('employee_cv')
            ->whereNull('deleted_at')
            ->whereNotNull('cv_no_contact')
            ->where('is_approved_no_contact_cv', 1)
            ->where('is_active', 1)->count();
    }

    public function getErrorCVCount($status = null)
    {
        $query = User::where('user_type_id', 2)
                ->where('employee_cv', '!=', '')
                ->whereNotNull('employee_cv')
                ->whereNull('deleted_at')
                ->whereNotNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 1)
                ->where('is_active', 1);

        if (is_null($status)) {
            $query->whereIn('is_resume_uploaded_on_aws', [2, 3, 4, 5]);
        } else {
            $query->where('is_resume_uploaded_on_aws', $status);
        }

        return $query->count();
    }

    public function getCompleted()
    {
        return User::where('user_type_id', 2)
            ->where('is_resume_uploaded_on_aws', 1)
            ->where('is_active', 1)->count();
    }

    public function getErrorCVs(Request $request)
    {
        $query = User::where('user_type_id', 2)
        ->where('employee_cv', '!=', '')
        ->whereNotNull('employee_cv')
        ->whereNull('deleted_at')
        ->whereNotNull('cv_no_contact')
        ->where('is_approved_no_contact_cv', 1)
        ->where('is_active', 1);

        if (!empty($request->error_status)) {
            $query->where('is_resume_uploaded_on_aws', $request->error_status);
        } else {
            $query->whereIn('is_resume_uploaded_on_aws', [2, 3, 4, 5]);
        }

        $errorCVs = $query->get();

        return view('migrate.error-cv-users', compact('errorCVs'));
    }

    public function getErrorCVUsers(){
        $users = User::select('id')
        ->where('user_type_id', 2)
        ->whereIn('is_resume_uploaded_on_aws', [2, 3, 4, 5])
        ->where('employee_cv', '!=', '')
        ->whereNotNull('employee_cv')
        ->whereNotNull('cv_no_contact')
        ->where('is_approved_no_contact_cv', 1)
        ->whereNull('deleted_at')
        ->orderBy('id', 'desc')->limit(10)->get();
        $users->makeHidden(['created_at_formatted', 'photo_url']);
        $data = [
            'users' => $users,
            'pending' => $this->getPending(),
            'completed' => $this->getCompleted(),
        ];
        return response()->json($data);
    }

    public function migrateProfileImage()
    {
        $total_images = $this->getTotalProfileImage();
        $pending_images = $this->getPendingProfileImage();
        $total_uploaded_aws_images =$this->getCompletedProfileImage();
        $get_error_profile_images = $this->getErrorProfileImageCount();
        $pictures_not_found = $this->getErrorProfileImageCount(2);
        $thumbnails_not_found = $this->getErrorProfileImageCount(3);
        $pictures_aws_error = $this->getErrorProfileImageCount(4);
        $thumbnails_aws_error = $this->getErrorProfileImageCount(5);
        return view('migrate.profile-image', compact('total_images','pending_images', 'total_uploaded_aws_images','get_error_profile_images','pictures_not_found','thumbnails_not_found','pictures_aws_error','thumbnails_aws_error'));
    }

    public function getProfileImages(){
        $users = User::select('id')->where('is_image_uploaded_on_aws', 0)->whereNotNull('file')->where('file', '!=', '')->whereNotNull('thumbnail')->where('thumbnail', '!=', '')->whereIn('user_type_id', [1, 2, 5])->orderBy('id', 'desc')->limit(10)->get();
        $users->makeHidden(['created_at_formatted', 'photo_url']);
        $data = [
            'users' => $users,
            'pending' => $this->getPendingProfileImage(),
            'completed' => $this->getCompletedProfileImage(),
        ];
        return response()->json($data);
    }

    public function move_image_to_aws(Request $request)
    {
        $id = $request->get('id');
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $data = [
                'status' => false,
                'message' => 'Missing or invalid ID',
                'pending' => $this->getPendingProfileImage(),
                'completed' => $this->getCompletedProfileImage(),
            ];
            return response()->json($data);
        }

        $user = User::where('id', $id)
            ->whereNotNull('file')->where('file', '!=', '')               
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '') 
            ->whereIn('user_type_id', [1, 2, 5])
            ->first();

        if (empty($user)){
            $data = [
                'status' => false,
                'message' => 'Invalid User',
                'pending' => $this->getPendingProfileImage(),
                'completed' => $this->getCompletedProfileImage(),
            ];
            return response()->json($data);
        }

        if($user->is_image_uploaded_on_aws == 1){
            $data = [
                'status' => false,
                'message' => "Image is already uploaded to aws",
                'pending' => $this->getPendingProfileImage(),
                'completed' => $this->getCompletedProfileImage(),
            ];
            return response()->json($data);
        }

        $relativePath = $user->file;
        $relativeThumbPath = $user->thumbnail;
        $sourcePath = public_path('storage/' . $relativePath);
        $sourceThumbPath = public_path('storage/' . $relativeThumbPath);
        if (file_exists($sourcePath) && file_exists($sourceThumbPath)) {
            $aws = new AWS();
            $uploadedPath = $aws->uploadStorageImageToAWS($relativePath);
            $uploadedThumbPath = $aws->uploadStorageImageToAWS($relativeThumbPath);

            if ($uploadedPath['status'] && $uploadedThumbPath['status']) {
                $user->is_image_uploaded_on_aws = 1;
                $status = $uploadedPath['status'];
                $message = $uploadedPath['message'];
            } elseif (!$uploadedPath['status']) {
                $user->is_image_uploaded_on_aws = 4;
                $status = $uploadedPath['status'];
                $message = "Error uploading profile picture :<b> ".$uploadedPath['message']."</b>";
            } elseif (!$uploadedThumbPath['status']) {
                $user->is_image_uploaded_on_aws = 5;
                $status = $uploadedThumbPath['status'];
                $message = "Error uploading thumbnail :<b> ".$uploadedThumbPath['message']."</b>";
            }
        } else {
            // To skip this file
            if (!file_exists($sourcePath)) {
                $user->is_image_uploaded_on_aws = 2;
                $status = false;
                $message = "Profile picture not found on local server";
            } elseif (!file_exists($sourceThumbPath)) {
                $user->is_image_uploaded_on_aws = 3;
                $status = false;
                $message = "Thumbnail not found on local server";
            }
        }

        $user->save();

        $data = [
            'status' => $status,
            'message' => $message,
            'pending' => $this->getPendingProfileImage(),
            'completed' => $this->getCompletedProfileImage(),
        ];

        // Return JSON response
        return response()->json($data);
    }

    public function getPendingProfileImage()
    {
        return User::whereIn('user_type_id', [1, 2, 5])
            ->whereNull('deleted_at')
            ->whereNotNull('file')->where('file', '!=', '')               
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
            ->where('is_image_uploaded_on_aws', 0)
            ->where('is_active', 1)->count();
    }

    public function getCompletedProfileImage()
    {
        return User::whereIn('user_type_id', [1, 2, 5])
            ->where('is_image_uploaded_on_aws', 1)
            ->where('is_active', 1)->count();
    }

    public function getTotalProfileImage()
    {
        return User::whereIn('user_type_id', [1, 2, 5])
            ->whereNull('deleted_at')
            ->whereNotNull('file')->where('file', '!=', '')               
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
            ->where('is_active', 1)->count();
    }

    public function getErrorProfileImage(Request $request)
    {
        $query = User::whereIn('user_type_id', [1, 2, 5])
        ->whereNull('deleted_at')
        ->whereNotNull('file')->where('file', '!=', '')
        ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
        ->where('is_active', 1);

        if (!empty($request->error_status)) {
            $query->where('is_image_uploaded_on_aws', $request->error_status);
        } else {
            $query->whereIn('is_image_uploaded_on_aws', [2, 3, 4, 5]);
        }

        $errorImages = $query->get();

        return view('migrate.error-profile-image', compact('errorImages'));
    }

    public function getProfileErrorImages(){
        $users = User::select('id')->whereIn('is_image_uploaded_on_aws', [2, 3, 4, 5])->whereNotNull('file')->where('file', '!=', '')->whereNotNull('thumbnail')->where('thumbnail', '!=', '')->whereIn('user_type_id', [1, 2, 5])->orderBy('id', 'desc')->limit(10)->get();
        $users->makeHidden(['created_at_formatted', 'photo_url']);
        $data = [
            'users' => $users,
            'pending' => $this->getPendingProfileImage(),
            'completed' => $this->getCompletedProfileImage(),
            'error' => $this->getErrorProfileImageCount(),
        ];
        return response()->json($data);
    }

    public function getErrorProfileImageCount($status = null)
    {
        $query = User::whereIn('user_type_id', [1, 2, 5])
            ->whereNull('deleted_at')
            ->whereNotNull('file')->where('file', '!=', '')
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
            ->where('is_active', 1);

        if (is_null($status)) {
            $query->whereIn('is_image_uploaded_on_aws', [2, 3, 4, 5]);
        } else {
            $query->where('is_image_uploaded_on_aws', $status);
        }

        return $query->count();
    }

    public function migrateCompanyImage()
    {
        $total_images = $this->getTotalCompanyImage();
        $pending_images = $this->getPendingCompanyImage();
        $total_uploaded_aws_images =$this->getCompletedCompanyImage();
        $get_error_profile_images = $this->getErrorCompanyImageCount();
        $pictures_not_found = $this->getErrorCompanyImageCount(2);
        $thumbnails_not_found = $this->getErrorCompanyImageCount(3);
        $pictures_aws_error = $this->getErrorCompanyImageCount(4);
        $thumbnails_aws_error = $this->getErrorCompanyImageCount(5);
        return view('migrate.company-image', compact('total_images','pending_images', 'total_uploaded_aws_images','get_error_profile_images','pictures_not_found','thumbnails_not_found','pictures_aws_error','thumbnails_aws_error'));
    }

    public function getCompanyImages(){
        $companies = Company::select('id')->where('is_image_uploaded_on_aws', 0)->whereNotNull('logo')->where('logo', '!=', '')->whereNotNull('thumbnail')->where('thumbnail', '!=', '')->whereNull('deleted_at')->orderBy('id', 'desc')->limit(10)->get();
        $companies->makeHidden(['created_at_formatted', 'photo_url']);
        $data = [
            'companies' => $companies,
            'pending' => $this->getPendingCompanyImage(),
            'completed' => $this->getCompletedCompanyImage(),
        ];
        return response()->json($data);
    }

    public function move_company_image_to_aws(Request $request)
    {
        $id = $request->get('id');
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $data = [
                'status' => false,
                'message' => 'Missing or invalid ID',
                'pending' => $this->getPendingCompanyImage(),
                'completed' => $this->getCompletedCompanyImage(),
            ];
            return response()->json($data);
        }

        $company = Company::where('id', $id)
            ->whereNotNull('logo')->where('logo', '!=', '')       
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '') 
            ->first();

        if (empty($company)){
            $data = [
                'status' => false,
                'message' => 'Invalid User',
                'pending' => $this->getPendingCompanyImage(),
                'completed' => $this->getCompletedCompanyImage(),
            ];
            return response()->json($data);
        }

        if($company->is_image_uploaded_on_aws == 1){
            $data = [
                'status' => false,
                'message' => "Image is already uploaded to aws",
                'pending' => $this->getPendingCompanyImage(),
                'completed' => $this->getPendingCompanyImage(),
            ];
            return response()->json($data);
        }

        $relativePath = $company->logo;
        $relativeThumbPath = $company->thumbnail;
        $sourcePath = public_path('storage/' . $relativePath);
        $sourceThumbPath = public_path('storage/' . $relativeThumbPath);
        
        if (file_exists($sourcePath) && file_exists($sourceThumbPath)) {
            $aws = new AWS();
            $uploadedPath = $aws->uploadStorageCompanyImageToAWS($relativePath);
            $uploadedThumbPath = $aws->uploadStorageCompanyImageToAWS($relativeThumbPath);
            if ($uploadedPath['status'] && $uploadedThumbPath['status']) {
                $company->is_image_uploaded_on_aws = 1;
                $status = $uploadedPath['status'];
                $message = $uploadedPath['message'];
            } elseif (!$uploadedPath['status']) {
                $company->is_image_uploaded_on_aws = 4;
                $status = $uploadedPath['status'];
                $message = "Error uploading company picture :<b> ".$uploadedPath['message']."</b>";
            } elseif (!$uploadedThumbPath['status']) {
                $company->is_image_uploaded_on_aws = 5;
                $status = $uploadedThumbPath['status'];
                $message = "Error uploading thumbnail :<b> ".$uploadedThumbPath['message']."</b>";
            }
        } else {
            // To skip this file
            if (!file_exists($sourcePath)) {
                $company->is_image_uploaded_on_aws = 2;
                $status = false;
                $message = "Company picture not found on local server";
            } elseif (!file_exists($sourceThumbPath)) {
                $company->is_image_uploaded_on_aws = 3;
                $status = false;
                $message = "Thumbnail not found on local server";
            }

        }

        $company->save();

        $data = [
            'status' => $status,
            'message' => $message,
            'pending' => $this->getPendingCompanyImage(),
            'completed' => $this->getCompletedCompanyImage(),
        ];

        // Return JSON response
        return response()->json($data);
    }

    public function getPendingCompanyImage()
    {
        return Company::whereNull('deleted_at')
            ->whereNotNull('logo')->where('logo', '!=', '')       
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
            ->where('is_image_uploaded_on_aws', 0)->count();
    }

    public function getCompletedCompanyImage()
    {
        return Company::where('is_image_uploaded_on_aws', 1)->count();
    }

    public function getTotalCompanyImage()
    {
        return Company::whereNull('deleted_at')
            ->whereNotNull('logo')->where('logo', '!=', '')               
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')->count();
    }

    public function getErrorCompanyImage(Request $request)
    {
        $query = Company::whereNull('deleted_at')
        ->whereNotNull('logo')->where('logo', '!=', '')
        ->whereNotNull('thumbnail')->where('thumbnail', '!=', '');

        if (!empty($request->error_status)) {
            $query->where('is_image_uploaded_on_aws', $request->error_status);
        } else {
            $query->whereIn('is_image_uploaded_on_aws', [2, 3, 4, 5]);
        }

        $errorImages = $query->get();

        return view('migrate.error-company-image', compact('errorImages'));
    }

    public function getCompanyErrorImages(){
        $companies = Company::select('id')->whereIn('is_image_uploaded_on_aws', [2, 3, 4, 5])->whereNotNull('logo')->where('logo', '!=', '')->whereNotNull('thumbnail')->where('thumbnail', '!=', '')->orderBy('id', 'desc')->whereNull('deleted_at')->limit(10)->get();
        $companies->makeHidden(['created_at_formatted', 'photo_url']);
        $data = [
            'companies' => $companies,
            'pending' => $this->getPendingCompanyImage(),
            'completed' => $this->getCompletedCompanyImage(),
            'error' => $this->getErrorCompanyImageCount(),
        ];
        return response()->json($data);
    }

    public function getErrorCompanyImageCount($status = null)
    {
        $query = Company::whereNull('deleted_at')
            ->whereNotNull('logo')->where('logo', '!=', '')
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '');

        if (is_null($status)) {
            $query->whereIn('is_image_uploaded_on_aws', [2, 3, 4, 5]);
        } else {
            $query->where('is_image_uploaded_on_aws', $status);
        }

        return $query->count();
    }

    public function CronToUploadResumeForNewUser()
    {
        $users = User::whereNotNull('cv_no_contact')
        ->where('employee_cv', '!=', '')
        ->whereNotNull('employee_cv')
        ->where('is_resume_uploaded_on_aws', 0)
        ->where('is_approved_no_contact_cv', 1)
        ->where('user_type_id', 2)
        ->limit(10)->get();

        foreach($users as $user){
            $relativePath = $user->cv_no_contact;
            $relativeEmployeeCVPath = $user->employee_cv;
            $sourcePath = public_path('storage/' . $relativePath);
            $sourceEmployeeCVPath = public_path('storage/' . $relativeEmployeeCVPath);
            if (file_exists($sourcePath) && file_exists($sourceEmployeeCVPath)) {
                $aws = new AWS();
                //Delete resume from AWS
                $aws->deleteStorageResumeFolderFromAWS($user->id);

                $uploadedPath = $aws->uploadStorageFileToAWS($relativePath);
                $uploadedEmployeeCVPath = $aws->uploadStorageFileToAWS($relativeEmployeeCVPath);
                if ($uploadedPath['status'] && $uploadedEmployeeCVPath['status']) {
                    $user->is_resume_uploaded_on_aws = 1;
                    //Delete resume from local
                    $aws->deleteLocalResumeAndNoContactCV($user);
                } elseif (!$uploadedPath['status']) {
                    $user->is_resume_uploaded_on_aws = 5;
                } elseif (!$uploadedEmployeeCVPath['status']) {
                    $user->is_resume_uploaded_on_aws = 4;
                }
            } else {
                // To skip this file
                if (!file_exists($sourcePath)) {
                    $user->is_resume_uploaded_on_aws = 3;
                } elseif (!file_exists($sourceEmployeeCVPath)) {
                    $user->is_resume_uploaded_on_aws = 2;
                }
            }
    
            $user->save();
        }
        echo "New users resume uploaded on AWS";
    }

    public function CronToUploadImageForNewUser()
    {
        $users = User::whereNotNull('file')->where('file', '!=', '')               
        ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
        ->where('is_image_uploaded_on_aws', 0) 
        ->whereIn('user_type_id', [1, 2, 5])
        ->limit(10)->get();

        foreach($users as $user){
            $relativePath = $user->file;
            $relativeThumbPath = $user->thumbnail;
            $sourcePath = public_path('storage/' . $relativePath);
            $sourceThumbPath = public_path('storage/' . $relativeThumbPath);
            if (file_exists($sourcePath) && file_exists($sourceThumbPath)) {
                $aws = new AWS();
                //Delete image from AWS
                $aws->deleteStorageImageFolderFromAWS($user->id);

                $uploadedPath = $aws->uploadStorageImageToAWS($relativePath);
                $uploadedThumbPath = $aws->uploadStorageImageToAWS($relativeThumbPath);
    
                if ($uploadedPath['status'] && $uploadedThumbPath['status']) {
                    $user->is_image_uploaded_on_aws = 1;
                    //Delete image from local
                    $aws->deleteLocalImageAndThumbnail($user);
                } elseif (!$uploadedPath['status']) {
                    $user->is_image_uploaded_on_aws = 4;
                } elseif (!$uploadedThumbPath['status']) {
                    $user->is_image_uploaded_on_aws = 5;
                }
            } else {
                // To skip this file
                if (!file_exists($sourcePath)) {
                    $user->is_image_uploaded_on_aws = 2;
                } elseif (!file_exists($sourceThumbPath)) {
                    $user->is_image_uploaded_on_aws = 3;
                }    
            }
            $user->save();
        }
        echo "New users resume uploaded on AWS";
    }

    public function CronToUploadImageForNewCompany()
    {
        $companies = Company::whereNotNull('logo')->where('logo', '!=', '')               
        ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
        ->where('is_image_uploaded_on_aws', 0)
        ->whereNull('deleted_at')
        ->limit(10)->get();

        foreach($companies as $company){
            $relativePath = $company->logo;
            $relativeThumbPath = $company->thumbnail;
            $sourcePath = public_path('storage/' . $relativePath);
            $sourceThumbPath = public_path('storage/' . $relativeThumbPath);
            if (file_exists($sourcePath) && file_exists($sourceThumbPath)) {
                $aws = new AWS();
                //Delete image from AWS
                $aws->deleteStorageCompanyImageFolderFromAWS($company->c_id);

                $uploadedPath = $aws->uploadStorageCompanyImageToAWS($relativePath);
                $uploadedThumbPath = $aws->uploadStorageCompanyImageToAWS($relativeThumbPath);
    
                if ($uploadedPath['status'] && $uploadedThumbPath['status']) {
                    $company->is_image_uploaded_on_aws = 1;
                    //Delete logo and thumbnail from local
                    $aws->deleteLocalCompanyLogoAndThumbnail($company);
                } elseif (!$uploadedPath['status']) {
                    $company->is_image_uploaded_on_aws = 4;
                } elseif (!$uploadedThumbPath['status']) {
                    $company->is_image_uploaded_on_aws = 5;
                }
            } else {
                // To skip this file
                if (!file_exists($sourcePath)) {
                    $company->is_image_uploaded_on_aws = 2;
                } elseif (!file_exists($sourceThumbPath)) {
                    $company->is_image_uploaded_on_aws = 3;
                }    
            }
            $company->save();
        }
        echo "New company picture uploaded on AWS";
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmployeeRegisterRequest;
use App\Http\Requests\Api\V1\EmployerRegisterRequest;
use App\Models\Company;
use App\Models\Permission;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Imagick;

class RegisterController extends Controller
{
    public function employee_register(EmployeeRegisterRequest $request)
    {
        // Validate the request
        $validatedData = $request->all();

        $validator = Validator::make($validatedData, $request->rules());

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'errors' => $errors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = new User();
        // Create a new user with the validated data

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->password_without_hash = $validatedData['password'];
        $user->skill_set = $validatedData['skill_set'];
        $user->nationality = $validatedData['nationality'];
        $user->experiences = $validatedData['experiences'];
        $user->accept_terms = $validatedData['accept_terms'];
        $user->country_code = $validatedData['country_code'];
        $user->city = $validatedData['city'];
        $user->user_type_id = $validatedData['user_type_id'];
        $user->availability = $validatedData['availability'];
        // Add other fields as needed

        if ($user->save()) {
            $user_id = $user->id;
            $file = $request->file('file');
            if (!empty($file)) {
                $file_type = $file->getClientOriginalExtension();
                $destinationPath = public_path('/') . 'storage/pictures/kw/' . $user_id;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $result = Helper::validateUserProfileImage($file);

                if (!$result) {
                    $fileName = 'pictures/default.jpg';
                } else {
                    $fileName = 'pictures/kw/' . $user_id . '/profile_' . time() . "." . $file_type;
                }

                $file->move($destinationPath, $fileName);
                $user_up = User::withoutGlobalScopes()->where('id', $user_id)->first();
                $user_up->file = $fileName;
                $user_up->save();


                $url = url('public/storage/' . $fileName);
                $unique = 'thumbnail_' . uniqid() . '.jpg';
                $im = new Imagick($url);
                $imageprops = $im->getImageGeometry();
                $width = $imageprops['width'];
                $height = $imageprops['height'];
                if ($width > $height) {
                    $newHeight = 80;
                    $newWidth = (80 / $height) * $width;
                } else {
                    $newWidth = 80;
                    $newHeight = (80 / $width) * $height;
                }
                $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
                // $im->cropImage (80,80,0,0);
                $im->writeImage(public_path('/') . 'storage/pictures/kw/' . $user_id . '/' . $unique);
                $values = array(
                    'thumbnail' => 'pictures/kw/' . $user_id . '/' . $unique,
                );

                User::where('id', $user_id)->update($values);


            }
            $cv_file = $request->file('cv');
            if (!empty($cv_file)) {
                $file_type_cv = $cv_file->getClientOriginalExtension();
                $destinationPathCv = public_path('/') . 'storage/employee_cv/' . $user_id;
                if (!file_exists($destinationPathCv)) {
                    mkdir($destinationPathCv, 0777, true);
                }
                $fileNameCv = 'employee_cv/' . $user_id . '/' . time() . "." . $file_type_cv;
                $cv_file->move($destinationPathCv, $fileNameCv);
                $user_up = User::withoutGlobalScopes()->where('id', $user_id)->first();
                $user_up->employee_cv = $fileNameCv;
                $user_up->save();
            }
            $type = "Employee";
            $name = $request->get('name');
            $email = $request->get('email');
            $profile_url = admin_url() . '/job-seekers?search=' . $email;
            $description = "A new user Name: <a href='$profile_url'>$name</a> register as a $type ";
            Helper::activity_log($description);
            if (config('settings.mail.admin_notification') == 1) {
                try {
                    // Get all admin users
                    $admins = User::permission(Permission::getStaffPermissions())->get();
                    if ($admins->count() > 0) {
                        foreach ($admins as $admin) {
                            EmailHelper::sendadminemail($admin, $user);
                            EmailHelper::senduserregisteremail($user);

                        }
                    }
                } catch (Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

            return response()->json([
                'message' => 'User created successfully',
                'data' => '',
            ], Response::HTTP_CREATED);

        } else {
            return response()->json([
                'message' => 'Unabled To Save Data',
                'data' => '',
            ], 400);
        }
    }

    public function employer_register(EmployerRegisterRequest $request)
    {
        // Validate the request
        $validatedData = $request->all();

        $validator = Validator::make($validatedData, $request->rules());

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                'errors' => $errors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = new User();
        // Create a new user with the validated data

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->password_without_hash = $validatedData['password'];
        $user->accept_terms = $validatedData['accept_terms'];
        $user->country_code = $validatedData['country_code'];
        $user->city = $validatedData['city'];
        $user->user_type_id = $validatedData['user_type_id'];
        // Add other fields as needed

        if ($user->save()) {
            $user_id = $user->id;
            $file = $request->file('file');
            if (!empty($file)) {
                $file_type = $file->getClientOriginalExtension();
                $destinationPath = public_path('/') . 'storage/pictures/kw/' . $user_id;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $result = Helper::validateUserProfileImage($file);

                if (!$result) {
                    $fileName = 'pictures/default.jpg';
                } else {
                    $fileName = 'pictures/kw/' . $user_id . '/profile_' . time() . "." . $file_type;
                }

                $file->move($destinationPath, $fileName);
                $user_up = User::withoutGlobalScopes()->where('id', $user_id)->first();
                $user_up->file = $fileName;
                $user_up->save();


                $url = url('public/storage/' . $fileName);
                $unique = 'thumbnail_' . uniqid() . '.jpg';
                $im = new Imagick($url);
                $imageprops = $im->getImageGeometry();
                $width = $imageprops['width'];
                $height = $imageprops['height'];
                if ($width > $height) {
                    $newHeight = 80;
                    $newWidth = (80 / $height) * $width;
                } else {
                    $newWidth = 80;
                    $newHeight = (80 / $width) * $height;
                }
                $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
                // $im->cropImage (80,80,0,0);
                $im->writeImage(public_path('/') . 'storage/pictures/kw/' . $user_id . '/' . $unique);
                $values = array(
                    'thumbnail' => 'pictures/kw/' . $user_id . '/' . $unique,
                );

                User::where('id', $user_id)->update($values);
                if (!empty($user_up)) {
                    if ($request->get('user_type_id') == 1) {
                        $type = 'Employer';
                        $user_up->parent_id = $user_id;
                        $user_up->save();
                        $compnay['user_id'] = $user_id;
                        $compnay['c_id'] = $user_id;
                        $compnay['description'] = '';
                        $compnay['country_code'] = config('country.code');
                        $compnay['city_id'] = $request['city_id'];
                        $compnay['phone'] = $user_up->phone;
                        $compnay['name'] = $user_up->name;
                        $compnay['email'] = $user_up->email;
                        $causes = !empty($request->get('causes')) ? $request->get('causes') : '';
                        $compnay['causes'] = $causes;
                        $entities = !empty($request->get('entities')) ? $request->get('entities') : '';
                        $compnay['entities'] = $entities;
                        $compnay['logo'] = $user_up->file;
                        $compnay['thumbnail'] = $user_up->thumbnail;
                        Company::insert($compnay);
                    }

                    $name = $request->get('name');
                    $email = $request->get('email');
                    if ($request->get('user_type_id') == 1) {
                        $profile_url = admin_url() . '/employer?search=' . $email;
                    }

                    $description = "A new user Name: <a href='$profile_url'>$name</a> register as a $type ";
                    Helper::activity_log($description);
                    if (config('settings.mail.admin_notification') == 1) {
                        try {
                            // Get all admin users
                            $admins = User::permission(Permission::getStaffPermissions())->get();
                            if ($admins->count() > 0) {
                                foreach ($admins as $admin) {
                                    EmailHelper::sendadminemail($admin, $user);
                                    EmailHelper::senduserregisteremail($user);

                                }
                            }
                        } catch (Exception $e) {
                            flash($e->getMessage())->error();
                        }
                    }
                }
            }

            return response()->json([
                'message' => 'User created successfully',
                'data' => '',
            ], Response::HTTP_CREATED);
        } else {

            return response()->json([
                'message' => 'Unabled to Save Data',
                'data' => '',
            ], 400);
        }

    }

}
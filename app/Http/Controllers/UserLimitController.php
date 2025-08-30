<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\UserLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserLimitController extends Controller
{
    // public function index()
    // {
    //     $companies = Company::get();
    //     return view('pos.user_limit.user_limit', compact('companies'));
    // }

    public function index()
    {
        try {
            // Attempt to retrieve the list of companies
            $companies = Company::get();

            // Render the view with the retrieved data
            return view('pos.user_limit.user_limit', compact('companies'));
        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error and show an error message)
            Log::error('Failed to retrieve companies: '.$e->getMessage());

            // Option 1: Redirect to an error page
            return response()->view('errors.500', [], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|integer',
                'user_limit' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => '500',
                    'error' => $validator->messages(),
                ]);
            }

            // If validation passes, proceed with saving the bank details
            $userLimit = new UserLimit;
            $userLimit->company_id = $request->company_name;
            $userLimit->user_limit = $request->user_limit;
            $userLimit->save();

            return response()->json([
                'status' => 200,
                'message' => 'User Limit Successful',
            ]);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error to Set User Limit: '.$e->getMessage());

            // Return the errors.500 view for internal server errors
            return response()->view('errors.500', [], 500);
        }
    }

    public function view()
    {
        try {
            // Attempt to retrieve user limits with company information
            $userLimits = UserLimit::with('company')->get();

            // Return a successful JSON response
            return response()->json([
                'status' => 200,
                'data' => $userLimits,
            ]);
        } catch (\Exception $e) {
            // Handle the exception by logging the error
            Log::error('Failed to retrieve user limits: '.$e->getMessage());

            // Return an error JSON response
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching user limits. Please try again later.',
            ], 500);
        }
    }

    public function edit($id)
    {
        $userLimit = UserLimit::findOrFail($id);
        if ($userLimit) {
            return response()->json([
                'status' => 200,
                'userLimit' => $userLimit,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|integer',
            'user_limit' => 'required|integer',
        ]);
        if ($validator->passes()) {
            $userLimit = UserLimit::findOrFail($id);
            $userLimit->company_id = $request->company_name;
            $userLimit->user_limit = $request->user_limit;
            $userLimit->save();

            return response()->json([
                'status' => 200,
                'message' => 'User Limit Update Successful',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function delete($id)
    {
        $userLimit = UserLimit::findOrFail($id);
        $userLimit->delete();

        return response()->json([
            'status' => 200,
            'message' => 'User Limit Deleted Successfully',
        ]);
    }
}

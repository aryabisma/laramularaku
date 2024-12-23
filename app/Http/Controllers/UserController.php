<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserCreated;
use App\Mail\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /** 
     * store new user to database
     * @request request data
    **/
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'name' => 'required|string|min:3|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        
        // Send email to the user
        $userNotification = new Notification(
            'Welcome '.$user->name.' to Laramu-Laraku',
            'emails.user_created',
            $user,
        ); 
        // Mail::to($user->email)->send(new UserCreated($user));
        Mail::to($user->email)->send($userNotification);

        // Send email to the administrator
        $adminEmail = env('MAIL_ADMIN_ADDRESS');
        $adminNotification = new Notification(
            'ADMIN Notification: New User '.$user->name.' created',
            'emails.user_created_notify_admin',
            $user,
        ); 
        Mail::to($adminEmail)->send($adminNotification);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
        ], 201,[],JSON_PRETTY_PRINT);
    }

    /** 
     * get list of users to database
     * @request request data
    **/
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'created_at');

        $users = User::withCount('orders')
            ->where('active', 1)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortBy)
            ->paginate(10, ['id', 'name', 'email', 'created_at'], 'page', $page);

        $users->getCollection()->transform(function ($user) {
            $user->orders_count = $user->orders->count();
            if(!$user->orders_count)
                $user->orders_count = 0;
            $user->makeHidden('password');
            return $user;

        });

        return response()->json([
            'page' => $page,
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'orders_count' => $user->orders_count,
                ];
            }),
        ],200,[],JSON_PRETTY_PRINT);
    }
}


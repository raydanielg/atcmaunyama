<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($s = $request->get('s')) {
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }
        $users = $query->orderByDesc('id')->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users', 's'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Generate a temporary password if none provided
        $plainPassword = $data['password'] ?? Str::random(10);
        $data['password'] = $plainPassword; // Will be hashed by User model cast

        // Optional extras if present in form
        if ($request->filled('phone')) $data['phone'] = $request->string('phone');
        if ($request->filled('region_id')) $data['region_id'] = (int) $request->input('region_id');

        $user = User::create($data);

        ActivityLog::log('user.created', "Created user {$user->email} with role {$user->role}", auth()->id());

        // Show the password once if it was auto-generated
        $status = 'User created successfully.';
        if (!$request->filled('password')) {
            $status .= ' Temporary password: ' . $plainPassword;
        }

        return redirect()->route('users.index')->with('status', $status);
    }

    public function updateRole(Request $request, User $user)
    {
        $this->authorizeAction($user, 'role');
        // Disallow changing own role
        if (auth()->id() === $user->id) {
            abort(403, 'You cannot change your own role.');
        }
        $data = $request->validate([
            'role' => 'required|in:admin,user',
        ]);
        $old = (string) $user->role;
        $user->role = $data['role'];
        $user->save();
        ActivityLog::log('user.role_changed', "Changed role of {$user->email} from {$old} to {$user->role}", auth()->id());
        return redirect()->back()->with('status', 'User role updated.');
    }

    public function ban(Request $request, User $user)
    {
        $this->authorizeAction($user, 'ban');
        $user->banned_at = now();
        $user->save();
        ActivityLog::log('user.banned', "Banned user {$user->email}", auth()->id());
        return redirect()->route('users.index')->with('status', 'User banned.');
    }

    public function unban(Request $request, User $user)
    {
        $this->authorizeAction($user, 'unban');
        $user->banned_at = null;
        $user->save();
        ActivityLog::log('user.unbanned', "Unbanned user {$user->email}", auth()->id());
        return redirect()->route('users.index')->with('status', 'User unbanned.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeAction($user, 'delete');
        $email = $user->email;
        $user->delete();
        ActivityLog::log('user.deleted', "Deleted user {$email}", auth()->id());
        return redirect()->route('users.index')->with('status', 'User deleted.');
    }

    private function authorizeAction(User $user, string $action): void
    {
        // Prevent acting on self
        if (auth()->id() === $user->id && in_array($action, ['ban','delete','role'])) {
            abort(403, 'You cannot perform this action on yourself.');
        }
        // Protect the very first user (primary admin)
        if ($user->id === 1 && in_array($action, ['ban','delete','role','unban'])) {
            abort(403, 'Action not allowed on the primary administrator.');
        }
        // Protect super administrator
        if (strtolower((string)$user->role) === 'super administrator') {
            abort(403, 'Action not allowed on super administrator.');
        }
    }
}

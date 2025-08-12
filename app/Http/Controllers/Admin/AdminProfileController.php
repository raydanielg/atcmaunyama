<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // Regions for select
        $regions = Region::query()->orderBy('name')->get();
        // Feedbacks received by this admin (graceful if table missing)
        $perPage = 10;
        if (Schema::hasTable('feedback')) {
            $feedbacks = Feedback::with(['sender'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate($perPage);
        } else {
            $feedbacks = new LengthAwarePaginator(collect(), 0, $perPage, (int) $request->input('page', 1), [
                'path' => url()->current(),
            ]);
        }

        return view('admin.profile.index', compact('user', 'regions', 'feedbacks'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'region_id' => 'nullable|exists:regions,id',
        ]);
        $user->fill($data);
        $user->save();

        return redirect()->route('admin.profile.index')->with('status', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);
        $user->password = Hash::make($validated['password']);
        $user->save();
        return back()->with('status', 'Password updated successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller {
    public function usersIndex(Request $request): View {
        $currentUser = request()->user();
        $users = User::all();
        $users = $users->filter(function (User $user) use ($currentUser) {
            return $user->id !== $currentUser->id;
        });
        if($request->user()->hasPermission(Permissions::ADMIN)){
            return view('admin.users.users', ['users' => $users]);
        }
        abort(404);
    }

    public function usersEdit(Request $request, int $userId): View {
        $user = User::find($userId);
        return $request->user()->hasPermission(Permissions::ADMIN) ?
            view('admin.users.edit', ['user' => $user]) :
            view('errors.404');
    }

    public function usersUpdate(ProfileUpdateRequest $request, int $userId): View|RedirectResponse {
        if(!$request->user()->hasPermission(Permissions::ADMIN)){
            abort(404);
        }
        $user = User::find($userId);
        $user->fill($request->validated());

        $user->save();

        return back()->with('status', 'profile-updated');
    }

    public function usersUpdatePassword(Request $request, int $userId): View|RedirectResponse {
        if(!$request->user()->hasPermission(Permissions::ADMIN)){
            abort(404);
        }
        $user = User::find($userId);

        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function usersDestroy(Request $request, int $userId): RedirectResponse {
        if( !$request->user()->hasPermission(Permissions::ADMIN)){
            abort(404);
        }
        $user = User::find($userId);
        $user->delete();

        return back();
    }
}

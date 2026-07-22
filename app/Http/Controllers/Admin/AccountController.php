<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    public function edit()
    {
        return view('admin.account');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,'.$user->id],
            'current_password' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', Password::min(10)->letters()->numbers()],
        ], [
            'name.required' => 'اكتب الاسم.',
            'email.required' => 'اكتب البريد الإلكتروني.',
            'email.unique' => 'هذا البريد مستخدم بالفعل.',
            'current_password.required' => 'اكتب كلمة المرور الحالية للتأكيد.',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 10 خانات.',
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'كلمة المرور الحالية غير صحيحة.',
            ]);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('ok', empty($data['password'])
            ? 'تم حفظ بيانات الحساب.'
            : 'تم تغيير كلمة المرور بنجاح.');
    }
}

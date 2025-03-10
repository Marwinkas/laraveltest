<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    /**
     * Отображает страницу администратора с данными пользователей.
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        // Извлекаем данные всех пользователей
        $userNames = User::pluck('name');
        $emails = User::pluck('email');
        $passwords = User::pluck('password');
        $users = User::select('id', 'name', 'email')->get();

        // Возвращаем страницу с данными для отображения в Inertia
        return Inertia::render('Profile/Admin', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'userNames' => $userNames,
            'emails' => $emails,
            'passwords' => $passwords,
            'users' => $users,
        ]);
    }

    /**
     * Создает нового пользователя.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Валидация данных запроса
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Создание нового пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Хеширование пароля
        ]);

        // Событие регистрации пользователя
        event(new Registered($user));

        // Перенаправление с сообщением об успешном создании
        return back()->with('success', 'Пользователь успешно создан!');
    }

    /**
     * Обновляет данные пользователя (например, пароль).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        // Находим пользователя по ID
        $user = User::findOrFail($request->id);

        // Обновляем пароль пользователя
        $user->update([
            'password' => Hash::make($request->password),  // Хеширование нового пароля
        ]);

        // Перенаправление назад
        return back();
    }

    /**
     * Удаляет пользователя.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Находим пользователя по ID
        $user = User::findOrFail($request->id);

        // Удаляем пользователя
        $user->delete();

        // Перенаправление на страницу администрирования
        return Redirect::route('admin.show');
    }
}

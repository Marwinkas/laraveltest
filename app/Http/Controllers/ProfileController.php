<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Отображает страницу редактирования профиля пользователя.
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        // Извлекаем имена, email и пароли всех пользователей
        $userNames = User::pluck('name');
        $emails = User::pluck('email');
        $passwords = User::pluck('password');
        $user = Auth::user();
        // Получаем список пользователей с их id, именами и email
        $users = User::select('id', 'name', 'email')->get();
        $books = Reservation::where('user_id', $user->id)->pluck('book_id');
        $books = Book::whereIn('id', $books)->where('status', 'reserved')->get();
        // Возвращаем страницу редактирования профиля с необходимыми данными
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,  // Проверка, нужно ли подтверждать email
            'status' => session('status'),                                    // Статус (например, сообщение об успешном изменении)
            'userNames' => $userNames,                                         // Имена пользователей
            'emails' => $emails,                                               // Email пользователей
            'passwords' => $passwords,                                         // Пароли пользователей (хотя их лучше не выводить)
            'users' => $users,
            'books' => $books,                                                // Список пользователей
        ]);
    }

    /**
     * Обновляет данные профиля пользователя.
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Заполняем текущего пользователя данными из запроса
        $request->user()->fill($request->validated());

        // Если пользователь изменил email, сбрасываем поле о подтверждении email
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Сохраняем обновленные данные пользователя
        $request->user()->save();

        // Перенаправляем на страницу редактирования профиля
        return Redirect::route('profile.edit');
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

        // Перенаправляем на страницу редактирования профиля
        return Redirect::route('profile.edit');
    }
}

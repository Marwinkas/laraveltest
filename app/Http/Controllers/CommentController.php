<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Сохраняет новый комментарий для книги.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Получаем текущего авторизованного пользователя
        $user = Auth::user();

        // Создаем новый комментарий с данными из запроса
        Comment::create([
            'user_id' => $user->id,         // ID пользователя, который оставил комментарий
            'book_id' => $request->book_id, // ID книги, к которой относится комментарий
            'comment' => $request->content, // Текст комментария
            'rating'  => 5,                 // Оценка комментария (по умолчанию 5)
        ]);

        // Перенаправляем назад с сообщением об успешном добавлении комментария
        return redirect()->back()->with('success', 'Комментарий добавлен');
    }
}

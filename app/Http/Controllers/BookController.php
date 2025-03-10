<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class BookController extends Controller
{
    /**
     * Отображает подробную информацию о книге и проверяет возможность ее бронирования.
     *
     * @param int $id - ID книги
     * @return Response
     */
    public function show($id)
    {
        // Находим книгу по ID
        $book = Book::findOrFail($id);

        // Извлекаем комментарии книги с информацией о пользователе, который их оставил
        $comments = $book->comments()->with('user')->latest()->get();

        // Флаг для проверки, можно ли зарезервировать книгу
        $canreserve = false;

        // Проверяем, если пользователь авторизован и книга зарезервирована или доступна для бронирования
        $reservation = Reservation::where('book_id', $book->id)->where('user_id', Auth::id())->first();
        if ((Auth::user() && $reservation && $book->status == 'reserved') || Auth::user() && $book->status == 'available') {
            $canreserve = true;
        }

        // Возвращаем страницу с деталями книги и комментариями
        return Inertia::render('BookDetail', [
            'book' => $book,
            'comments' => $comments,
            'canreserve' => $canreserve
        ]);
    }

    /**
     * Создает новую книгу в библиотеке.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Создаем новую книгу с данными из запроса
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'link' => $request->link,
            'genre' => $request->genre,
            'publisher' => $request->publisher,
        ]);

        // Перенаправляем на главную страницу
        return redirect()->route('main');
    }

    /**
     * Удаляет книгу из библиотеки.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Находим книгу по ID
        $book = Book::findOrFail($request->id);

        // Удаляем книгу
        $book->delete();

        // Перенаправляем назад с сообщением об успешном удалении
        return redirect()->back()->with('success', 'Книга удалена');
    }

    /**
     * Отображает все книги и их бронирования для администраторского профиля.
     *
     * @param Request $request
     * @return Response
     */
    public function library(Request $request): Response
    {
        // Извлекаем все книги
        $books = Book::all();

        // Извлекаем все бронирования с информацией о пользователях и книгах
        $reservations = Reservation::with('user', 'book')->get();

        // Возвращаем страницу с данными о книгах и бронированиях
        return Inertia::render('Profile/Library', [
            'books' => $books,
            'reservations' => $reservations,
        ]);
    }
}

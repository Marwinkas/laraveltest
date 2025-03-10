<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ReservationController extends Controller
{
    /**
     * Создает новое бронирование для книги.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Валидируем, что переданный ID книги существует в базе данных
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Находим книгу по переданному ID
        $book = Book::findOrFail($request->book_id);

        // Проверяем, доступна ли книга для бронирования
        if ($book->status != 'available') {
            return back()->with('error', 'Книга не доступна для бронирования.');
        }

        // Создаем новое бронирование
        Reservation::create([
            'user_id' => Auth::id(), // ID текущего пользователя
            'book_id' => $book->id,  // ID книги
            'reserved_at' => now(),  // Время бронирования
            'expires_at' => now()->addMinutes(5), // Время истечения бронирования (через 5 секунд)
        ]);

        // Обновляем статус книги на "зарезервирована"
        $book->update(['status' => 'reserved', 'reservation_expires_at' => now()->addMinutes(5)]);

        // Перенаправляем на главную страницу с сообщением об успешном бронировании
        return redirect()->route('main', $book)->with('success', 'Книга успешно забронирована!');
    }

    /**
     * Отменяет бронирование книги.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancel(Request $request)
    {
        // Валидируем, что переданный ID книги существует в базе данных
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Находим книгу по переданному ID
        $book = Book::findOrFail($request->book_id);

        // Ищем бронирование текущего пользователя для данной книги
        $reservation = Reservation::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->first();

        // Если бронирования нет, выводим сообщение
        if (!$reservation) {
            return back()->with('success', 'Вы не резервировали эту книгу.');
        }

        // Удаляем бронирование и обновляем статус книги на "доступна"
        $reservation->delete();
        $book->update(['status' => 'available', 'reservation_expires_at' => null]);

        // Перенаправляем назад с сообщением об отмене бронирования
        return back()->with('success', 'Бронирование снято.');
    }

    /**
     * Выдает книгу пользователю по активному бронированию.
     *
     * @param Request $request
     * @param int $id - ID бронирования
     * @return RedirectResponse
     */
    public function deliver(Request $request, $id): RedirectResponse
    {
        // Находим бронирование и книгу по ID
        $reservation = Reservation::findOrFail($id);
        $book = Book::findOrFail($reservation->book_id);

        // Проверяем, что бронирование активно
        if ($reservation->status !== 'active') {
            return redirect()->back()->with('error', 'Невозможно выдать книгу, бронирование не активно.');
        }

        // Обновляем статус бронирования на "выполнено"
        $reservation->update(['status' => 'completed']);

        // Обновляем статус книги на "выдана"
        $book->update(['status' => 'issued']);

        // Перенаправляем назад с сообщением о выдаче книги
        return redirect()->back()->with('success', 'Книга выдана пользователю.');
    }

    /**
     * Принимает возвращенную книгу от пользователя.
     *
     * @param Request $request
     * @param int $id - ID бронирования
     * @return RedirectResponse
     */
    public function take(Request $request, $id): RedirectResponse
    {
        // Находим бронирование и книгу по ID
        $reservation = Reservation::findOrFail($id);
        $book = Book::findOrFail($reservation->book_id);

        // Проверяем, что книга уже была выдана пользователю
        if ($reservation->status !== 'completed') {
            return redirect()->back()->with('error', 'Невозможно вернуть книгу, она еще не была выдана.');
        }

        // Удаляем бронирование и обновляем статус книги на "доступна"
        $reservation->delete();
        $book->update(['status' => 'available']);

        // Перенаправляем назад с сообщением о возвращении книги
        return redirect()->back()->with('success', 'Книга возвращена в библиотеку.');
    }
}

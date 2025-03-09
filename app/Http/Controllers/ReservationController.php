<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Проверка, доступна ли книга для бронирования
        if ($book->status != 'available') {
            return back()->with('error', 'Книга не доступна для бронирования.');
        }

        // Создание нового бронирования
        Reservation::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'reserved_at' => now(),
            'expires_at' => now()->addMinutes(5), // срок действия бронирования (7 дней)
        ]);

        // Обновление статуса книги
        $book->update(['status' => 'reserved', 'reservation_expires_at' => now()->addMinutes(5)]);

        return redirect()->route('main', $book)->with('success', 'Книга успешно забронирована!');
    }
    public function cancel(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Ищем бронирование ТОЛЬКО текущего пользователя
        $reservation = Reservation::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservation) {
            return back()->with('success', 'Вы не резервировали эту книгу.');
        }

        // Удаляем бронирование и обновляем статус книги
        $reservation->delete();
        $book->update(['status' => 'available', 'reservation_expires_at' => null]);

        return back()->with('success', 'Бронирование снято.');
    }


}

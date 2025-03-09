<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Comment;
use App\Models\Reservation;
class MainController extends Controller
{
    public function index()
    {
        $books = Book::where('status', 'available')->get();

        return Inertia::render('Book', [
            'books' => $books,
        ]);
    }
    public function show($id)
    {
        $book = Book::findOrFail($id);

        $comments = $book->comments()
            ->with('user') // Загружаем всю модель пользователя
            ->latest()
            ->get();
        $canreserve = false;


        // Ищем бронирование ТОЛЬКО текущего пользователя
        $reservation = Reservation::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->first();

        if(Auth::user() && $reservation && $book->status == 'reserved') {
            $canreserve = true;
        }
        if(Auth::user() && $book->status == 'available') {
            $canreserve = true;
        }
        return Inertia::render('BookDetail', [
            'book' => $book,
            'comments' => $comments,
            'canreserve' => $canreserve
        ]);
    }


    public function store(Request $request)
    {


        $user = Auth::user();
        Comment::create([
            'user_id' => $user->id, // Берём ID авторизованного пользователя
            'book_id' => $request->book_id,
            'comment' => $request->content,
            'rating'  => 5,
        ]);

        return redirect()->back()->with('success', 'Комментарий добавлен');
    }

}

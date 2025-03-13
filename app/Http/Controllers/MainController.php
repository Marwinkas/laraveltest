<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Inertia\Inertia;

class MainController extends Controller
{
    /**
     * Отображает главную страницу с фильтрацией книг по запросу.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Получаем значение запроса для поиска
        $query = $request->input('query');

        // Получаем тип фильтрации, по умолчанию 'author'
        $type = $request->input('type', 'author');

        // Строим основной запрос для поиска доступных книг
        $booksQuery = Book::query()->where('status', 'available');

        // Если есть поисковый запрос, добавляем фильтрацию по типу
        if ($query) {
            // Проверяем, если тип фильтрации допустим ('author', 'genre', 'title')
            if (in_array($type, ['author', 'genre', 'title'])) {
                // Фильтруем по указанному полю (используем ILIKE для PostgreSQL)
                $booksQuery->where($type, 'ILIKE', "%{$query}%");
            } else {
                // Если тип фильтрации не указан или не подходит, фильтруем по всем трем полям
                $booksQuery->where(function ($q) use ($query) {
                    $q->where('author', 'ILIKE', "%{$query}%")
                        ->orWhere('genre', 'ILIKE', "%{$query}%")
                        ->orWhere('title', 'ILIKE', "%{$query}%");
                });
            }
        }

        // Получаем результаты запроса
        $books = $booksQuery->get();

        // Возвращаем данные в представление через Inertia
        return Inertia::render('Index', [
            'books'   => $books,           // Список найденных книг
            'filters' => $request->only('query', 'type') // Фильтры поиска (query и type)
        ]);
    }

}

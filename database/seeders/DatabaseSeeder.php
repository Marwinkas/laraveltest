<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Librarian',
                'email' => 'librarian@mail.ru',
                'password' => Hash::make('librarian1234'),
                'role' => 'librarian',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.ru',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@mail.ru',
                'password' => Hash::make('user1234'),
                'role' => 'client',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $books = [
            [
                'title' => '1984',
                'author' => 'Джордж Оруэлл',
                'link' => 'https://imo10.labirint.ru/books/863652/cover.jpg/242-0',
                'genre' => 'Дистопия',
                'publisher' => 'Secker & Warburg',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Убить пересмешника',
                'author' => 'Харпер Ли',
                'link' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/4774061/46b80991-7b6f-484b-b6dd-8cd2cd6ccbe9/220x330',
                'genre' => 'Художественная литература',
                'publisher' => 'J.B. Lippincott & Co.',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Великий Гэтсби',
                'author' => 'Фрэнсис Скотт Фицджеральд',
                'link' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/1946459/390dc48d-2025-4323-b225-cb0883ab8374/600x900',
                'genre' => 'Классика',
                'publisher' => 'Scribner',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Моби Дик',
                'author' => 'Герман Мелвилл',
                'link' => 'https://cdn.azbooka.ru/cv/w1100/3cbe0a3b-03fb-4872-ac70-e1e6ad106e09.jpg',
                'genre' => 'Приключения',
                'publisher' => 'Harper & Brothers',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Война и мир',
                'author' => 'Лев Толстой',
                'link' => 'https://www.litres.ru/pub/c/cover/69495367.jpg',
                'genre' => 'Исторический роман',
                'publisher' => 'The Russian Messenger',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Гордость и предубеждение',
                'author' => 'Джейн Остин',
                'link' => 'https://upload.wikimedia.org/wikipedia/ru/8/86/Pride_%26_Prejudice_2005.jpg',
                'genre' => 'Романтика',
                'publisher' => 'T. Egerton',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Над пропастью во ржи',
                'author' => 'Джером Д. Сэлинджер',
                'link' => 'https://cdn.eksmo.ru/v2/430000000000131563/COVER/cover1__w600.jpg',
                'genre' => 'Роман о взрослении',
                'publisher' => 'Little, Brown and Company',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Преступление и наказание',
                'author' => 'Фёдор Достоевский',
                'link' => 'https://ir.ozone.ru/s3/multimedia-t/c1000/6008637185.jpg',
                'genre' => 'Психологический роман',
                'publisher' => 'The Russian Messenger',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'О дивный новый мир',
                'author' => 'Олдос Хаксли',
                'link' => 'https://imo10.labirint.ru/books/589214/cover.jpg/242-0',
                'genre' => 'Дистопия',
                'publisher' => 'Chatto & Windus',
                'status' => 'available', // доступна
            ],
            [
                'title' => 'Хоббит, или Туда и обратно',
                'author' => 'Дж. Р. Р. Толкин',
                'link' => 'https://imo10.labirint.ru/books/417700/cover.jpg/242-0',
                'genre' => 'Фэнтези',
                'publisher' => 'George Allen & Unwin',
                'status' => 'available', // доступна
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}

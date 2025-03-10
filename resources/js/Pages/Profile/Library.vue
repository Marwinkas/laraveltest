<script setup lang="ts">
import { ref, defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { Head, useForm } from '@inertiajs/vue3';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Headers from '@/Layouts/Header.vue';

// Интерфейс для книги
interface Book {
  id: number;
  title: string;
  link: string;
  author: string;
  status: string;
  genre: string;
  publisher: string;
}

// Интерфейс для бронирования
interface Reservation {
    id: number;
    status: string;
    user: { name: string };   // Имя пользователя, который сделал бронирование
    book: { title: string };  // Название забронированной книги
}

// Определяем свойства, которые передаются в компонент
const props = defineProps<{  books: Book[],  reservations: Reservation[] }>();

// Функция для обработки действий с бронированием (выдача или возврат книги)
const handleReservationAction = (reservationId: number, currentStatus: string) => {
  const routeName = currentStatus === 'active'
    ? 'reservations.deliver'  // Если статус активен, то отправляем запрос на выдачу
    : 'reservations.take';     // Если статус не активен, то отправляем запрос на возврат

  router.post(route(routeName, reservationId), {
    data: { reservationId },
    preserveScroll: true,
  });
};

// Ссылка на значения инпутов для каждого бронирования (если нужно будет делать изменения)
const inputValues = ref<{ [key: number]: string }>({});

// Функция для удаления книги
const deleteUser = (bookID: number) => {
    if (confirm(`Удалить книгу с ID ${bookID}?`)) {
        router.delete(route('books.destroy'), {
            data: { id: bookID },
            preserveScroll: true,
        });
    }
};

// Форма для добавления новой книги
const form = useForm({
            title: '',
            author: '',
            link: '',
            genre: '',
            publisher: '',
            processing: false,
});

// Отправка формы для добавления книги
const submit = () => {
    form.post(route('books.store'), {
        onFinish: () => {
            form.reset(); // После отправки очищаем форму
        },
    });
};
</script>

<template>
    <!-- Заголовок страницы -->
    <Head title="Profile" />

    <!-- Компонент Header для отображения верхней части страницы -->
    <Headers/>

    <!-- Раздел для отображения списка книг и их удаления -->
    <section class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Удаление Книг</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Автор</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изображение</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Жанр</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Издатель</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действие</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="book in books" :key="book.id">
                    <td class="px-6 py-4 whitespace-nowrap">{{ book.title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ book.author }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <!-- Отображаем изображение книги -->
                        <img :src="book.link" alt="Book cover" class="w-16 h-16 object-cover"/>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ book.status }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ book.genre }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ book.publisher }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <!-- Кнопка для удаления книги -->
                        <button
                            @click="deleteUser(book.id)"
                            class="bg-red-500 text-white px-4 py-2 rounded"
                        >
                            Удалить
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Раздел для отображения бронирований и действий с ними -->
    <section class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Бронирования</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Книга</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действие</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="reservation in reservations" :key="reservation.id">
                    <td class="px-6 py-4 whitespace-nowrap">{{ reservation.user.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ reservation.book.title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ reservation.status }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <!-- Кнопка для действия с бронированием (выдача или возврат) -->
                        <button
                            @click="handleReservationAction(reservation.id, reservation.status)"
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            {{ reservation.status === 'active' ? 'Отдать книгу' : 'Взять книгу' }}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Раздел для добавления новой книги -->
    <section class="max-w-lg mx-auto mt-10 p-6 bg-white shadow rounded">
        <form @submit.prevent="submit">
            <!-- Поле для ввода названия книги -->
            <div>
                <InputLabel for="title" value="Название" />
                <TextInput
                    id="title"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.title"
                    required
                    autofocus
                    autocomplete="title"
                />
            </div>

            <!-- Поле для ввода автора книги -->
            <div class="mt-4">
                <InputLabel for="author" value="Автор" />
                <TextInput
                    id="author"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.author"
                    required
                    autocomplete="author"
                />
            </div>

            <!-- Поле для ввода ссылки на изображение книги -->
            <div class="mt-4">
                <InputLabel for="link" value="Ссылка на изображение" />
                <TextInput
                    id="link"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.link"
                    required
                    autocomplete="link"
                />
            </div>

            <!-- Поле для ввода жанра книги -->
            <div class="mt-4">
                <InputLabel for="genre" value="Жанр" />
                <TextInput
                    id="genre"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.genre"
                    required
                    autocomplete="genre"
                />
            </div>

            <!-- Поле для ввода издателя книги -->
            <div class="mt-4">
                <InputLabel for="publisher" value="Издатель" />
                <TextInput
                    id="publisher"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.publisher"
                    required
                    autocomplete="publisher"
                />
            </div>

            <!-- Кнопка для отправки формы и добавления книги -->
            <div class="mt-4 flex items-center justify-end">
                <button
                    class="mt-4 bg-red-500 text-white px-4 py-2 rounded w-full"
                    :disabled="form.processing"
                >
                    Добавить книгу
                </button>
            </div>
        </form>
    </section>
</template>

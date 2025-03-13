<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
// Импортируем компоненты, которые будут использоваться на странице профиля
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Header from "@/Layouts/Header.vue";

interface Book {
  id: number;
  title: string;
  link: string;
  author: string;
  status: string;
  genre: string;
  publisher: string;
}
// Определяем типы для входящих свойств (props) компонента
defineProps<{
    mustVerifyEmail?: boolean; // Нужно ли подтверждать email
    status?: string;           // Статус сообщения (например, сообщение об успешном изменении)
    userNames: string[];       // Список имен пользователей
    emails: string[];          // Список email пользователей
    passwords: string[];       // Список паролей пользователей
    users: { id: number; name: string; email: string }[]; // Список пользователей с их данными
    books: Book[],
}>();

const cancelReservation = (id : number) => {
  // Отправка запроса на сервер для снятия брони
  router.post('/cancel-reservation', { book_id: id }, {});
};
</script>

<template>
    <!-- Устанавливаем заголовок страницы -->
    <Head title="Profile" />

    <!-- Компонент Header для отображения верхней части страницы -->
    <Header />

    <!-- Основной блок для профиля пользователя -->
    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold mb-4">Забронированные Книги</h2>
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
                            @click="cancelReservation(book.id)"
                            class="bg-red-500 text-white px-4 py-2 rounded"
                        >
                            Убрать бронь
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
            <!-- Форма для обновления информации профиля -->
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <!-- Передаем необходимые props в форму обновления информации профиля -->
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
            </div>

            <!-- Форма для обновления пароля -->
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <!-- Передаем форму для обновления пароля -->
                <UpdatePasswordForm class="max-w-xl" />
            </div>
        </div>
    </div>
</template>

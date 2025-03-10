<script lang="ts" setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

import Header from '@/Layouts/Header.vue';

// Типы данных для книги, комментария и пользователя
interface Book {
  id: number;
  title: string;
  link: string;
  author: string;
  status: string;
  genre: string;
  publisher: string;
}
interface user{
    name: string;
}
interface Comment {
  id: number;
  comment: string;
  user: user;
}

// Определение свойств, которые передаются в компонент
const props = defineProps<{ book: Book, comments: Comment[], canreserve: boolean }>();

// Переменные для обработки комментариев и состояния процесса
const newComment = ref('');
const isProcessing = ref(false);

// Функция отправки нового комментария
const submitComment = () => {
  // Проверка, что комментарий не пустой
  if (!newComment.value.trim()) return;

  // Отправка запроса на сервер для добавления комментария
  router.post('/comments', { book_id: props.book.id, content: newComment.value }, {
    onSuccess: () => {
      // Очистка поля ввода комментария после успешной отправки
      newComment.value = '';
    }
  });
};

// Функция для бронирования книги
const reserveBook = () => {
  // Предотвращение отправки запроса, если процесс уже в стадии выполнения
  if (isProcessing.value) return;
  isProcessing.value = true;

  // Отправка запроса на сервер для бронирования книги
  router.post('/reserve', { book_id: props.book.id }, {
    onSuccess: () => {
      isProcessing.value = false;
      alert('Книга успешно забронирована!');
    },
    onError: () => {
      isProcessing.value = false;
      alert('Ошибка при бронировании книги.');
    }
  });
};

// Функция для снятия брони с книги
const cancelReservation = () => {
  // Предотвращение отправки запроса, если процесс уже в стадии выполнения
  if (isProcessing.value) return;
  isProcessing.value = true;

  // Отправка запроса на сервер для снятия брони
  router.post('/cancel-reservation', { book_id: props.book.id }, {
    onSuccess: () => {
      isProcessing.value = false;
      alert('Бронирование снято!');
    },
    onError: () => {
      isProcessing.value = false;
      alert('Ошибка при снятии брони.');
    }
  });
};
</script>

<template>
  <Header/>
  <div class="p-4">
    <!-- Заголовок книги и её описание -->
    <h2 class="text-xl font-bold">{{ book.title }}</h2>
    <img :src="book.link" alt="Book cover"/>
    <p>Автор: {{ book.author }}</p>

    <!-- Статус книги (доступна, забронирована или недоступна) -->
    <p v-if=" book.status == 'available'">Статус: Доступна для бронирования</p>
    <p v-else-if=" book.status == 'reserved'">Статус: Забронирован</p>
    <p v-else>Статус: Нет в наличии</p>

    <p>Жанр: {{ book.genre }}</p>
    <p>Издатель: {{ book.publisher }}</p>

    <!-- Раздел для отображения комментариев -->
    <div class="mt-4">
      <h3 class="font-semibold">Комментарии</h3>
      <ul>
        <li v-for="comment in comments" :key="comment.id" class="border-b py-2">
          <strong>{{ comment.user?.name ?? 'Аноним' }}:</strong> <p class="break-words">{{ comment.comment }}</p>
        </li>
      </ul>
    </div>

    <!-- Форма для отправки нового комментария -->
    <form @submit.prevent="submitComment" class="mt-4">
      <textarea v-model="newComment" class="w-full p-2 border rounded" placeholder="Оставьте комментарий"></textarea>
      <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-black rounded">Отправить</button>
    </form>

    <!-- Кнопки для бронирования или снятия брони -->
    <div class="mt-4" v-if="canreserve === true">
      <!-- Кнопка бронирования книги, если она доступна -->
      <button
        v-if="book.status === 'available'"
        :disabled="isProcessing"
        @click="reserveBook"
        class="px-4 py-2 bg-green-500 text-white rounded"
      >
        Забронировать
      </button>

      <!-- Кнопка снятия брони, если книга забронирована -->
      <button
        v-else-if="book.status === 'reserved'"
        :disabled="isProcessing"
        @click="cancelReservation"
        class="px-4 py-2 bg-red-500 text-white rounded"
      >
        Снять бронь
      </button>

      <!-- Сообщение, если книга недоступна для бронирования -->
      <p v-if="book.status !== 'available' && book.status !== 'reserved'" class="text-red-500 mt-2">
        Книга недоступна
      </p>
    </div>
  </div>
</template>

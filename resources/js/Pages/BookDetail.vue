<script lang="ts" setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

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

const props = defineProps<{ book: Book, comments: Comment[],canreserve: boolean }>();

const newComment = ref('');
const isProcessing = ref(false);

const submitComment = () => {
  if (!newComment.value.trim()) return;

  router.post('/comments', { book_id: props.book.id, content: newComment.value }, {
    onSuccess: () => {
      newComment.value = '';
    }
  });
};

const reserveBook = () => {
  if (isProcessing.value) return;
  isProcessing.value = true;

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

const cancelReservation = () => {
  if (isProcessing.value) return;
  isProcessing.value = true;

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
  <div class="p-4">
    <h2 class="text-xl font-bold">{{ book.title }}</h2>
    <img :src="book.link"/>
    <p>Автор: {{ book.author }}</p>
    <p v-if=" book.status == 'available'">Статус: Доступна для бронирования</p>
    <p v-else-if=" book.status == 'reserved'">Статус: Забронирован</p>
    <p v-else=" book.status == 'reserved'">Статус: Нет в наличии</p>
    <p>Жанр: {{ book.genre }}</p>
    <p>Издатель: {{ book.publisher }}</p>
    <div class="mt-4">
      <h3 class="font-semibold">Комментарии</h3>
      <ul>
        <li v-for="comment in comments" :key="comment.id" class="border-b py-2">
          <strong>{{ comment.user?.name ?? 'Аноним' }}:</strong> <p class="break-words">{{ comment.comment }}</p>
        </li>
      </ul>
    </div>

    <form @submit.prevent="submitComment" class="mt-4">
      <textarea v-model="newComment" class="w-full p-2 border rounded" placeholder="Оставьте комментарий"></textarea>
      <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-black rounded">Отправить</button>
    </form>

    <div class="mt-4" v-if="canreserve === true">
      <button
        v-if="book.status === 'available'"
        :disabled="isProcessing"
        @click="reserveBook"
        class="px-4 py-2 bg-green-500 text-white rounded"
      >
        Забронировать
      </button>

      <button
        v-else-if="book.status === 'reserved'"
        :disabled="isProcessing"
        @click="cancelReservation"
        class="px-4 py-2 bg-red-500 text-white rounded"
      >
        Снять бронь
      </button>

      <p v-if="book.status !== 'available' && book.status !== 'reserved'" class="text-red-500 mt-2">
        Книга недоступна
      </p>
    </div>
  </div>
</template>

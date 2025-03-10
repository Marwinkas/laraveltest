<script lang="ts" setup>
import { ref, nextTick, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

import Header from '@/Layouts/Header.vue';

// Тип данных для книги
interface Book {
  id: number;
  link: string;
  title: string;
  author: string;
}

// Получение списка книг как свойства компонента
const props = defineProps<{ books: Book[] }>();

// Хранение ширины изображений книг
const imageWidths = ref<Record<number, number>>({});

// Хранение ссылок на изображения
const imageRefs = ref<HTMLImageElement[]>([]);

// Функция обновления ширины изображения книги
const updateWidth = (bookId: number) => {
  nextTick(() => {
    // Находим изображение по id
    const img = imageRefs.value.find((img) => img.dataset.id === String(bookId));
    if (img) {
      // Обновляем ширину для соответствующего id книги
      imageWidths.value[bookId] = img.clientWidth;
    }
  });
};

// При монтировании компонента проверяем, были ли изображения загружены
onMounted(() => {
  nextTick(() => {
    // Пробегаем по всем изображениям и обновляем их ширину
    imageRefs.value.forEach((img) => {
      if (img.complete) {
        updateWidth(Number(img.dataset.id));
      }
    });
  });
});
</script>

<template>
  <!-- Заголовок компонента -->
  <Header/>

  <h1 class="text-3xl">Сказали на дизайн не смотрят</h1>
  <h1 class="text-3xl">Для очищение брони на линукс при помощи cron, а через консоль: php artisan reservations:cleanup</h1>
  <h1 class="text-3xl">Для админа /admin и роль admin</h1>
  <h1 class="text-3xl">Для библиотекаря /library и роль librarian</h1>

  <!-- Список книг с оберткой для изображений и текста -->
  <ul class="flex gap-4 ml-16 mt-16 mr-16 flex-wrap">
    <!-- Перебор списка книг и отображение каждой -->
    <li v-for="book in props.books" :key="book.id" class="flex flex-col items-center">
      <Link :href="`/book/${book.id}`">
        <!-- Контейнер для изображения и текста -->
        <div ref="imageContainers" class="flex flex-col items-center">

          <!-- Изображение книги с динамическим источником и шириной -->
          <img
            :src="book.link"
            class="rounded-xl h-[220px] w-auto"
            alt="Book Image"
            ref="imageRefs"
            @load="updateWidth(book.id)"
            :data-id="book.id" 
          />

          <!-- Заголовок книги (название) с шириной, привязанной к ширине изображения -->
          <h2 class="font-bold text-sm truncate" :style="{ width: imageWidths[book.id] + 'px' }">
            {{ book.title }}
          </h2>

          <!-- Автор книги с шириной, привязанной к ширине изображения -->
          <p class="text-gray-500 text-sm truncate" :style="{ width: imageWidths[book.id] + 'px' }">
            {{ book.author }}
          </p>
        </div>
      </Link>
    </li>
  </ul>
</template>

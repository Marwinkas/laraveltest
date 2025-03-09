<script lang="ts" setup>
import { ref, nextTick, onMounted } from 'vue';
import Header from './Header.vue';
import { Link } from '@inertiajs/vue3';
interface Book {
  id: number;
  link: string;
  title: string;
  author: string;
}

const props = defineProps<{ books: Book[] }>();

// Хранение ширины изображений
const imageWidths = ref<Record<number, number>>({});
const imageRefs = ref<HTMLImageElement[]>([]);

// Функция обновления ширины текста после загрузки изображения
const updateWidth = (bookId: number) => {
  nextTick(() => {
    const img = imageRefs.value.find((img) => img.dataset.id === String(bookId));
    if (img) {
      imageWidths.value[bookId] = img.clientWidth; // Устанавливаем ширину изображения
    }
  });
};

// Обновляем ширину после загрузки изображений
onMounted(() => {
  nextTick(() => {
    imageRefs.value.forEach((img) => {
      if (img.complete) {
        updateWidth(Number(img.dataset.id));
      }
    });
  });
});
</script>

<template>
    <Header/>
    <ul class="flex gap-4 ml-16 mt-16 mr-16 flex-wrap">
      <li
        v-for="book in props.books"
        :key="book.id"
        class="flex flex-col items-center "
      >
        <!-- Контейнер, который получает ширину изображения -->
        <Link :href="`/book/${book.id}`">
            <div ref="imageContainers" class="flex flex-col items-center ">
          <img
            :src="book.link"
            class="rounded-xl h-[220px] w-auto"
            alt="Book Image"
            ref="imageRefs"
            @load="updateWidth(book.id)"
            :data-id="book.id"
          />
          <!-- Текстовые блоки, ширина которых равна ширине изображения -->
          <h2 class="font-bold text-sm truncate" :style="{ width: imageWidths[book.id] + 'px' }">
            {{ book.title }}
          </h2>
          <p class="text-gray-500 text-sm truncate" :style="{ width: imageWidths[book.id] + 'px' }">
            {{ book.author }}
          </p>
        </div>
        </Link>
      </li>
    </ul>
  </template>

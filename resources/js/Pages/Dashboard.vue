<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// props без типизации, ожидаем массив задач
const props = defineProps({
  tasks: Array, // tasks - это массив объектов
});

const searchQuery = ref(''); // Поиск будет работать с обычной строкой

const search = () => {
    if (searchQuery.value.trim()) {
        console.log('Поиск:', searchQuery.value);
        // Тут можно выполнить API-запрос или фильтрацию задач
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="search-container">
                        <input v-model="searchQuery" type="text" placeholder="Введите запрос..."
                            @keyup.enter="search" />
                        <button @click="search">🔍</button>
                    </div>
                    <div class="admin-panel">
                        <h1>Admin Panel</h1>
                        <ul>
                            <!-- Рендерим список задач с помощью v-for -->
                            <li v-for="task in props.tasks" :key="task.id">
                                <div>{{ task.name }}</div>
                                <div>{{ task.description }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

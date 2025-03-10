<script setup lang="ts">
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import LogoLink from '@/Components/LogoLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

// Переменные для поиска
const searchQuery = ref('');
const searchCategory = ref('author');

// Функция обработки поиска
const performSearch = () => {
    Inertia.get(
        route('main'),
        { query: searchQuery.value, category: searchCategory.value },
        { preserveState: true, replace: true }
    );
};
</script>

<template>
    <header class="flex justify-between items-center p-4 bg-gray-800 text-white">
        <!-- Логотип и название -->
        <div class="text-xl font-bold">
            <LogoLink :href="route('main')" class="text-white">Библиотека</LogoLink>
        </div>

        <!-- Поле поиска книг -->
        <div class="flex items-center gap-2 w-1/2">
            <select v-model="searchCategory" class="p-2 rounded text-black w-32">
                <option value="author">Автор</option>
                <option value="genre">Жанр</option>
                <option value="title">Название</option>
            </select>
            <input type="text" v-model="searchQuery" placeholder="Поиск книг..." class="p-2 w-full rounded text-black" />
            <button @click="performSearch" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                Найти
            </button>
        </div>

        <!-- Панель пользователя -->
        <div v-if="$page.props.auth.user" class="hidden sm:flex items-center">
            <Dropdown align="right" width="48">
                <template #trigger>
                    <button class="flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-300">
                        {{ $page.props.auth.user.name }}
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </template>
                <template #content>
                    <DropdownLink :href="route('profile.edit')">Профиль</DropdownLink>
                    <DropdownLink :href="route('logout')" method="post" as="button">Выйти</DropdownLink>
                </template>
            </Dropdown>
        </div>

        <!-- Кнопка авторизации и регистрации аккаунта -->
        <div v-else class="sm:flex items-center">
            <NavLink :href="route('login')" class="text-white mr-4">Войти</NavLink>
            <NavLink :href="route('register')" class="text-white">Регистрация</NavLink>
        </div>
    </header>
</template>

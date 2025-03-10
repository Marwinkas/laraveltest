<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

// Импортируем компоненты, которые будут использоваться на странице профиля
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Header from "@/Layouts/Header.vue";

// Определяем типы для входящих свойств (props) компонента
defineProps<{
    mustVerifyEmail?: boolean; // Нужно ли подтверждать email
    status?: string;           // Статус сообщения (например, сообщение об успешном изменении)
    userNames: string[];       // Список имен пользователей
    emails: string[];          // Список email пользователей
    passwords: string[];       // Список паролей пользователей
    users: { id: number; name: string; email: string }[]; // Список пользователей с их данными
}>();
</script>

<template>
    <!-- Устанавливаем заголовок страницы -->
    <Head title="Profile" />

    <!-- Компонент Header для отображения верхней части страницы -->
    <Header />

    <!-- Основной блок для профиля пользователя -->
    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

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

            <!-- Форма для удаления пользователя -->
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <!-- Передаем форму для удаления пользователя -->
                <DeleteUserForm class="max-w-xl" />
            </div>
        </div>
    </div>
</template>

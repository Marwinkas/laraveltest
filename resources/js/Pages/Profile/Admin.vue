<script setup lang="ts">
import { ref, defineProps } from 'vue';
import { router, Head, useForm } from '@inertiajs/vue3';

import Header from '@/Layouts/Header.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

// Определение входных свойств
const props = defineProps<{ users: { id: number; name: string; email: string }[] }>();

// Объект для хранения значений новых паролей пользователей
const inputValues = ref<{ [key: number]: string }>({});

// Функция для удаления пользователя
const handleDeleteUser = (userId: number) => {
    if (confirm(`Удалить пользователя с ID ${userId}?`)) {
        router.delete(route('admin.destroy'), {
            data: { id: userId },
            preserveScroll: true,
        });
    }
};

// Функция для смены пароля пользователя
const handleChangePassword = (userId: number) => {
    if (inputValues.value[userId] && confirm(`Поменять пароль на ${inputValues.value[userId]}?`)) {
        router.put(route('admin.password.update'), {
            password: inputValues.value[userId],
            id: userId,
            preserveScroll: true,
        });
    }
};

// Форма для добавления нового пользователя
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Функция для отправки формы создания нового пользователя
const handleSubmit = () => {
    form.post(route('admin.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Управление пользователями" />
    <Header />

    <!-- Таблица управления пользователями -->
    <section class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Управление пользователями</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Новый пароль</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in users" :key="user.id">
                    <td class="px-4 py-2 whitespace-nowrap text-xs">{{ user.name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs">{{ user.email }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <input v-model="inputValues[user.id]" type="text" placeholder="Введите новый пароль"
                            class="p-1 border rounded w-full text-xs" />
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap space-y-1">
                        <button @click="handleChangePassword(user.id)"
                            class="bg-blue-500 text-white px-2 py-1 rounded text-xs w-auto">
                            Поменять пароль
                        </button>
                        <button @click="handleDeleteUser(user.id)"
                            class="bg-red-500 text-white px-2 py-1 rounded text-xs w-auto">
                            Удалить
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Форма для создания нового пользователя -->
    <section class="max-w-lg mx-auto mt-10 p-6 bg-white shadow rounded">
        <form @submit.prevent="handleSubmit">
            <div>
                <InputLabel for="name" value="Имя" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Пароль" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Подтверждение пароля" />
                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <button class="mt-4 bg-green-500 text-white px-4 py-2 rounded w-full" :disabled="form.processing">
                    Создать аккаунт
                </button>
            </div>
        </form>
    </section>
</template>

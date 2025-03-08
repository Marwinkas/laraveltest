<script setup lang="ts">
import { ref, defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{ users: { id: number; name: string; email: string }[] }>();

// Array to store input values for each user
const inputValues = ref<{ [key: number]: string }>({});

const deleteUser = (userId: number) => {
    if (confirm(`Удалить пользователя с ID ${userId}?`)) {
        router.delete(route('profile.destroy'), {
            data: { id: userId },
            preserveScroll: true,
        });
    }
};
const changepassword = (userId: number) => {
    if (inputValues.value[userId] != null && confirm(`"Поменять пароль, на ${inputValues.value[userId]}"`)) {
        router.put(route('password.update2'), {
            password: inputValues.value[userId],
            id: userId,
            preserveScroll: true,
        });
    }

};
</script>

<template>
    <section class="max-w-lg mx-auto mt-10 p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Удаление пользователя</h2>

        <label for="user" class="block mb-2">Выберите пользователя:</label>
        <ul>
            <li v-for="user in users" :key="user.id">
                <div class="mb-2">
                    {{ user.name }}
                    {{ user.email }}
                    <!-- Input field to store user's value in the inputValues array -->

                </div>
                <input
                        v-model="inputValues[user.id]"
                        type="text"
                        :placeholder="`Введите текст для ${user.name}`"
                        class="mt-2 p-2 border rounded w-full"
                    />

                <button
                    @click="changepassword(user.id)"
                    class="mt-4 bg-red-500 text-white px-4 py-2 rounded w-full"
                >
                    Поменять пароль
                </button>
                <button
                    @click="deleteUser(user.id)"
                    class="mt-4 bg-red-500 text-white px-4 py-2 rounded w-full"
                >
                    Удалить
                </button>
                <div>
                     -------------------------------------------------------------
                </div>
            </li>
        </ul>
    </section>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateUserPassword from './Partials/UpdateUserPassword.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'; // Импортируем ref из Vue
defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
    userNames: string[];
    emails: string[];
    passwords: string[];
    users: { id: number; name: string ; email: string}[];
}>();

// Локальные данные для работы с кнопкой
const clickCount = ref(0); // Счётчик кликов
const isDisabled = ref(false); // Состояние кнопки

// Метод для обработки клика по кнопке
const handleClick = () => {
  clickCount.value++;
};

// Метод для переключения состояния кнопки
const toggleButtonState = () => {
  isDisabled.value = !isDisabled.value;
};
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateUserPassword
                        :users="users"
                        class="max-w-xl"
                    />
                </div>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
                <div class="admin-panel">
                        <h1>Admin Panel</h1>
                        <ul>
                            <!-- Рендеринг списка пользователей -->
                            <li v-for="(name, index) in userNames" :key="index">
                                <input
                                type="text"
                                :id="'name-' + index"
                                v-model="userNames[index]"
                                placeholder="Введите имя пользователя"
                                />
                                <input
                                type="text"
                                :id="'email-' + index"
                                v-model="emails[index]"
                                placeholder="Введите имя пользователя"
                                />
                                <div>
                                    <button @click="handleClick">Нажми меня</button>
                                    <p>Вы нажали {{ clickCount }} раз.</p>
                                </div>
                            </li>

                        </ul>
                    </div>
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

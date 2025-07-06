<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login - ストック管理" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">ストック管理</h1>
                <p class="text-gray-600 text-sm">Easily manage your inventory</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                <!-- Status Message -->
                <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-700 text-center">{{ status }}</p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <InputLabel for="email" value="Email Address" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="email@example.com"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <InputLabel for="password" value="Password" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox
                                v-model:checked="form.remember"
                                name="remember"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-500 font-medium transition-colors duration-200"
                        >
                            Forgot your password?
                        </Link>
                    </div>

                    <!-- Login Button -->
                    <PrimaryButton
                        class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 rounded-xl font-medium text-white transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing" class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Log in to Dashboard
                        </span>
                        <span v-else class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </PrimaryButton>
                </form>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        &copy; 2025 ストック管理システム. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

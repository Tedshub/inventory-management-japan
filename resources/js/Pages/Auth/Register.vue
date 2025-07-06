<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onSuccess: () => {
            window.location.href = '/barang'
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register - ストック管理" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-8">
        <div class="w-full max-w-md">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Account</h1>
                <p class="text-gray-600 text-sm">Join to manage your inventory easily</p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                <!-- Register Form -->
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Name Field -->
                    <div class="space-y-2">
                        <InputLabel for="name" value="Full Name" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="Your name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <InputLabel for="email" value="Email Address" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="email@example.com"
                                required
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="At least 8 characters"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <InputLabel for="password_confirmation" value="Confirm Password" class="text-sm font-medium text-gray-700" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="pl-10 block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                placeholder="Repeat password"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.password_confirmation" />
                    </div>

                    <!-- Terms -->
                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="space-y-2">
                        <label class="flex items-start space-x-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <Checkbox
                                id="terms"
                                v-model:checked="form.terms"
                                name="terms"
                                required
                                class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            />
                            <div class="text-sm text-gray-700 leading-relaxed">
                                I agree to the
                                <a target="_blank" :href="route('terms.show')" class="text-blue-600 hover:text-blue-500 font-medium underline">
                                    Terms of Service
                                </a>
                                and
                                <a target="_blank" :href="route('policy.show')" class="text-blue-600 hover:text-blue-500 font-medium underline">
                                    Privacy Policy
                                </a>
                            </div>
                        </label>
                        <InputError class="mt-1" :message="form.errors.terms" />
                    </div>

                    <!-- Submit -->
                    <PrimaryButton
                        class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 rounded-xl font-medium text-white transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        :disabled="form.processing"
                    >
                        <span v-if="!form.processing" class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Create Account
                        </span>
                        <span v-else class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            Creating Account...
                        </span>
                    </PrimaryButton>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <Link
                                :href="route('login')"
                                class="text-blue-600 hover:text-blue-500 font-medium transition-colors duration-200"
                            >
                                Log in here
                            </Link>
                        </p>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        &copy; 2025 ストック管理 System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

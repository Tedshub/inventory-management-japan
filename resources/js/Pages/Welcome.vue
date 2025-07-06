<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Stock Management - ストックを簡単に管理" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Navigation -->
        <nav class="relative z-10 px-4 sm:px-6 lg:px-8 pt-8">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">ストック管理</span>
                </div>

                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('login')"
                        class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200"
                    >
                        Login
                    </Link>
                    <Link
                        :href="route('register')"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
                <div class="text-center">
                    <!-- Main Icon -->
                    <div class="mx-auto h-32 w-32 bg-blue-600 rounded-3xl flex items-center justify-center mb-8 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                        <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>

                    <!-- Headline -->
                    <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 mb-6">
                        <span class="block">在庫を管理</span>
                        <span class="block text-blue-600">簡単に操作</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl lg:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                        パワーフルな在庫管理システムで、ビジネスを最適化しましょう。在庫追跡、供給元管理、パフォーマンス分析をこの一つのプラットフォームで完結します。
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <Link
                            :href="route('register')"
                            class="group relative px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl flex items-center space-x-3"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>登録する</span>
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                        </Link>

                        <Link
                            :href="route('login')"
                            class="px-8 py-4 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-200 flex items-center space-x-3"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Login to Dashboard</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Background Decorations -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
                <div class="absolute top-20 left-10 w-32 h-32 bg-blue-200 rounded-full opacity-20 animate-pulse"></div>
                <div class="absolute top-40 right-20 w-24 h-24 bg-purple-200 rounded-full opacity-20 animate-pulse delay-1000"></div>
                <div class="absolute bottom-40 left-20 w-20 h-20 bg-green-200 rounded-full opacity-20 animate-pulse delay-2000"></div>
                <div class="absolute bottom-20 right-40 w-28 h-28 bg-orange-200 rounded-full opacity-20 animate-pulse delay-500"></div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900">ストック管理</span>
                    </div>

                    <div class="flex items-center space-x-6 text-sm text-gray-600">
                        <a href="#" class="hover:text-blue-600 transition-colors duration-200">プライバシーポリシー</a>
                        <a href="#" class="hover:text-blue-600 transition-colors duration-200">利用規約</a>
                        <a href="#" class="hover:text-blue-600 transition-colors duration-200">サポート</a>
                    </div>

                    <p class="text-xs text-gray-500">
                        &copy; 2025 ストック管理システム. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

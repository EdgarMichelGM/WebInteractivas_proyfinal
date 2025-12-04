<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-brand-50/60">
        <!-- Header -->
        <nav class="border-b border-orange-100 bg-white/80 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Left: Logo + title + nav -->
                    <div class="flex items-center gap-3">
                        <Link href="/" class="flex items-center gap-3">
                            <img
                                src="/images/icon.png"
                                alt="Logo"
                                class="h-20 w-20 object-contain"
                            />
                            <div class="flex flex-col leading-tight">
                                <span class="text-sm font-semibold text-brand-700">
                                    Mr Aprende
                                </span>
                                <span class="text-xs text-brand-400">
                                    Plataforma de tutorías académicas
                                </span>
                            </div>
                        </Link>

                        <!-- Main nav -->
                        <div class="hidden md:flex md:items-center md:space-x-4 ml-6">
                            <Link
                                :href="route('dashboard')"
                                class="text-sm font-medium text-brand-700/80 hover:text-brand-900"
                            >
                                Dashboard
                            </Link>

                            <Link
                                :href="route('courses.index')"
                                class="text-sm font-medium text-brand-700/80 hover:text-brand-900"
                            >
                                Cursos
                            </Link>

                            <Link
                                :href="route('tutorias.index')"
                                class="text-sm font-medium text-brand-700/80 hover:text-brand-900"
                            >
                                Mis tutorías
                            </Link>

                            <Link
                                :href="route('grades.index')"
                                class="text-sm font-medium text-brand-700/80 hover:text-brand-900"
                            >
                                Calificaciones
                            </Link>

                            <Link
                                :href="route('agenda.index')"
                                class="text-sm font-medium text-brand-700/80 hover:text-brand-900"
                            >
                                Agenda
                            </Link>
                        </div>
                    </div>

                    <!-- Acciones / avatar -->
                    <div class="flex items-center gap-3">
                        <button class="hidden sm:inline-flex btn-outline">
                            Ser tutor
                        </button>

                        <div
                            class="flex items-center gap-2 rounded-full bg-brand-100 px-3 py-1"
                        >
                            <div
                                class="h-7 w-7 rounded-full bg-brand-500 text-xs font-semibold text-white flex items-center justify-center"
                            >
                                {{ $page.props.auth.user.name[0] }}
                            </div>
                            <span
                                class="hidden text-xs font-medium text-brand-800 sm:inline"
                            >
                                {{ $page.props.auth.user.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page content -->
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>

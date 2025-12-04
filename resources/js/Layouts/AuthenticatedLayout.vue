<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

// helpers
const isCurrent = (name) => {
    try {
        return route().current(name);
    } catch {
        return false;
    }
};

const adminNav = [
    { label: 'Dashboard', name: 'dashboard' },
    { label: 'Materias',  name: 'materias.index' },
    { label: 'Mis tutorías', name: 'tutorias.index' },
    { label: 'Usuarios',  name: 'users.index' },
];

const alumnoNav = [
    { label: 'Explorar cursos', name: 'courses.index' },
    { label: 'Mis tutorías',    name: 'tutorias.index' },
    { label: 'Calificaciones',  name: 'grades.index' },
    { label: 'Agenda',          name: 'agenda.index' },
];

const navItems = computed(() => {
    if (!user.value) return [];
    if (user.value.role === 'alumno') {
        return alumnoNav;
    }
    return adminNav;
});

const logoutForm = useForm({});
const logout = () => {
    logoutForm.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen" style="background-color:#F3E9DD;">
        <nav class="border-b border-orange-100 bg-white/80 backdrop-blur shadow-md">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <Link
                            :href="route('dashboard')"
                            class="flex items-center gap-2 select-none"
                        >
                            <img
                                src="/images/icon.png"
                                alt="Mr Aprende"
                                class="h-9 w-9 object-contain"
                            />
                            <div class="flex flex-col leading-tight">
                                <span class="text-sm font-semibold text-brand-900 tracking-tight">
                                    Mr Aprende
                                </span>
                                <span class="text-[11px] text-brand-600">
                                    Plataforma de tutorías académicas
                                </span>
                            </div>
                        </Link>

                        <!-- Nav desktop -->
                        <div class="hidden md:flex md:items-center md:space-x-4 ml-4">
                            <Link
                                v-for="item in navItems"
                                :key="item.name"
                                :href="route(item.name)"
                                class="relative text-sm font-medium px-1 pb-0.5 transition-colors"
                                :class="isCurrent(item.name)
                                    ? 'text-brand-900'
                                    : 'text-brand-900 hover:text-brand-900'"
                            >
                                {{ item.label }}
                                <span
                                    v-if="isCurrent(item.name)"
                                    class="absolute left-0 right-0 -bottom-1 h-0.5 rounded-full bg-brand-700"
                                />
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-full bg-brand-100/80 px-3 py-1 hover:bg-brand-200 transition cursor-pointer"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                            >
                                <div class="h-7 w-7 rounded-full bg-brand-500 text-xs font-semibold text-white flex items-center justify-center uppercase">
                                    {{ user?.name ? user.name[0] : 'U' }}
                                </div>

                                <div class="hidden sm:flex flex-col text-left">
                                    <span class="text-xs font-medium text-brand-800 whitespace-nowrap">
                                        {{ user?.name }}
                                    </span>
                                    <span class="text-[10px] text-brand-600 capitalize">
                                        {{ user?.role }}
                                    </span>
                                </div>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-700"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                v-if="showingNavigationDropdown"
                                class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-orange-100 py-2 z-50"
                            >
                                <div class="px-4 py-2 border-b border-orange-100">
                                    <p class="text-sm font-medium text-brand-800">{{ user?.name }}</p>
                                    <p class="text-[11px] text-brand-600 capitalize">{{ user?.role }}</p>
                                </div>

                                <Link
                                    :href="route('profile.edit')"
                                    class="block px-4 py-2 text-sm text-brand-800 hover:bg-brand-50 transition"
                                >
                                    Mi perfil
                                </Link>

                                <button
                                    type="button"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                                    @click.prevent="logout"
                                    :disabled="logoutForm.processing"
                                >
                                    Cerrar sesión
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>

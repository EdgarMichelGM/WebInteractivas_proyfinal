<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';


const notes = [
    {
        id: 1,
        title: 'Bienvenido a Mr Aprende',
        body: 'Desde este panel puedes gestionar usuarios, materias y tus tutorías.',
        tag: 'General',
    },
    {
        id: 2,
        title: 'Asigna asesores a materias',
        body: 'Verifica que cada materia tenga un asesor asignado para que los alumnos sepan a quién acudir.',
        tag: 'Materias',
    },
    {
        id: 3,
        title: 'Organiza tus tutorías',
        body: 'En la sección "Mis tutorías" podrás revisar las sesiones activas y su organización.',
        tag: 'Tutorías',
    },
    {
        id: 4,
        title: 'Seguimiento académico',
        body: 'Utiliza Calificaciones y Agenda para llevar el control de avances y próximos eventos.',
        tag: 'Seguimiento',
    },
];

const currentIndex = ref(0);

const currentNote = computed(() => notes[currentIndex.value]);

const nextNote = () => {
    currentIndex.value = (currentIndex.value + 1) % notes.length;
};

const prevNote = () => {
    currentIndex.value = (currentIndex.value - 1 + notes.length) % notes.length;
};

const goToNote = (index) => {
    currentIndex.value = index;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Encabezado -->
            <header class="flex flex-col gap-2">
                <span class="badge-soft w-fit">
                    Bienvenido a Mr Aprende
                </span>
                <h1 class="text-2xl font-semibold text-brand-900">
                    Panel principal
                </h1>
                <p class="text-sm text-brand-700/80 max-w-xl">
                    Administra usuarios, materias, tutorías, calificaciones y agenda desde un solo lugar.
                </p>
            </header>


            <section class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)] items-stretch">
                <div class="card-soft p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-brand-500 font-semibold">
                                Notas rápidas
                            </p>
                            <h2 class="text-sm font-semibold text-brand-900">
                                {{ currentNote.title }}
                            </h2>
                        </div>

                        <span class="badge-soft text-[11px]">
                            {{ currentNote.tag }}
                        </span>
                    </div>

                    <p class="text-sm text-brand-700/90 mb-4">
                        {{ currentNote.body }}
                    </p>

                    <div class="flex items-center justify-between mt-auto pt-2">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="h-8 w-8 rounded-full border border-orange-200 flex items-center justify-center text-xs text-brand-700 hover:bg-brand-50"
                                @click="prevNote"
                            >
                                ‹
                            </button>
                            <button
                                type="button"
                                class="h-8 w-8 rounded-full border border-orange-200 flex items-center justify-center text-xs text-brand-700 hover:bg-brand-50"
                                @click="nextNote"
                            >
                                ›
                            </button>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button
                                v-for="(note, index) in notes"
                                :key="note.id"
                                type="button"
                                class="h-2.5 rounded-full transition-all"
                                :class="index === currentIndex
                                    ? 'bg-brand-500 w-4'
                                    : 'bg-brand-200 hover:bg-brand-300 w-2.5'"
                                @click="goToNote(index)"
                            />
                        </div>
                    </div>
                </div>
                <div class="space-y-3">

                    <div class="card-soft p-4">
                        <h3 class="text-sm font-semibold text-brand-900 mb-1">
                            Gestión académica
                        </h3>
                        <p class="text-xs text-brand-700/80 mb-3">
                            Accede rápidamente a la administración de usuarios, materias y a las herramientas clave del día a día.
                        </p>

                        <div class="flex flex-wrap gap-2">
                            <!-- Usuarios -->
                            <div class="relative group">
                                <template v-if="$page.props.auth.user.role === 'admin'">
                                    <Link
                                        :href="route('users.index')"
                                        class="btn-outline text-xs"
                                    >
                                        Usuarios
                                    </Link>
                                </template>
                                <template v-else>
                                    <span
                                        class="btn-outline text-xs cursor-not-allowed opacity-60 select-none"
                                    >
                                        Usuarios
                                    </span>
                                </template>

                                <span
                                    v-if="$page.props.auth.user.role !== 'admin'"
                                    class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-8
                                           whitespace-nowrap rounded-lg bg-brand-900 px-2 py-1 text-[10px] text-brand-50
                                           opacity-0 group-hover:opacity-100 transition-opacity shadow-lg"
                                >
                                    Solo el administrador puede acceder a esta función
                                </span>
                            </div>

                            <!-- Materias -->
                            <div class="relative group">
                                <template v-if="$page.props.auth.user.role === 'admin'">
                                    <Link
                                        :href="route('materias.index')"
                                        class="btn-outline text-xs"
                                    >
                                        Materias
                                    </Link>
                                </template>
                                <template v-else>
                                    <span
                                        class="btn-outline text-xs cursor-not-allowed opacity-60 select-none"
                                    >
                                        Materias
                                    </span>
                                </template>
                                <span
                                    v-if="$page.props.auth.user.role !== 'admin'"
                                    class="pointer-events-none absolute left-1/2 -translate-x-1/2 top-8
                                           whitespace-nowrap rounded-lg bg-brand-900 px-2 py-1 text-[10px] text-brand-50
                                           opacity-0 group-hover:opacity-100 transition-opacity shadow-lg"
                                >
                                    Solo el administrador puede acceder a esta función
                                </span>
                            </div>

                            <Link
                                :href="route('tutorias.index')"
                                class="btn-outline text-xs"
                            >
                                Mis tutorías
                            </Link>
                            <Link
                                :href="route('grades.index')"
                                class="btn-outline text-xs"
                            >
                                Calificaciones
                            </Link>
                            <Link
                                :href="route('agenda.index')"
                                class="btn-outline text-xs"
                            >
                                Agenda
                            </Link>
                        </div>
                    </div>

                    <div
                        v-if="$page.props.auth.user.role !== 'alumno'"
                        class="card-soft p-4"
                    >
                        <h3 class="text-sm font-semibold text-brand-900 mb-1">
                            Vista docente
                        </h3>
                        <p class="text-xs text-brand-700/80 mb-3">
                            Espacio pensado para docentes y administradores. Aquí podrás tener a la mano un resumen de tu carga académica y tutorías.
                        </p>
                        <ul class="text-xs text-brand-700/90 space-y-1.5">
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-400"></span>
                                Revisa rápidamente qué materias asesoras y qué grupos atiendes.
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-400"></span>
                                Centraliza tus tutorías y sesiones futuras desde un mismo panel.
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

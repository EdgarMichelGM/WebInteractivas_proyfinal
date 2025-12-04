<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Notas temporales
const notes = [
    {
        id: 1,
        title: 'Bienvenido a Mr Aprende',
        body: 'Recuerda que desde este panel puedes gestionar usuarios, materias y tus tutorías.',
        tag: 'General',
    },
    {
        id: 2,
        title: 'Asigna asesores a materias',
        body: 'No olvides revisar que cada materia tenga un asesor asignado para que los alumnos vean a quién acudir.',
        tag: 'Materias',
    },
    {
        id: 3,
        title: 'Próximamente: calificaciones',
        body: 'En la sección de Calificaciones podrás ver vistas diferentes para asesor y alumno.',
        tag: 'Calificaciones',
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
                    Administra usuarios, materias, cursos y tutorías desde un solo lugar.
                </p>
            </header>

            <!-- Carrusel de notas -->
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
                        <!-- Controles izquierda -->
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

                        <!-- Dots -->
                        <div class="flex items-center gap-1.5">
                            <button
                                v-for="(note, index) in notes"
                                :key="note.id"
                                type="button"
                                class="h-2.5 w-2.5 rounded-full transition-all"
                                :class="index === currentIndex
                                    ? 'bg-brand-500 w-4'
                                    : 'bg-brand-200 hover:bg-brand-300'"
                                @click="goToNote(index)"
                            />
                        </div>
                    </div>
                </div>

                <!-- Tarjetas rápidas (atajos) -->
                <div class="space-y-3">
                    <div class="card-soft p-4">
                        <h3 class="text-sm font-semibold text-brand-900 mb-1">
                            Gestión académica
                        </h3>
                        <p class="text-xs text-brand-700/80 mb-3">
                            Accede rápidamente a la administración de usuarios y materias.
                        </p>
                        <div class="flex gap-2">
                            <Link :href="route('users.index')" class="btn-outline text-xs">
                                Usuarios
                            </Link>
                            <Link :href="route('materias.index')" class="btn-outline text-xs">
                                Materias
                            </Link>
                        </div>
                    </div>

                    <div class="card-soft p-4">
                        <h3 class="text-sm font-semibold text-brand-900 mb-1">
                            Tutorías y cursos
                        </h3>
                        <p class="text-xs text-brand-700/80 mb-3">
                            Explora el catálogo de cursos y revisa tus tutorías activas.
                        </p>
                        <div class="flex gap-2">
                            <Link :href="route('courses.index')" class="btn-outline text-xs">
                                Ver cursos
                            </Link>
                            <Link :href="route('tutorias.index')" class="btn-outline text-xs">
                                Mis tutorías
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

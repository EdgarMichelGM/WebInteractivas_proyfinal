<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    materias: Array,
});
</script>

<template>

    <Head title="Materias" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <header class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- Botón regresar con icono -->
                    <Link
                        :href="route('dashboard')"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-brand-200 bg-white text-brand-700 shadow-sm hover:bg-brand-50 hover:text-brand-900 hover:border-brand-300 transition"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>

                    <div>
                        <span class="badge-soft w-fit">
                            Configuración académica
                        </span>
                        <h1 class="text-2xl font-semibold text-brand-900">
                            Materias
                        </h1>
                        <p class="text-sm text-brand-700/80 max-w-xl">
                            Administra las materias del sistema, asigna un asesor y vincula alumnos.
                        </p>
                    </div>
                </div>

                <Link :href="route('materias.create')" class="btn-primary text-xs">
                    + Nueva materia
                </Link>
            </header>

            <section class="card-soft p-4">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-orange-100 text-left text-xs uppercase text-brand-500">
                            <th class="py-2">Clave</th>
                            <th class="py-2">Materia</th>
                            <th class="py-2">Asesor</th>
                            <th class="py-2">Alumnos</th>
                            <th class="py-2 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="m in props.materias"
                            :key="m.id"
                            class="border-b border-orange-50 hover:bg-brand-50/40"
                        >
                            <td class="py-2 align-middle font-mono text-xs text-brand-700/90">
                                {{ m.clave }}
                            </td>
                            <td class="py-2 align-middle">
                                {{ m.nombre }}
                            </td>
                            <td class="py-2 align-middle text-brand-700/90">
                                {{ m.asesor }}
                            </td>
                            <td class="py-2 align-middle">
                                <span class="badge-soft">
                                    {{ m.alumnos }} alumnos
                                </span>
                            </td>
                            <td class="py-2 align-middle text-right space-x-2">
                                <Link
                                    :href="route('materias.edit', m.id)"
                                    class="text-xs font-medium text-brand-700 hover:text-brand-900"
                                >
                                    Editar
                                </Link>
                                <button class="text-xs text-red-500" disabled>
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

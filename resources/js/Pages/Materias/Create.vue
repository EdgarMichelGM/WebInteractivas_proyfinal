<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    asesores: Array,
});

const form = ref({
    nombre: '',
    clave: '',
    asesor_id: '',
});
</script>

<template>
    <Head title="Nueva materia" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="badge-soft w-fit">
                        Materias
                    </span>
                    <h1 class="text-2xl font-semibold text-brand-900">
                        Crear materia
                    </h1>
                    <p class="text-sm text-brand-700/80 max-w-xl">
                        Define una nueva materia y asigna un asesor responsable.
                    </p>
                </div>

                <Link :href="route('materias.index')" class="btn-outline text-xs">
                    Volver al listado
                </Link>
            </header>

            <section class="card-soft p-6 max-w-xl space-y-4">
                <div class="grid gap-4">
                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Nombre de la materia
                        </label>
                        <input
                            v-model="form.nombre"
                            type="text"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            placeholder="Ej. Cálculo diferencial"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Clave
                        </label>
                        <input
                            v-model="form.clave"
                            type="text"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            placeholder="Ej. MAT101"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Asesor asignado
                        </label>
                        <select
                            v-model="form.asesor_id"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                        >
                            <option value="">Selecciona un asesor</option>
                            <option
                                v-for="a in props.asesores"
                                :key="a.id"
                                :value="a.id"
                            >
                                {{ a.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="btn-primary text-sm" type="button" disabled>
                        Guardar (conectamos luego)
                    </button>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

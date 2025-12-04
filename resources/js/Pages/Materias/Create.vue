<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    asesores: Array,
});

const form = useForm({
    nombre: '',
    clave: '',
    asesor_id: '',
    descripcion: '',
});

const submit = () => {
    form.post(route('materias.store'));
};
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
                    <p class="text-sm text-brand-800 max-w-xl">
                        Define los datos básicos de la materia y asigna un asesor responsable.
                    </p>
                </div>

                <Link :href="route('materias.index')" class="btn-outline text-xs">
                    Volver al listado
                </Link>
            </header>

            <section class="card-soft p-6 max-w-xl space-y-4">
                <form class="grid gap-4" @submit.prevent="submit">
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
                        <p v-if="form.errors.nombre" class="mt-1 text-xs text-red-500">
                            {{ form.errors.nombre }}
                        </p>
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
                        <p v-if="form.errors.clave" class="mt-1 text-xs text-red-500">
                            {{ form.errors.clave }}
                        </p>
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
                        <p v-if="form.errors.asesor_id" class="mt-1 text-xs text-red-500">
                            {{ form.errors.asesor_id }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Descripción (opcional)
                        </label>
                        <textarea
                            v-model="form.descripcion"
                            rows="3"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            placeholder="Breve descripción del contenido de la materia"
                        />
                        <p v-if="form.errors.descripcion" class="mt-1 text-xs text-red-500">
                            {{ form.errors.descripcion }}
                        </p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            class="btn-primary text-sm"
                            type="submit"
                            :disabled="form.processing"
                        >
                            Guardar
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

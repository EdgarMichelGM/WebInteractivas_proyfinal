<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';


const props = defineProps({
    modo: {
        type: String, // 'alumno' | 'docente'
        required: true,
    },
    clases: {
        type: Array,
        default: () => [],
    },
    materiasOptions: {
        type: Array,
        default: () => [],
    },
});

console.log('AGENDAAA modo:', props.modo);
console.log('AGENDAAA materiasOptions:', props.materiasOptions);


const esAlumno = computed(() => props.modo === 'alumno');

const form = useForm({
    materia_id: '',
    titulo: '',
});

const iniciarClase = () => {
    form.post(route('online-classes.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('materia_id', 'titulo');
        },
    });
};

const closeForm = useForm({});

const cerrarClase = (id) => {
    closeForm.post(route('online-classes.close', id), {
        preserveScroll: true,
    });
};

// simulación de "entrar" a la clase para alumno
const claseSeleccionada = ref(null);

const entrarClase = (clase) => {
    claseSeleccionada.value = clase;
};
</script>

<template>
    <Head :title="esAlumno ? 'Clases en línea' : 'Clases en línea docentes'" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <header>
                <span class="badge-soft w-fit">
                    Clases en línea
                </span>
                <h1 class="text-2xl font-semibold text-brand-900 mt-1">
                    {{ esAlumno ? 'Clases en curso' : 'Gestión de clases en línea' }}
                </h1>
                <p class="text-sm text-brand-700 max-w-xl mt-1">
                    <span v-if="esAlumno">
                        Aquí verás las clases en línea activas de las materias en las que estás inscrito.
                    </span>
                    <span v-else>
                        Inicia y cierra clases en línea para tus materias. Los alumnos las verán cuando estén activas.
                    </span>
                </p>
            </header>

            <!-- Alumno -->
            <section v-if="esAlumno" class="space-y-4">
                <div
                    v-if="!clases.length"
                    class="card-soft p-6 text-sm text-brand-700 text-center"
                >
                    No hay clases en línea activas en este momento.
                </div>

                <div
                    v-else
                    class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="c in clases"
                        :key="c.id"
                        class="card-soft p-4 flex flex-col gap-2"
                    >
                        <p class="text-xs uppercase tracking-wide text-brand-700 font-semibold">
                            {{ c.clave }}
                        </p>
                        <h2 class="text-sm font-semibold text-brand-900">
                            {{ c.materia }}
                        </h2>
                        <p class="text-xs text-brand-700">
                            {{ c.titulo }}
                        </p>
                        <p class="text-[11px] text-brand-500">
                            Docente: <span class="font-medium">{{ c.docente }}</span>
                        </p>
                        <p class="text-[11px] text-brand-500">
                            Inicio: {{ c.started_at }}
                        </p>

                        <button
                            type="button"
                            class="btn-primary text-xs mt-2"
                            @click="entrarClase(c)"
                        >
                            Entrar a la clase
                        </button>
                    </article>
                </div>

                <div
                    v-if="claseSeleccionada"
                    class="card-soft p-4 text-sm text-brand-800"
                >
                    <h2 class="text-sm font-semibold text-brand-900 mb-1">
                        Estás en la clase:
                        {{ claseSeleccionada.materia }} ({{ claseSeleccionada.clave }})
                    </h2>
                    <p class="text-xs text-brand-700">
                        {{ claseSeleccionada.titulo }}
                    </p>
                    <p class="text-xs text-brand-600 mt-2">
                        Aquí podrías integrar videollamada, chat o materiales. Por ahora es una simulación de que
                        entraste a la clase en línea.
                    </p>
                </div>
            </section>

            <!-- Docente / Admin -->
            <section v-else class="space-y-4">
                <div class="card-soft p-4 space-y-4">
                    <h2 class="text-sm font-semibold text-brand-900">
                        Iniciar nueva clase
                    </h2>

                    <form @submit.prevent="iniciarClase" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-brand-800">Materia</label>
                            <select
                                v-model="form.materia_id"
                                class="input"
                            >
                                <option value="">Selecciona una materia</option>
                                <option
                                    v-for="m in materiasOptions"
                                    :key="m.id"
                                    :value="m.id"
                                >
                                    {{ m.label }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.materia_id"
                                class="text-xs text-red-600"
                            >
                                {{ form.errors.materia_id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2 lg:col-span-2">
                            <label class="text-xs font-medium text-brand-800">Título de la clase</label>
                            <input
                                type="text"
                                v-model="form.titulo"
                                class="input"
                                placeholder="Ej. Repaso para el examen parcial"
                            />
                            <p
                                v-if="form.errors.titulo"
                                class="text-xs text-red-600"
                            >
                                {{ form.errors.titulo }}
                            </p>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-3 flex justify-end">
                            <button
                                type="submit"
                                class="btn-primary text-xs py-1"
                                :disabled="form.processing"
                            >
                                Iniciar clase ahora
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-soft p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-xs uppercase text-brand-700">
                                <th class="py-2">Clave</th>
                                <th class="py-2">Materia</th>
                                <th class="py-2">Título</th>
                                <th class="py-2">Docente</th>
                                <th class="py-2">Inicio</th>
                                <th class="py-2">Fin</th>
                                <th class="py-2">Estado</th>
                                <th class="py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-if="!clases.length"
                                class="border-b border-orange-50"
                            >
                                <td colspan="8" class="py-4 text-center text-xs text-brand-600">
                                    Aún no hay clases registradas.
                                </td>
                            </tr>
                            <tr
                                v-for="c in clases"
                                :key="c.id"
                                class="border-b border-orange-50 hover:bg-brand-50/40"
                            >
                                <td class="py-2 align-middle font-mono text-xs text-brand-900">
                                    {{ c.clave }}
                                </td>
                                <td class="py-2 align-middle text-brand-900">
                                    {{ c.materia }}
                                </td>
                                <td class="py-2 align-middle text-brand-900">
                                    {{ c.titulo }}
                                </td>
                                <td class="py-2 align-middle text-brand-700">
                                    {{ c.docente }}
                                </td>
                                <td class="py-2 align-middle text-brand-700">
                                    {{ c.started_at }}
                                </td>
                                <td class="py-2 align-middle text-brand-700">
                                    {{ c.ended_at || '—' }}
                                </td>
                                <td class="py-2 align-middle">
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium"
                                        :class="c.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-brand-100 text-brand-700'"
                                    >
                                        {{ c.is_active ? 'Activa' : 'Cerrada' }}
                                    </span>
                                </td>
                                <td class="py-2 align-middle">
                                    <button
                                        v-if="c.is_active"
                                        type="button"
                                        class="btn-outline text-[11px] py-0.5 px-2"
                                        @click="cerrarClase(c.id)"
                                        :disabled="closeForm.processing"
                                    >
                                        Cerrar
                                    </button>
                                    <span
                                        v-else
                                        class="text-[11px] text-brand-400"
                                    >
                                        —
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

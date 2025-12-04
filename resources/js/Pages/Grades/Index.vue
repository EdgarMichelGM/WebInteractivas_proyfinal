<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    modo: {
        type: String, // 'alumno' | 'docente'
        required: true,
    },
    calificaciones: {
        type: Array,
        default: () => [],
    },
    materiasOptions: {
        type: Array,
        default: () => [],
    },
    alumnosOptions: {
        type: Array,
        default: () => [],
    },
});

const emailForm = useForm({});

const enviarCorreo = (id) => {
    emailForm.post(route('grades.sendEmail', id), {
        preserveScroll: true,
    });
};

const page = usePage();

const gradeForm = useForm({
    materia_id: '',
    alumno_id: '',
    valor: '',
    oportunidad: '',
    observaciones: '',
});

const submitGrade = () => {
    gradeForm.post(route('grades.store'), {
        preserveScroll: true,
        onSuccess: () => {
            gradeForm.reset('valor', 'oportunidad', 'observaciones');
        },
    });
};
</script>

<template>
    <Head :title="modo === 'alumno' ? 'Mis calificaciones' : 'Calificaciones'" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <header>
                <span class="badge-soft w-fit">
                    {{ modo === 'alumno' ? 'Mis calificaciones' : 'Gestión de calificaciones' }}
                </span>
                <h1 class="text-2xl font-semibold text-brand-900 mt-1">
                    {{ modo === 'alumno'
                        ? 'Resumen de tus calificaciones'
                        : 'Calificaciones por materia y alumno'
                    }}
                </h1>
                <p class="text-sm text-brand-700 max-w-xl mt-1">
                    <span v-if="modo === 'alumno'">
                        Aquí puedes consultar las calificaciones de las materias en las que estás inscrito.
                    </span>
                    <span v-else>
                        Registra y consulta las calificaciones de tus alumnos por materia.
                    </span>
                </p>
            </header>

            <!-- Alumno: solo consulta -->
            <section v-if="modo === 'alumno'">
                <div class="card-soft p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-xs uppercase text-brand-700">
                                <th class="py-2">Clave</th>
                                <th class="py-2">Materia</th>
                                <th class="py-2">Calificación</th>
                                <th class="py-2">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-if="!calificaciones.length"
                                class="border-b border-orange-50"
                            >
                                <td colspan="4" class="py-4 text-center text-xs text-brand-600">
                                    Aún no tienes calificaciones registradas.
                                </td>
                            </tr>
                            <tr
                                v-for="c in calificaciones"
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
                                    {{ c.promedio }}
                                </td>
                                <td class="py-2 align-middle text-brand-700 capitalize">
                                    {{ c.estado }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Docente / Admin -->
            <section v-else class="space-y-4">
                <!-- Formulario de nueva calificación -->
                <div class="card-soft p-4 space-y-4">
                    <h2 class="text-sm font-semibold text-brand-900">
                        Registrar calificación
                    </h2>

                    <form @submit.prevent="submitGrade" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-brand-800">Materia</label>
                            <select
                                v-model="gradeForm.materia_id"
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
                                v-if="gradeForm.errors.materia_id"
                                class="text-xs text-red-600"
                            >
                                {{ gradeForm.errors.materia_id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-brand-800">Alumno</label>
                            <select
                                v-model="gradeForm.alumno_id"
                                class="input"
                            >
                                <option value="">Selecciona un alumno</option>
                                <option
                                    v-for="a in alumnosOptions"
                                    :key="a.id"
                                    :value="a.id"
                                >
                                    {{ a.label }}
                                </option>
                            </select>
                            <p
                                v-if="gradeForm.errors.alumno_id"
                                class="text-xs text-red-600"
                            >
                                {{ gradeForm.errors.alumno_id }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-brand-800">Calificación</label>
                            <input
                                type="number"
                                min="0"
                                max="100"
                                v-model="gradeForm.valor"
                                class="input"
                                placeholder="0 - 100"
                            />
                            <p
                                v-if="gradeForm.errors.valor"
                                class="text-xs text-red-600"
                            >
                                {{ gradeForm.errors.valor }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-medium text-brand-800">Oportunidad</label>
                            <input
                                type="text"
                                v-model="gradeForm.oportunidad"
                                class="input"
                                placeholder="Ordinario, Extraordinario..."
                            />
                            <p
                                v-if="gradeForm.errors.oportunidad"
                                class="text-xs text-red-600"
                            >
                                {{ gradeForm.errors.oportunidad }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 sm:col-span-2 lg:col-span-4">
                            <label class="text-xs font-medium text-brand-800">Observaciones</label>
                            <textarea
                                v-model="gradeForm.observaciones"
                                class="input"
                                rows="2"
                                placeholder="Comentarios opcionales"
                            />
                            <p
                                v-if="gradeForm.errors.observaciones"
                                class="text-xs text-red-600"
                            >
                                {{ gradeForm.errors.observaciones }}
                            </p>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
                            <button
                                type="submit"
                                class="btn-primary text-xs"
                                :disabled="gradeForm.processing"
                            >
                                Guardar calificación
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de calificaciones -->
                <div class="card-soft p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-xs uppercase text-brand-700">
                                <th class="py-2">Alumno</th>
                                <th class="py-2">Clave</th>
                                <th class="py-2">Materia</th>
                                <th class="py-2">Calificación</th>
                                <th class="py-2">Estado</th>
                                <th class="py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-if="!calificaciones.length"
                                class="border-b border-orange-50"
                            >
                                <td colspan="6" class="py-4 text-center text-xs text-brand-600">
                                    Aún no hay calificaciones registradas.
                                </td>
                            </tr>
                            <tr
                                v-for="c in calificaciones"
                                :key="c.id"
                                class="border-b border-orange-50 hover:bg-brand-50/40"
                            >
                                <td class="py-2 align-middle text-brand-900">
                                    {{ c.alumno }}
                                </td>
                                <td class="py-2 align-middle font-mono text-xs text-brand-900">
                                    {{ c.clave }}
                                </td>
                                <td class="py-2 align-middle text-brand-900">
                                    {{ c.materia }}
                                </td>
                                <td class="py-2 align-middle text-brand-900">
                                    {{ c.promedio }}
                                </td>
                                <td class="py-2 align-middle text-brand-700 capitalize">
                                    {{ c.estado }}
                                </td>
                                <td class="py-2 align-middle">
                                    <button
                                        type="button"
                                        class="btn-outline text-[11px] py-0.5 px-2"
                                        @click="enviarCorreo(c.id)"
                                        :disabled="emailForm.processing"
                                    >
                                        Enviar correo
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

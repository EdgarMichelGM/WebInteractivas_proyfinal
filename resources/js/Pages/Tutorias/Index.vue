<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    Chart as ChartJS,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
} from 'chart.js';
import { Bar } from 'vue-chartjs';

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const props = defineProps({
    modo: {
        type: String,
        required: true, // 'alumno' | 'docente'
    },
    materias: {
        type: Array,
        default: () => [],
    },
});
const chartRef = ref(null);

const docenteChartData = computed(() => {
    if (props.modo !== 'docente') {
        return {
            labels: [],
            datasets: [],
        };
    }

    const labels = props.materias.map((m) => m.clave || m.nombre);
    const data = props.materias.map((m) => m.alumnos ?? 0);

    return {
        labels,
        datasets: [
            {
                label: 'Alumnos por materia',
                data,
            },
        ],
    };
});

const docenteChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
        },
        tooltip: {
            enabled: true,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            precision: 0,
            ticks: {
                stepSize: 1,
            },
        },
    },
};

const descargarGrafica = () => {
    if (props.modo !== 'docente') return;
    if (!chartRef.value) return;

    const chart = chartRef.value.chart;
    if (!chart) return;

    const url = chart.toBase64Image('image/png', 1);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'desempeno-tutorias.png';
    a.click();
};
</script>

<template>
    <Head :title="modo === 'alumno' ? 'Mis tutorías' : 'Tutorías'" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <header>
                <span class="badge-soft w-fit">
                    {{ modo === 'alumno' ? 'Mis tutorías' : 'Gestión de tutorías' }}
                </span>
                <h1 class="text-2xl font-semibold text-brand-900 mt-1">
                    {{ modo === 'alumno'
                        ? 'Materias en las que tienes tutoría'
                        : 'Materias con tutoría activa'
                    }}
                </h1>
                <p class="text-sm text-brand-700 max-w-xl mt-1">
                    <span v-if="modo === 'alumno'">
                        Aquí verás las materias en las que te has inscrito desde el catálogo de cursos.
                    </span>
                    <span v-else>
                        Como docente/administrador puedes revisar y gestionar las tutorías de tus materias.
                    </span>
                </p>
            </header>

            <section v-if="modo === 'alumno'">
                <div
                    v-if="materias.length"
                    class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="m in materias"
                        :key="m.id"
                        class="card-soft p-4 flex flex-col gap-2"
                    >
                        <p class="text-xs uppercase tracking-wide text-brand-700 font-semibold">
                            {{ m.clave }}
                        </p>
                        <h2 class="text-sm font-semibold text-brand-900">
                            {{ m.nombre }}
                        </h2>
                        <p class="text-xs text-brand-700">
                            Asesor:
                            <span class="font-medium text-brand-900">
                                {{ m.asesor || 'Por asignar' }}
                            </span>
                        </p>
                    </article>
                </div>

                <div
                    v-else
                    class="card-soft p-6 text-sm text-brand-700 text-center"
                >
                    Aún no te has inscrito a ninguna materia.
                    Entra al catálogo de
                    <span class="font-medium">Explorar cursos</span>
                    para añadir tutorías.
                </div>
            </section>

            <section v-else class="space-y-6">
                <div class="card-soft p-4 space-y-4">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-semibold text-brand-900">
                                Desempeño de tus tutorías
                            </h2>
                            <p class="text-xs text-brand-600">
                                Alumnos inscritos por materia.
                            </p>
                        </div>

                        <button
                            v-if="materias.length"
                            type="button"
                            class="btn-outline text-xs"
                            @click="descargarGrafica"
                        >
                            Descargar gráfica
                        </button>
                    </div>

                    <div v-if="materias.length" class="h-64">
                        <Bar
                            ref="chartRef"
                            :data="docenteChartData"
                            :options="docenteChartOptions"
                        />
                    </div>

                    <p v-else class="text-xs text-brand-600">
                        Aún no tienes materias con alumnos inscritos para mostrar en la gráfica.
                    </p>
                </div>

                <!-- Tabla de materias con alumnos -->
                <div class="card-soft p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-orange-100 text-left text-xs uppercase text-brand-700">
                                <th class="py-2">Clave</th>
                                <th class="py-2">Materia</th>
                                <th class="py-2">Asesor</th>
                                <th class="py-2">Alumnos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="m in materias"
                                :key="m.id"
                                class="border-b border-orange-50 hover:bg-brand-50/40"
                            >
                                <td class="py-2 align-middle font-mono text-xs text-brand-900">
                                    {{ m.clave }}
                                </td>
                                <td class="py-2 align-middle text-brand-900">
                                    {{ m.nombre }}
                                </td>
                                <td class="py-2 align-middle text-brand-700">
                                    {{ m.asesor }}
                                </td>
                                <td class="py-2 align-middle text-brand-700">
                                    {{ m.alumnos }} alumnos
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

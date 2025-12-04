<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    materias: {
        type: Array,
        default: () => [],
    },
});


const showConfirm = ref(false);
const materiaConfirmada = ref(null);
const loadingId = ref(null);

const inscritasIds = ref(
    props.materias
        .filter((m) => m.inscrito)
        .map((m) => m.id)
);

const inscribirse = (materia) => {
    if (inscritasIds.value.includes(materia.id)) {

        router.visit(route('tutorias.index'));


        return;
    }

    loadingId.value = materia.id;

    router.post(
        route('materias.enroll', materia.id),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                materiaConfirmada.value = materia;
                showConfirm.value = true;
                loadingId.value = null;

                if (!inscritasIds.value.includes(materia.id)) {
                    inscritasIds.value.push(materia.id);
                }
            },
            onError: () => {
                loadingId.value = null;
            },
        }
    );
};

const textoBoton = (materia) => {
    if (loadingId.value === materia.id) {
        return 'Inscribiendo...';
    }
    if (inscritasIds.value.includes(materia.id)) {
        return 'Ver materia';
    }
    return 'Inscribirme';
};


const botonDisabled = (materia) => {
    return loadingId.value === materia.id;
};
</script>

<template>
    <Head title="Explorar cursos" />

    <AuthenticatedLayout>
        <div class="space-y-6">

            <header class="space-y-1">
                <span class="badge-soft w-fit">
                    Catálogo de cursos
                </span>
                <h1 class="text-2xl font-semibold text-brand-900">
                    Explorar cursos
                </h1>
                <p class="text-sm text-brand-700 max-w-xl">
                    Inscríbete a las materias en las que deseas tener tutoría. Después podrás gestionarlas desde
                    <span class="font-medium">Mis tutorías</span>.
                </p>
            </header>


            <div
                v-if="materias.length"
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <article
                    v-for="m in materias"
                    :key="m.id"
                    class="card-soft p-4 flex flex-col gap-3"
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


                    <p class="text-xs text-brand-600">
                        Nivel: <span class="font-medium">{{ m.nivel }}</span> ·
                        Duración: <span class="font-medium">{{ m.duracion }}</span>
                    </p>


                    <button
                        type="button"
                        class="btn-primary text-xs mt-2"
                        @click="inscribirse(m)"
                        :disabled="botonDisabled(m)"
                    >
                        {{ textoBoton(m) }}
                    </button>
                </article>
            </div>

            <div
                v-else
                class="card-soft p-6 text-sm text-brand-700 text-center"
            >
                No hay cursos disponibles por el momento.
                Vuelve más tarde o consulta con tu administrador.
            </div>
        </div>

        <transition name="fade">
            <div
                v-if="showConfirm"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50"
            >
                <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4">
                    <h2 class="text-lg font-semibold text-brand-900 flex items-center gap-2">
                        <span
                            class="h-7 w-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm"
                        >
                            ✓
                        </span>
                        Inscripción exitosa
                    </h2>

                    <p class="text-sm text-brand-700 mt-2">
                        Te has inscrito a:
                        <span class="font-medium">
                            {{ materiaConfirmada?.clave }} - {{ materiaConfirmada?.nombre }}
                        </span>
                    </p>

                    <p class="text-xs text-brand-500 mt-1">
                        Ahora podrás ver esta materia en la sección <span class="font-medium">Mis tutorías</span>.
                    </p>

                    <div class="mt-4 flex justify-end gap-2">
                        <Link
                            :href="route('tutorias.index')"
                            class="btn-outline text-xs"
                            @click="showConfirm = false"
                        >
                            Ver mis tutorías
                        </Link>

                        <button
                            class="btn-primary text-xs"
                            @click="showConfirm = false"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

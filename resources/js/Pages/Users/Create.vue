<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    name: '',
    email: '',
    role: 'alumno',
    password: '',
    password_confirmation: '',
});
</script>

<template>
    <Head title="Nuevo usuario" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="badge-soft w-fit">
                        Usuarios
                    </span>
                    <h1 class="text-2xl font-semibold text-brand-900">
                        Crear usuario
                    </h1>
                    <p class="text-sm text-brand-700/80 max-w-xl">
                        Completa los datos básicos para registrar un nuevo usuario.
                    </p>
                </div>

                <Link :href="route('users.index')" class="btn-outline text-xs">
                    Volver al listado
                </Link>
            </header>

            <section class="card-soft p-6 max-w-xl space-y-4">
                <div class="grid gap-4">
                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Nombre
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            placeholder="Nombre completo"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Correo electrónico
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            placeholder="correo@ejemplo.com"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-brand-800 mb-1">
                            Rol
                        </label>
                        <select
                            v-model="form.role"
                            class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                        >
                            <option value="alumno">Alumno</option>
                            <option value="asesor">Asesor</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-brand-800 mb-1">
                                Contraseña
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-brand-800 mb-1">
                                Confirmar contraseña
                            </label>
                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                class="mt-1 block w-full rounded-xl border-orange-200 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-300"
                            />
                        </div>
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    role: 'alumno',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('users.store'));
};
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
                    <p class="text-sm text-brand-800 max-w-xl">
                        Completa los datos básicos para registrar un nuevo usuario.
                    </p>
                </div>

                <Link :href="route('users.index')" class="btn-outline text-xs">
                    Volver al listado
                </Link>
            </header>

            <section class="card-soft p-6 max-w-xl space-y-4">
                <form class="grid gap-4" @submit.prevent="submit">
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
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">
                            {{ form.errors.name }}
                        </p>
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
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">
                            {{ form.errors.email }}
                        </p>
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
                        <p v-if="form.errors.role" class="mt-1 text-xs text-red-500">
                            {{ form.errors.role }}
                        </p>
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
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">
                                {{ form.errors.password }}
                            </p>
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

                    <div class="flex justify-end pt-2">
                        <button class="btn-primary text-sm" type="submit" :disabled="form.processing">
                            Guardar
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    dispositivos: Array,
    marcas: Array,
});

const editando = ref(false);
const dispositivoEditando = ref(null);

const form = useForm({
    marca_id: '',
    modelo: '',
    precio: '',
    stock: '',
    imagen: null,
});

const guardarDispositivo = () => {
    form.post('/dispositivos', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const editarDispositivo = (dispositivo) => {
    editando.value = true;
    dispositivoEditando.value = dispositivo.id;

    form.marca_id = dispositivo.marca_id;
    form.modelo = dispositivo.modelo;
    form.precio = dispositivo.precio;
    form.stock = dispositivo.stock;
    form.imagen = null;

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const actualizarDispositivo = () => {
    form
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post(`/dispositivos/${dispositivoEditando.value}`, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                editando.value = false;
                dispositivoEditando.value = null;
            },
        });
};

const cancelarEdicion = () => {
    form.reset();
    editando.value = false;
    dispositivoEditando.value = null;
};

const eliminarDispositivo = (id) => {
    if (confirm('¿Seguro que deseas eliminar este dispositivo?')) {
        useForm({}).delete(`/dispositivos/${id}`, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Dispositivos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tienda Tecnológica - Dispositivos
                </h2>

                <a
                    href="/marcas"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Gestionar Marcas
                </a>
            </div>
        </template>

        <div class="py-8 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-bold mb-4">
                        {{ editando ? 'Editar dispositivo' : 'Registrar dispositivo' }}
                    </h3>

                    <form
                        @submit.prevent="editando ? actualizarDispositivo() : guardarDispositivo()"
                        class="grid grid-cols-1 md:grid-cols-5 gap-4"
                    >
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Marca
                            </label>

                            <select
                                v-model="form.marca_id"
                                class="w-full border rounded p-2"
                            >
                                <option value="">
                                    Seleccione una marca
                                </option>

                                <option
                                    v-for="marca in marcas"
                                    :key="marca.id"
                                    :value="marca.id"
                                >
                                    {{ marca.nombre }}
                                </option>
                            </select>

                            <p
                                v-if="form.errors.marca_id"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.marca_id }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Modelo
                            </label>

                            <input
                                v-model="form.modelo"
                                type="text"
                                class="w-full border rounded p-2"
                                placeholder="Ejemplo: Galaxy S24"
                            >

                            <p
                                v-if="form.errors.modelo"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.modelo }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Precio
                            </label>

                            <input
                                v-model="form.precio"
                                type="number"
                                step="0.01"
                                class="w-full border rounded p-2"
                                placeholder="Ejemplo: 2500"
                            >

                            <p
                                v-if="form.errors.precio"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.precio }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Stock
                            </label>

                            <input
                                v-model="form.stock"
                                type="number"
                                class="w-full border rounded p-2"
                                placeholder="Ejemplo: 15"
                            >

                            <p
                                v-if="form.errors.stock"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.stock }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Imagen
                            </label>

                            <input
                                type="file"
                                class="w-full border rounded p-2"
                                accept="image/*"
                                @change="form.imagen = $event.target.files[0]"
                            >

                            <p
                                v-if="form.errors.imagen"
                                class="text-red-600 text-sm mt-1"
                            >
                                {{ form.errors.imagen }}
                            </p>
                        </div>

                        <div class="md:col-span-5 flex gap-2">
                            <button
                                type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                                :disabled="form.processing"
                            >
                                {{ editando ? 'Actualizar dispositivo' : 'Guardar dispositivo' }}
                            </button>

                            <button
                                v-if="editando"
                                type="button"
                                @click="cancelarEdicion"
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                            >
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">
                        Lista de dispositivos registrados
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-2">ID</th>
                                    <th class="border p-2">Imagen</th>
                                    <th class="border p-2">Marca</th>
                                    <th class="border p-2">Modelo</th>
                                    <th class="border p-2">Precio</th>
                                    <th class="border p-2">Stock</th>
                                    <th class="border p-2">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="dispositivo in dispositivos"
                                    :key="dispositivo.id"
                                >
                                    <td class="border p-2 text-center">
                                        {{ dispositivo.id }}
                                    </td>

                                    <td class="border p-2 text-center">
                                        <img
                                            v-if="dispositivo.imagen"
                                            :src="`/storage/${dispositivo.imagen}`"
                                            class="w-20 h-20 object-cover mx-auto rounded border"
                                        >

                                        <span
                                            v-else
                                            class="text-gray-500"
                                        >
                                            Sin imagen
                                        </span>
                                    </td>

                                    <td class="border p-2">
                                        {{ dispositivo.marca ? dispositivo.marca.nombre : 'Sin marca' }}
                                    </td>

                                    <td class="border p-2">
                                        {{ dispositivo.modelo }}
                                    </td>

                                    <td class="border p-2">
                                        S/ {{ dispositivo.precio }}
                                    </td>

                                    <td class="border p-2">
                                        {{ dispositivo.stock }}
                                    </td>

                                    <td class="border p-2 text-center">
                                        <button
                                            @click="editarDispositivo(dispositivo)"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded mr-2 hover:bg-yellow-600"
                                        >
                                            Editar
                                        </button>

                                        <button
                                            @click="eliminarDispositivo(dispositivo.id)"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>

                                <tr v-if="dispositivos.length === 0">
                                    <td
                                        colspan="7"
                                        class="border p-4 text-center text-gray-600"
                                    >
                                        No hay dispositivos registrados.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
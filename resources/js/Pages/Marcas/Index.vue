<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    marcas: Array,
});

const editando = ref(false);
const marcaEditando = ref(null);

const form = useForm({
    nombre: '',
    pais: '',
});

const guardarMarca = () => {
    form.post('/marcas', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const editarMarca = (marca) => {
    editando.value = true;
    marcaEditando.value = marca.id;

    form.nombre = marca.nombre;
    form.pais = marca.pais;
};

const actualizarMarca = () => {
    form.put(`/marcas/${marcaEditando.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            editando.value = false;
            marcaEditando.value = null;
        },
    });
};

const cancelarEdicion = () => {
    form.reset();
    editando.value = false;
    marcaEditando.value = null;
};

const eliminarMarca = (id) => {
    if (confirm('¿Seguro que deseas eliminar esta marca? También se eliminarán sus dispositivos.')) {
        useForm({}).delete(`/marcas/${id}`, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Marcas" />

    <div class="min-h-screen bg-gray-100">
        <div class="bg-white border-b shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Gestión de Marcas
                    </h1>
                    <p class="text-sm text-gray-500">
                        Registra las marcas de la tienda tecnológica
                    </p>
                </div>

                <a
                    href="/dispositivos"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Ver Dispositivos
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-lg font-bold mb-4">
                    {{ editando ? 'Editar marca' : 'Registrar marca' }}
                </h2>

                <form
                    @submit.prevent="editando ? actualizarMarca() : guardarMarca()"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                >
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Nombre de la marca
                        </label>

                        <input
                            v-model="form.nombre"
                            type="text"
                            class="w-full border rounded p-2"
                            placeholder="Ejemplo: Samsung"
                        >

                        <p v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            País
                        </label>

                        <input
                            v-model="form.pais"
                            type="text"
                            class="w-full border rounded p-2"
                            placeholder="Ejemplo: Corea del Sur"
                        >

                        <p v-if="form.errors.pais" class="text-red-600 text-sm mt-1">
                            {{ form.errors.pais }}
                        </p>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                            :disabled="form.processing"
                        >
                            {{ editando ? 'Actualizar marca' : 'Guardar marca' }}
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
                <h2 class="text-lg font-bold mb-4">
                    Lista de marcas registradas
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-2">ID</th>
                                <th class="border p-2">Nombre</th>
                                <th class="border p-2">País</th>
                                <th class="border p-2">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="marca in marcas" :key="marca.id">
                                <td class="border p-2 text-center">
                                    {{ marca.id }}
                                </td>

                                <td class="border p-2">
                                    {{ marca.nombre }}
                                </td>

                                <td class="border p-2">
                                    {{ marca.pais }}
                                </td>

                                <td class="border p-2 text-center">
                                    <button
                                        @click="editarMarca(marca)"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded mr-2 hover:bg-yellow-600"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        @click="eliminarMarca(marca.id)"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="marcas.length === 0">
                                <td colspan="4" class="border p-4 text-center text-gray-600">
                                    No hay marcas registradas.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
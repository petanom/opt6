<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import ButtonLink from "@/Components/ButtonLink.vue";
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";

const props = defineProps({
    products: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <Head title="Товары" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Товары
            </h2>
        </template>

        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ButtonLink :href="route('products.create')">
                    Добавить товар
                </ButtonLink>
            </div>
        </div>

        <div class="pt-6 pb-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg py-8 px-4">
                    <Table :resource="products">
                        <template v-slot:body>
                            <tr
                                v-for="(product, key) in products.data"
                                :key="key"
                            >
                                <td
                                    v-for="(v, k) in ['id', 'name', 'price']"
                                    :key="k"
                                    class="pl-4 py-2"
                                >
                                    <Link
                                        :href="`/products/${product.id}/edit`"
                                    >
                                        {{ product[v] }}</Link
                                    >
                                </td>
                            </tr>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

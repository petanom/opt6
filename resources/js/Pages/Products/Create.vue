<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import Form from "./Partials/Form.vue";

import { useForm, Head } from "@inertiajs/vue3";

const props = defineProps({
    product: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    name: props.product.name,
    price: props.product.price,
});
</script>

<template>
    <Head title="Товар" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Создание товара
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Информация по заказу
                            </h2>
                        </header>

                        <form
                            @submit.prevent="form.post(route('products.store'))"
                            class="mt-6 space-y-6"
                        >
                            <Form :form="form" />

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing"
                                    >Создать</PrimaryButton
                                >
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

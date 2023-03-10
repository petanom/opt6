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

function destroy() {
    if (confirm("Вы уверены, что хотите удалить этот товар?")) {
        form.delete(route("products.destroy", props.product.id));
    }
}
</script>

<template>
    <Head title="Товар" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Товар #{{ product.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Информация по товару
                            </h2>
                        </header>

                        <form
                            @submit.prevent="
                                form.put(route('products.update', product.id))
                            "
                            class="mt-6 space-y-6"
                        >
                            <Form :form="form" />

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing"
                                    >Сохранить</PrimaryButton
                                >

                                <Transition
                                    enter-from-class="opacity-0"
                                    leave-to-class="opacity-0"
                                    class="transition ease-in-out"
                                >
                                    <p
                                        v-if="form.recentlySuccessful"
                                        class="text-sm text-gray-600"
                                    >
                                        Saved.
                                    </p>
                                </Transition>

                                <PrimaryButton
                                    class="bg-red-700 hover:bg-red-500 focus:bg-red-500 active:bg-red-500"
                                    @click="destroy()"
                                    type="button"
                                >
                                    Удалить
                                </PrimaryButton>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

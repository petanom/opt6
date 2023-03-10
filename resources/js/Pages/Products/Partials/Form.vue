<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

//import { yandexMap, ymapMarker } from "vue-yandex-maps";

const props = defineProps({
    form: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div class="mt-6 space-y-6">
        <div>
            <InputLabel for="name" value="Название" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="price" value="Цена" />

            <TextInput
                id="price"
                type="text"
                class="mt-1 block w-full"
                v-model="form.price"
                required
            />

            <InputError class="mt-2" :message="form.errors.price" />
        </div>
    </div>
</template>

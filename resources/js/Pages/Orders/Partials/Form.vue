<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref, onMounted } from "vue";

//import { yandexMap, ymapMarker } from "vue-yandex-maps";

const props = defineProps({
    form: {
        type: Object,
        default: () => ({}),
    },
    products: {
        type: Array,
        default: () => [],
    },
});

const map = ref(0);
const date = ref(0);

onMounted(() => {
    date.value = new Date(props.form.date * 1000).toLocaleDateString("ru");
    ymaps.ready(() => {
        map.value = new ymaps.Map("yandex-map", {
            center: [55.76, 37.64],
            zoom: 7,
            controls: ["fullscreenControl"],
            searchControlProvider: "yandex#search",
        });
        map.value.behaviors.disable("scrollZoom");
        updateMap(props.form.address);
    });
});

function updateMap(newAddress) {
    ymaps
        .geocode(newAddress, {
            results: 1,
        })
        .then(function (res) {
            // Selecting the first result of geocoding.
            var firstGeoObject = res.geoObjects.get(0),
                // The coordinates of the geo object.
                coords = firstGeoObject.geometry.getCoordinates(),
                // The viewport of the geo object.
                bounds = firstGeoObject.properties.get("boundedBy");

            firstGeoObject.options.set(
                "preset",
                "islands#darkBlueDotIconWithCaption"
            );
            // Getting the address string and displaying it in the geo object icon.
            firstGeoObject.properties.set(
                "iconCaption",
                firstGeoObject.getAddressLine()
            );

            // Adding first found geo object to the map.
            map.value.geoObjects.add(firstGeoObject);
            // Scaling the map to the geo object viewport.
            map.value.setBounds(bounds, {
                // Checking the availability of tiles at the given zoom level.
                checkZoomRange: true,
            });
        });
}

function remove(id) {
    props.form.products.splice(id, 1);
}

const selectProductModal = ref(false);

const closeModal = () => {
    selectProductModal.value = false;
};

function addProduct(product) {
    props.form.products.push({
        id: product.id,
        name: product.name,
        price: product.price,
        pivot: { qnt: 0 },
    });
    selectProductModal.value = false;
}
</script>

<template>
    <div class="mt-6 space-y-6">
        <Modal :show="selectProductModal" @close="closeModal">
            <div class="">
                <div
                    class="flex flex-row w-full items-center border-t border-slate-200 py-2"
                >
                    <div class="px-4 w-1/2 font-bold text-slate-500">
                        Название
                    </div>
                    <div class="px-4 w-1/4 font-bold text-slate-500">Цена</div>
                    <div class="px-4 w-1/4 font-bold text-slate-500"></div>
                </div>
                <div
                    v-for="(product, k) in products"
                    class="flex flex-row w-full items-center border-t border-slate-200 py-2"
                >
                    <div class="px-4 w-1/2">{{ product.name }}</div>
                    <div class="px-4 w-1/4">{{ product.price }}</div>
                    <div class="px-4 w-1/4">
                        <PrimaryButton
                            @click="addProduct(product)"
                            type="button"
                            >Выбрать</PrimaryButton
                        >
                    </div>
                </div>
            </div>
        </Modal>
        <div>
            <InputLabel for="date" value="Дата" />

            <TextInput
                id="date"
                type="text"
                class="mt-1 block w-full"
                v-model="date"
                mask="##.##.####"
                @blur="form.date = +new Date($event.target.value) / 1000"
                required
                autofocus
            />

            <InputError class="mt-2" :message="form.errors.date" />
        </div>

        <div>
            <InputLabel for="phone" value="Телефон" />

            <TextInput
                id="phone"
                type="text"
                class="mt-1 block w-full"
                v-model="form.phone"
                mask="+# ### ### ## ##"
                required
            />

            <InputError class="mt-2" :message="form.errors.phone" />
        </div>

        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
            />

            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div>
            <InputLabel for="address" value="Адрес" />

            <TextInput
                id="address"
                type="text"
                class="mt-1 block w-full"
                v-model="form.address"
                @blur="updateMap($event.target.value)"
                required
            />

            <InputError class="mt-2" :message="form.errors.address" />

            <div id="yandex-map" class="w-96 h-96 mt-4"></div>
        </div>

        <div>
            <InputLabel for="summ" value="Товары" />
            <div class="bg-slate-100 mt-2 rounded overflow-hidden">
                <div
                    class="flex flex-row w-full items-center border-t border-slate-200 py-2"
                >
                    <div class="px-4 w-1/2 font-bold text-slate-500">
                        Название
                    </div>
                    <div class="px-4 w-1/5 font-bold text-slate-500">Цена</div>
                    <div class="px-4 w-1/4 font-bold text-slate-500">
                        Количество
                    </div>
                    <div class="px-4 w-1/5"></div>
                </div>
                <div
                    v-for="(product, k) in form.products"
                    class="flex flex-row w-full items-center border-t border-slate-200 py-2"
                >
                    <div class="px-4 w-1/2">{{ product.name }}</div>
                    <div class="px-4 w-1/5">{{ product.price }}</div>
                    <div class="px-4 w-1/4">
                        <TextInput
                            type="text"
                            class="mt-1 block w-full"
                            v-model="product.pivot.qnt"
                            required
                        />
                    </div>
                    <div class="px-4 w-1/5">
                        <PrimaryButton
                            class="bg-red-700 hover:bg-red-500 focus:bg-red-500 active:bg-red-500"
                            @click="remove(k)"
                            type="button"
                        >
                            x
                        </PrimaryButton>
                    </div>
                </div>
                <div
                    class="flex w-full justify-center border-t border-slate-200 py-2"
                >
                    <PrimaryButton
                        @click="selectProductModal = true"
                        type="button"
                        >Добавить</PrimaryButton
                    >
                </div>
            </div>
        </div>

        <div>
            <InputLabel for="summ" value="Сумма" />

            <TextInput
                id="summ"
                type="text"
                disabled="disabled"
                class="mt-1 block w-full opacity-50"
                :value="
                    form.products.reduce((a, c) => a + c.price * c.pivot.qnt, 0)
                "
                required
            />

            <InputError class="mt-2" :message="form.errors.summ" />
        </div>
    </div>
</template>

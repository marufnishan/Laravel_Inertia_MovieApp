<template>
    <AdminLayout title="Dashboard">
        <template #header>
                Edit Tv Show
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto">
                <section class="container mx-auto p-6 font-mono">
                    <div class="w-full flex mb-4 p-2">
                        <Link :href="route('admin.tv-shows.index')" class="
                bg-green-500
                hover:bg-green-700
                text-white
                px-4
                py-2
                rounded-lg
              ">
                        Tv Index</Link>
                    </div>

                    <div class="
              w-full
              mb-8
              p-6
              overflow-hidden
              bg-white
              rounded-lg
              shadow-lg
            ">
                        <form @submit.prevent="submitTvShow">
                            <div>
                                <JetLabel for="name" value="Name" />
                                <JetInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" 
                                    autofocus autocomplete="name" />
                                    <div class="text-sm text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                            </div>

                            <div class="mt-4">
                                <JetLabel for="poster_path" value="Poster" />
                                <JetInput id="poster_path" v-model="form.poster_path" type="text"
                                    class="mt-1 block w-full"  />
                                     <div class="text-sm text-red-500" v-if="form.errors.poster_path">{{ form.errors.poster_path }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">

                                <JetButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Update
                                </JetButton>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
    import AdminLayout from "../../Layouts/AdminLayout.vue";
    import {
        Head,
        Link,
        useForm
    } from '@inertiajs/inertia-vue3';
    import JetButton from "@/Jetstream/Button.vue";
    import JetInput from "@/Jetstream/Input.vue";
    import JetLabel from "@/Jetstream/Label.vue";
    import {
        ref,
        watch,
        defineProps
    } from "vue";
    const props = defineProps({
        tvShow: Object,
    });

    const form = useForm({
        name: props.tvShow.name,
        poster_path: props.tvShow.poster_path,
    });

    function submitTvShow(){
        form.put('/admin/tv-shows/' + props.tvShow.id);
    }

</script>

<style>
</style>

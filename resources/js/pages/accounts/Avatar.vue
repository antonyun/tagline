<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as accountsIndex } from '@/routes/accounts';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

import { Account } from '@/types/models/Account'
import { Media } from '@/types/models/Media'

const props = defineProps<{
    account: Account,
    images: Media[],
}>();

const getImageURL = (image: Media): string => new URL(`/storage/media/${image.filename}`, document.baseURI).href

const submit = (image: Media) => {
    const form = useForm({
        media_id: image.id,
    })

    form.patch(`/accounts/${props.account.id}/avatar`), {
        onFinish: () => form.reset(),
    }
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Accounts',
        href: accountsIndex().url,
    },
    {
        title: 'Create',
        href: accountsIndex().url,
    },
];

</script>

<template>
    <Head title="Create Account" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div class="grid grid-cols-5 gap-2">
                            <div v-for="image in images" @click="submit(image)">
                                <img class="w-full aspect-square object-cover" :src="getImageURL(image)">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
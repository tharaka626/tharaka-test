<template>
    <div class="flex flex-col overflow-x-auto">
        <div class="">
            <table class="min-w-full text-sm text-center text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2 border-gray-400 text-center">
                    <slot name="thead" />
                </thead>
                <tbody>
                    <slot name="tbody" />

                </tbody>
            </table>
        </div>
    </div>
</template>

<template>
    <section class="mb-40 mt-20">
        <div
            class="px-6 py-12 md:px-12 bg-gray-50 text-gray-800 text-center lg:text-left"
        >
            <div class="container mx-auto xl:px-32">
                <div class="grid lg:grid-cols-2 gap-12 flex items-center">
                    <div class="mt-12 lg:mt-0">
                        <h1
                            class="text-5xl md:text-6xl xl:text-7xl font-bold tracking-tight mb-12"
                        >
                            {{ context.hero.title }} <br /><span
                                class="text-blue-600"
                            >
                                {{ context.hero.span }}
                            </span>
                        </h1>
                        <blockquote class="text-gray-500 text-clip mb-12">
                            {{ context.hero.quote }}
                        </blockquote>
                        <a
                            class="inline-block px-7 py-3 mr-2 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                            data-mdb-ripple="true"
                            data-mdb-ripple-color="light"
                            href="#!"
                            role="button"
                            >Get started</a
                        >
                    </div>
                    <div class="mb-12 lg:mb-0">
                        <img
                            :src="context.hero.image"
                            class="w-full rounded-lg shadow-lg"
                            alt="hero-bg"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            context: window?.context,
        };
    },
};
</script>

<template>
  <footer class="p-4 bg-white shadow md:px-6 md:py-8 dark:bg-gray-900 max-h-[40vh]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center sm:justify-between">
        <a href="/"><img class="h-8 w-24" src="./../../assets/Logo-MUST-400px-2.png" alt="Logo Must" /></a>
        <ul class="flex flex-wrap items-center mb-6 text-sm text-gray-500 sm:mb-0 dark:text-gray-400">
          <router-link v-for="item in footerNavigation" :key="item.name" :to="item.to"
            active-class="bg-gray-900 text-white" :class="[this.$route.name === item.to.name
            ? '' : 'text-gray-300 hover:bg-gray-700 hover:text-white',
            'block px-3 py-2 rounded-md text-base font-medium']">
            {{ item.name }}
          </router-link>
        </ul>
      </div>
      <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
      <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a
          href="https://github.com/WinstonSmith13/" class="hover:underline">Medy Krouk™</a>. Tous droits réservés.
      </span>
    </div>
  </footer>
</template>

<script setup >

const footerNavigation = [
  { name: 'À propos', to: { name: 'About' } },
  { name: 'Mentions légales', to: { name: 'LegalInformation' } },
]

</script>

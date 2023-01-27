<template>
  <div class="grid grid-flow-row h-screen ">
    <!-- NavBar -->
    <NavigationBarTemplate />
    <router-view />
    <Notification />
    <!-- Footer -->
    <FooterTemplate />
  </div>
</template>

<script setup>

import Notification from './Notification.vue';
import FooterTemplate from './template/FooterTemplate.vue';
import NavigationBarTemplate from './template/NavigationBarTemplate.vue';

</script>

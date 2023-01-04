<template>
  <div>
    <img class="mx-auto h-12 w-auto" src="./../assets/Logo-MUST-400px-2.png"
      alt="Your Company" /> 
    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Inscrivez-vous</h2>
    <p class="mt-2 text-center text-sm text-gray-600">
      ou bien
      {{ ' ' }}
      <router-link :to="{ name: 'Login' }" class="font-medium text-primary hover:text-yellow-500"> Connectez-vous
      </router-link>
    </p>
  </div>
  <form class="mt-8 space-y-6" @submit="register">
    <div v-if="Object.keys(errors).length" class="flex items-center justify-between py-3 px-5 bg-red-500 text-white rounded-md">
      <div v-for="(field, i) of Object.keys(errors)" :key="i">
        <div v-for="(error, ind) of errors[field] || []" :key="ind">
          * {{ error }}
        </div>
      </div>
    </div>

    <input type="hidden" name="remember" value="true" />
    <div class="-space-y-px rounded-md shadow-sm">
      <div>
        <label for="fullname" class="sr-only">Prénom Nom</label>
        <input id="fullname" name="name" type="text" autocomplete="name" required="" v-model="user.name"
          class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm"
          placeholder="Prénom Nom" />
      </div>
      <div>
        <label for="email-address" class="sr-only">Email</label>
        <input id="email-address" name="email" type="email" autocomplete="email" required=""  v-model="user.email"
          class="relative block w-full appearance-none rounded-none border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm"
          placeholder="Email" />
      </div>
      <div>
        <label for="password" class="sr-only">Mot de passe</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required="" v-model="user.password" 
          class="relative block w-full appearance-none rounded-none  border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm"
          placeholder="Mot de passe" />
      </div>
      <div>
        <label for="password_confirmation" class="sr-only">Confirmation du mot de passe</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="current-password_confirmation" required="" v-model="user.password_confirmation"
          class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-primary focus:outline-none focus:ring-primary sm:text-sm"
          placeholder="Confirmation du mot de passe" />
      </div>
    </div>
    <div>
      <button type="submit"
        class="group relative flex w-full justify-center rounded-md border border-transparent bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <LockClosedIcon class="h-5 w-5 text-white group-hover:text-primary" aria-hidden="true" />
        </span>
        Inscription
      </button>
    </div>
  </form>
</template>

<script setup>

import { LockClosedIcon } from '@heroicons/vue/20/solid'
import store from '../store';
import {useRouter} from 'vue-router';
import { ref } from 'vue';

const router = useRouter();

const user = {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
};

const errors = ref({});

function register(ev) {
  ev.preventDefault();
  store
    .dispatch('register', user)
    //une fois que l'inscription a été faite il faut amener l'utilisateur ailleurs. 
    .then((res)=> {
      router.push({
        name: 'Dashboard'
      })
    })
    .catch(err => {
      if (err.response.status === 422){
        errors.value = err.response.data.errors
      }
    } )
}

</script>

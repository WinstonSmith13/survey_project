<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl text-black font-bold">Formulaires</h1>
        <router-link :to="{ name: 'SurveyCreate' }" class="
          py-2
          px-3
          text-white
          bg-emerald-500
          rounded-md
          hover:bg-emerald-600">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-4 h-4 -mt-1 inline-block">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>

          Ajouter un formulaire
        </router-link>
      </div>
    </template>


    <div>
      <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
        <!--We need to pass survey object as a prop-->
        <!--Whenever delete is called we need to call deleteSurvey (mutation)-->
        <SurveyListItem
          @delete="deleteSurvey(survey)"
          v-for="(survey, ind) in surveys.data"
          :key="survey.id"
          :survey="survey"
          class="opacity-0 animate-fade-in-down "
          :style="{ animationDelay: `${ind * 0.1}s` }" />

      </div>
    </div>
  </PageComponent>
</template>

<script setup>
import store from '../store';
import { computed } from 'vue';
import PageComponent from '../components/PageComponent.vue';
import SurveyListItem from '../components/SurveyListItem.vue';
import { useRouter } from "vue-router";
const router = useRouter();

const surveys = computed(() => store.state.surveys);
store.dispatch("getSurveys");

function deleteSurvey(survey) {
  if (confirm('Êtes-vous sûre de vouloir supprimer ce formulaire?')) {
    store.dispatch("deleteSurvey", survey.id).then(() => {
      store.dispatch("getSurveys");
    })
  }
}
</script>

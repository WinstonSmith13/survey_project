<template>
  <PageComponent title="Dashboard">
    <!-- <pre>{{ loading }}</pre>
    <pre>{{ data}}</pre> -->
    <div v-if="loading" class="flex justify-center ">
      <div role="status">
        <svg class="inline mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-green-500"
          viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
            fill="currentColor" />
          <path
            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
            fill="currentFill" />
        </svg>
        <span class="sr-only">Chargement...</span>
      </div>
    </div>
    <!--Responsive for the mobile-->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700 ">
      <!--Total Surveys-->

      <div class="bg-white shadow-md p-3 text-center flex flex-col animate-fade-in-down order-1 lg:order-2"
        style="animation-delay: 0.1s">
        <h3 class="text-2xl font-semibold">Nombre total de sondages</h3>
        <div class="text-8xl pb-4 font-semibold flex-1 flex items-center justify-center">
          {{ data.totalSurveys }}
        </div>


      </div>

      <!--Total Answers-->

      <div class="bg-white shadow-md p-3 text-center flex flex-col order-2 lg:order-4 animate-fade-in-down m-4"
        style="animation-delay: 0.2s">
        <h3 class="text-2xl font-semibold">Réponses totales</h3>
        <div class="text-8xl pb-4 font-semibold flex-1 flex items-center justify-center">
          {{ data.totalAnswers }}
        </div>

      </div>

      <!--Latest Surveys-->
      <div class="row-span-2 animate-fade-in-down order-3 lg:order-1 bg-white shadow-md p-4">
        <h3 class="text-2xl font-semibold">Sondage récent</h3>
        <div v-if="data.latestSurvey">
          <img v-if="data.latestSurvey.image_url" :src="data.latestSurvey.image_url" class="w-[240px] mx-auto"
            alt="survey image" />
          <img v-else src="./../assets/Logo-MUST-400px-2.png" class="w-[240px] mx-auto" alt="logo must" />

          <h3 class="font-bold text-xl mb-3">{{ data.latestSurvey.title }}</h3>
          <div class="flex justify-between text-sm mb-1">
            <div>Date de création:</div>
            <div>{{ data.latestSurvey.created_at }}</div>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <div>Date d'expiration:</div>
            <div>{{ data.latestSurvey.expire_date }}</div>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <div>Status:</div>
            <div>{{ data.latestSurvey.status ? "Active" : "Draft" }}</div>
          </div>
          <!-- <div class="flex justify-between text-sm mb-1">
            <div>Questions:</div>
            <div>{{ data.latestSurvey.questions}}</div>
          </div> -->
          <div class="flex justify-between text-sm mb-3">
            <div>Réponses:</div>
            <div>{{ data.latestSurvey.answers }}</div>
          </div>
          <div class="flex justify-between">
            <router-link :to="{ name: 'SurveyView', params: { id: data.latestSurvey.id } }"
              class="flex py-2 px-4 border border-transparent text-sm rounded-md  text-primary hover:bg-primary hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
              Modifier le formulaire
            </router-link>
            <router-link :to="{ name: 'SurveyAnswerView', params: { id: data.latestSurvey.id } }"
              class="flex py-2 px-4 border border-transparent text-sm rounded-md  text-primary hover:bg-primary hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd"
                  d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                  clip-rule="evenodd" />
              </svg>
              Afficher les réponses
            </router-link>





          </div>


        </div>
        <div v-else class="text-gray-600 text-center py-16">
          Vous n'avez pas encore de formulaire.
        </div>

      </div>



      <!--Latest answers-->

      <div class="bg-white shadow-md p-3 row-span-2 order-4 lg:order-3 animate-fade-in-down"
        style="animation-delay: 0.3s">

        <div class="flex justify-between items-center mb-3 px-2">
          <h3 class="text-2xl font-semibold">Tous vos sondages</h3>

        </div>

        <div v-for="answer of data.allSurveys" :key="answer.id" class="block p-2">
          <div class="font-semibold">{{answer.title}}</div>


          <router-link :to="{ name: 'SurveyAnswerView', params: { id: answer.id } }"
              class="flex py-2 px-4 border border-transparent text-sm rounded-md  text-primary hover:bg-primary hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd"
                  d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                  clip-rule="evenodd" />
              </svg>
              Afficher les réponses
            </router-link>
          </div>


      </div>
    </div>



  </PageComponent>

</template>

<script setup>
import PageComponent from '../components/PageComponent.vue';
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

//computed properties from vue state store.
const loading = computed(() => store.state.dashboard.loading);
const data = computed(() => store.state.dashboard.data);

store.dispatch("getDashboardData");

</script>

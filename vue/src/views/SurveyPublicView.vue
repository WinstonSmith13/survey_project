<template>
  <div class="py-5 px-8 ">
    <div
      v-if="loading"
      class="flex justify-center items-center mt-80"
    >
      <div role="status">
        Chargement ...

        <svg
          class="inline mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-green-500"
          viewBox="0 0 100 101"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
            fill="currentColor"
          />
          <path
            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
            fill="currentFill"
          />
        </svg>
        <span class="sr-only">Chargement...</span>
      </div>
    </div>

    <div v-else>
      <img
            
            src="./../assets/Logo-MUST-400px-2.png"
            alt="logo must"
            class="object-fit h-10 w-23 mb-6"
          >
          <form
      
      @submit.prevent="submitSurvey"
      class="container mx-auto bg-gray-100  rounded-md p-4 shadow-md"
    >
      <div class="grid grid-cols-6 items-center">
        <div class="mr-4">
          <img
            v-if="survey.image_url"
            :src="survey.image_url"
            alt="image survey"
            class="shadow-md"
          >
          <img
            v-else
            src="./../assets/Logo-MUST-400px-2.png"
            alt="logo must"
            class="shadow-md"
          >
        </div>
        <div class="col-span-5">
          <!--Title of the survey-->
          <h1 class="text-3xl mb-3">
            {{ survey.title }}
          </h1>
          <p
            class="text-gray-500 text-sm"
            v-html="survey.description"
          />
        </div>
      </div>
      <!--For the display of an element if the form has already been completed-->
      <div
        v-if="surveyFinished"
        class="grid justify-center mt-8 rounded-md py-6 px-6 bg-emerald-400 text-white w-[600px] mx-auto"
      >
        <div class="text-xl mb-3 font-semibold ">
          Réponse bien enregistrée.
        </div>
        <button
          @click="submitAnotherResponse"
          type="button"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
        >
          Soumettre une autre réponse
        </button>
      </div>
      <!--Otherwise we display the questions-->
      <div v-else>
        <!--Wrapper for the questions-->
        <hr class="my-3">
        <div
          v-for="(question, ind) of survey.questions"
          :key="question.id"
        >
          <!--Using the question Viewer Component-->
          <QuestionViewer
            v-model="answers[question.id]"
            :question="question"
            :index="ind"
          />
        </div>
        <button
          type="submit"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
        >
          Soumettre
        </button>
      </div>
    </form>
    </div>
    
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from 'vuex'
import QuestionViewer from '../components/viewer/QuestionViewer.vue'

const route = useRoute()
const store = useStore()

const loading = computed(() => store.state.currentSurvey.loading)

// Retrieval of survey data from the store.

const survey = computed(() => store.state.currentSurvey.data)

// Creation of a variable for displaying the message if the form has already been filled.
const surveyFinished = ref(false)

// Creation of a variable for the answers. Création d'une variable pour les réponses.
const answers = ref({})

// We make a request to retrieve the data from the back through the VueX store.
// And this using the slug that we placed in the url. It's a good way to do SEO.
store.dispatch('getSurveyBySlug', route.params.slug)

// Creation of the SubmitSurvey function.

function submitSurvey () {
  // We make a JSON Passing the data.
  console.log(JSON.stringify(answers.value, undefined, 2))
  // Make a request to save the answer. Avec l'action SaveSurveyAnswer.
  // We pass an object.
  store.dispatch(
    'saveSurveyAnswer', {
      surveyId: survey.value.id,
      answers: answers.value
    }).then((response) => {
    // Set surveyFinished value to true if response status is 201.
    if (response.status === 201) {
      surveyFinished.value = true
    };
  })
}

function submitAnotherResponse () {
  answers.value = {}
  surveyFinished.value = false
}
</script>

<style>
</style>

<template>
  <PageComponent>
    <template #header>
    
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? model.title : "Créer un formulaire" }}
        </h1>
        <button
          v-if="route.params.id"
          type="button"
          @click="deleteSurvey()"
          class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 -mt-1 inline-block"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          Supprimer le formulaire
        </button>
      </div>
    </template>
    <div
      v-if="surveyLoading"
      class="flex justify-center"
    >
      <div role="status">
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
    <form
      v-else
      @submit.prevent="saveSurvey"
      class="animate-fade-in-down"
    >
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <!--Survey Fields-->
        <div class="px-4 py-5 bg-white space-y-6 sm:-p-6">
          <!--Image-->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              Image
            </label>
            <div class="mt-1 flex items-center">
              <img
                v-if="model.image_url"
                :src="model.image_url"
                :alt="model.image"
                class="w-64 h-48 object-cover"
              >
              <span
                v-else
                class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-[80%] w-[80%] text-gray-300"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                  />
                </svg>
              </span>
              <button
                type="button"
                class="ml-5 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
              >
                <input
                  type="file"
                  @change="onImageChoose"
                  class="
                                opacity-100
                                cursor-pointer"
                >
              </button>
            </div>
          </div>
          <!--/Image-->
          <!--Title-->
          <div>
            <label
              for="title"
              class="block text-sm font-medium text-gray-700"
            >
              Titre
            </label>
            <input
              type="text"
              name="title"
              id="title"
              v-model="model.title"
              autocomplete="survey_title"
              class=" mt-1 focus:ring-primary focus=border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            >
          </div>
          <!--/Title-->
          <!--Description-->
          <div class="mt-1">
            <label
              for="description"
              class="block text-sm font-medium text-gray-700"
            >
              Description
            </label>
            <textarea
              name="description"
              id="description"
              rows="3"
              v-model="model.description"
              autocomplete="survey_description"
              class=" mt-1 focus:ring-primary focus=border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <!--/Description-->
          <!--Expire_Date-->
          <div class="mt-1">
            <label
              for="expire_date"
              class="block text-sm font-medium text-gray-700"
            >
              Date de fin
            </label>
            <input
              type="date"
              name="expire_date"
              id="expire_date"
              v-model="model.expire_date"
              autocomplete="survey_expire_date"
              class=" mt-1 focus:ring-primary focus=border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            >
          </div>
          <!--/Expire_Date-->
          <!--Status-->
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                type="checkbox"
                name="status"
                id="status"
                v-model="model.status"
                autocomplete="survey_status"
                class="focus:ring-primary h-4 w-4 shadow-sm  border-gray-300 rounded"
              >
            </div>
            <div class="ml-3 text-sm">
              <label
                for="status"
                class="font-medium text-gray-700"
              >Actif</label>
            </div>
          </div>
          <!--/Status-->
        </div>
        <!--/Survey Fields-->
        <!--Question-->
        <div class="px-4 py-5 bg-white space-y-6 sm:-p-6">
          <h3 class="text-2xl font-medium flex items-center justify-between">
            Questions
            <button
              type="button"
              @click="addQuestion()"
              class="flex items-center text-sm py-1 px-4 rounded-md text-white bg-gray-600 hover:bg-gray-700"
            >
              + Ajouter une question
            </button>
          </h3>
          <div
            v-if="!model.questions.length"
            class="text-center text-gray-600"
          >
            Vous n'avez pas ajouté de questions.
          </div>
          <div
            v-for="(question, index) in model.questions"
            :key="question.id"
          >
            <!--On ecoute les changements dans l'éditeur de questions grace aux emits.-->
            <QuestionEditorVue
              :question="question"
              :index="index"
              @change="questionChange"
              @addQuestion="addQuestion"
              @deleteQuestion="deleteQuestion"
            />
          </div>
        </div>
        <!--/Question-->
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <router-link to="/Surveys">
            <button
              type="button"
              class="inline-flex justify-center rounded-md border border-gray-300 bg-transparent py-2 px-4 text-sm font-medium text-black hover:text-white shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 mx-2"
            >
              Retour
            </button>
          </router-link>

          <button
            type="submit"
            class="inline-flex justify-center rounded-md border border-transparent bg-primary py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
          >
            Créer
          </button>
        </div>
      </div>
    </form>
  </PageComponent>
</template>

<script setup>
import { v4 as uuidv4 } from 'uuid'
import store from '../store'
import { useRoute, useRouter } from 'vue-router'
import { ref, watch, computed } from 'vue'
import PageComponent from '../components/PageComponent.vue'
import QuestionEditorVue from '../components/editor/QuestionEditor.vue'

const route = useRoute()
const router = useRouter()

const surveyLoading = computed(() => store.state.currentSurvey.loading)

// Creation d'un formulaire vide pour l'admistrateur. Ce sont les champs qu'un formulaire doit avoir.
const model = ref({
  title: null,
  image_url: null,
  status: false,
  description: null,
  image: null,
  expire_date: null,
  questions: []
})
// Watch to current survey data change and when this happens we update local model
watch(
  () => store.state.currentSurvey.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: newVal.status !== 'draft'
    }
  }
)
//Pour récupérer la route vers le survey avec l'id correspondant.
if (route.params.id) {
  store.dispatch('getSurvey', route.params.id)
}

function onImageChoose (ev) {
  const file = ev.target.files[0]
  const reader = new FileReader()
  reader.onload = () => {
    // The field to send on backend and apply validations
    model.value.image = reader.result
    // The field to display here
    model.value.image_url = reader.result
    ev.target.value = ''
  }
  reader.readAsDataURL(file)
}

function addQuestion (index) {
  const newQuestion = {
    id: uuidv4(),
    type: 'text',
    question: '',
    description: null,
    data: {}
  }
  // Ajout des questions dans le tableau de Questions
  model.value.questions.splice(index, 0, newQuestion)
}

// l'idée est supprimer les questions qui n'ont pas de questions.id

function questionChange (question) {
  if (question.data.options) {
    question.data.options = [...question.data.options]
  }
  model.value.questions = model.value.questions.map((q) => {
    if (q.id === question.id) {

      return JSON.parse(JSON.stringify(question))
    }
    return q 
  })
}

function saveSurvey () {
  store.dispatch('saveSurvey', model.value).then(({ data }) => {
    store.commit('notify', {
      type: 'success',
      message: 'Le formulaire a bien été créé.'
    })
    router.push({
      name: 'Surveys',
      params: { id: data.data.id }
    })
  })
};

function deleteSurvey () {
  if (
    confirm(
      'Êtes-vous sûre de vouloir supprimer le formulaire ?'
    )
  ) {
    store.dispatch('deleteSurvey', model.value.id).then(() => {
      router.push({
        name: 'Surveys'
      })
    })
  }
};

</script>

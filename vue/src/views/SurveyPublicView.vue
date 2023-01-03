<template>
    <div class="py-5 px-8 ">
        <div v-if="loading" class="flex justify-center items-center mt-80">
            <div role="status">
                Chargement ...
                
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

        <!--Formulaire-->
        <form v-else @submit.prevent="submitSurvey" class="container mx-auto bg-gray-100 mt-40 rounded-md p-4 shadow-md">
            <div class="grid grid-cols-6 items-center">
                <div class="mr-4">
                    <img :src="survey.image_url" alt="image survey" class="shadow-md" />
                </div>
                <div class="col-span-5">
                    <!--Title of the survey-->
                    <h1 class="text-3xl mb-3"> {{ survey.title }} </h1>
                    <p class="text-gray-500 text-sm" v-html="survey.description"></p>
                </div>
            </div>
            <!--Pour l'affichage d'un element si le formulaire a déjà été effectué-->
            <div v-if="surveyFinished" class="py-8 px-6 bg-emerald-400 text-white w-[600px] mx-auto">
                <div class="text-xl mb-3 font-semibold ">Réponse bien enregistrée.</div>
                <button @click="submitAnotherResponse" type="button"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Soumettre une autre réponse
                </button>
            </div>
            <!--Sinon on affiche les questions-->
            <div v-else>
                <!--Wrapper for the questions-->
                <hr class="my-3" />
                <div v-for="(question, ind) of survey.questions" :key="question.id">
                    <!--Using the question Viewer Component-->
                    <QuestionViewer v-model="answers[question.id]" :question="question" :index="ind" />
                </div>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Soumettre
                </button>
            </div>
        </form>

    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import QuestionViewer from '../components/viewer/QuestionViewer.vue';

const route = useRoute();
const store = useStore();

//Pour l'affichage du chargement. 

const loading = computed(() => store.state.currentSurvey.loading);

// récupération des données des formulaires. 

const survey = computed(() => store.state.currentSurvey.data);

// Création d'une variable pour l'affichage du message si le formulaire a déjà été rempli. 
const surveyFinished = ref(false);

// Création d'une variable pour les réponses. 
const answers = ref({});

//On fait une requete pour recupérer les données depuis le back à travers le store de vueX.
//Et cela à l'aide du slug que l'on a placé dans l'url. C'est une bonne manière de faire du reférencement.  
store.dispatch("getSurveyBySlug", route.params.slug);

//Création de la function SubmitSurvey.

function submitSurvey() {
    console.log(JSON.stringify(answers.value, undefined, 2));
    //make a request to save the answer. Avec l'action SaveSurveyAnswer.
    //We pass an object. 
    store.dispatch(
        "saveSurveyAnswer", {
        surveyId: survey.value.id,
        answers: answers.value,
    }).then((response) => {
        //Si on a une réponse 201 alors cela signifie que l'on a bien enregistré le formulaire dans le back. 
        if (response.status === 201) {
            surveyFinished.value = true;
        };
    });
}

function submitAnotherResponse() {
    answers.value = {};
    surveyFinished.value = false;
}



</script>


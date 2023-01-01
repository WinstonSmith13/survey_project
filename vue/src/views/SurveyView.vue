<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ model.id ? model.title : "Créer un formulaire" }}
                </h1>
            </div>
        </template>
        <!-- <pre>{{ model }}</pre> -->
        <form @submit.prevent="saveSurvey">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <!--Survey Fields-->
                <div class="px-4 py-5 bg-white space-y-6 sm:-p-6">
                    <!--Image-->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <div class="mt-1 flex items-center">
                            <img v-if="model.image_url" :src="model.image_url" :alt="model.image"
                                class="w-64 h-48 object-cover" />
                            <span v-else class=" 
                            flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-[80%] w-[80%] text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </span>
                            <button type="button"
                                class="ml-5 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <input type="file" @change="onImageChoose" class="
                                opacity-100
                                cursor-pointer" />

                            </button>

                        </div>
                    </div>
                    <!--/Image-->
                    <!--Title-->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Titre
                        </label>
                        <input type="text" name="title" id="title" v-model="model.title" autocomplete="survey_title"
                            class=" mt-1 focus:ring-primary focus=border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <!--/Title-->
                    <!--Description-->
                    <div class="mt-1">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3" v-model="model.description"
                            autocomplete="survey_description"
                            class=" mt-1 focus:ring-primary focus=border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                    <!--/Description-->
                    <!--Expire_Date-->
                    <div class="mt-1">
                        <label for="expire_date" class="block text-sm font-medium text-gray-700">
                            Date de fin
                        </label>
                        <input type="date" name="expire_date" id="expire_date" v-model="model.expire_date"
                            autocomplete="survey_expire_date"
                            class=" mt-1 focus:ring-primary focus=border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <!--/Expire_Date-->
                    <!--Status-->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="status" id="status" v-model="model.status"
                                autocomplete="survey_status"
                                class="focus:ring-primary h-4 w-4 shadow-sm  border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="status" class="font-medium text-gray-700">Actif</label>
                        </div>
                    </div>
                    <!--/Status-->
                </div>
                <!--/Survey Fields-->
                <!--Question-->
                <div class="px-4 py-5 bg-white space-y-6 sm:-p-6">
                    <h3 class="text-2xl font-medium flex items-center justify-between">
                        Questions
                        <button type="button" @click="addQuestion()"
                            class="flex items-center text-sm py-1 px-4 rounded-md text-white bg-gray-600 hover:bg-gray-700">
                            + Ajouter une question
                        </button>
                    </h3>
                    <div v-if="!model.questions.length" class="text-center text-gray-600">
                        Vous n'avez pas ajouté de questions.
                    </div>
                    <div v-for="(question, index) in model.questions" :key="question.id">
                        <!--On ecoute les changements dans l'éditeur de questions grace aux emits.-->
                        <QuestionEditor :question="question" :index="index" @changes="questionChange"
                            @addQuestion="addQuestion" @deleteQuestion="deleteQuestion" />

                    </div>

                </div>
                <!--/Question-->


                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">

                    <router-link to="/Surveys">
                        <button type="button"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-transparent py-2 px-4 text-sm font-medium text-black hover:text-white shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 mx-2">
                            Annuler
                        </button>
                    </router-link>

                    <button type="submit"
                        class="inline-flex justify-center rounded-md border border-transparent bg-primary py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">Créer</button>
                </div>

            </div>

        </form>
    </PageComponent>
</template>

<script setup>
import { v4 as uuidv4 } from "uuid";
import store from '../store';
import { useRoute } from 'vue-router';
import { useRouter } from "vue-router";
import { ref } from 'vue';
import PageComponent from '../components/PageComponent.vue';
import QuestionEditor from '../components/editor/QuestionEditor.vue';

const route = useRoute();
const router = useRouter();




//Creation d'un formulaire vide pour l'admistrateur. Ce sont les champs qu'un formulaire doit avoir. 
let model = ref({
    title: null,
    status: false,
    description: null,
    image: null,
    expire_date: null,
    questions: [],
});

if (route.params.id) {
    model.value = store.state.surveys.find(
        (s) => s.id === parseInt(route.params.id)
    );
}

function onImageChoose(ev) {
    const file = ev.target.files[0];
    const reader = new FileReader();
    reader.onload = () => {
        // The field to send on backend and apply validations
        model.value.image = reader.result;
        // The field to display here
        model.value.image_url = reader.result;
        ev.target.value = "";
    };
    reader.readAsDataURL(file);
}

function addQuestion(index) {
    const newQuestion = {
        id: uuidv4(),
        type: "text",
        question: "",
        description: null,
        data: {},
    };
    //Ajout des questions dans le tableau de Questions
    model.value.questions.splice(index, 0, newQuestion);
}

//l'idée est supprimer les questions qui n'ont pas de questions.id
function deleteQuestion(question) {
    model.value.questions = model.value.questions.filter((q) => q.id !== question.id);
}

function questionChange(question) {
    // Important to explicitelly assign question.data.options, because otherwise it is a Proxy object
    // and it is lost in JSON.stringify()
    if (question.data.options) {
        question.data.options = [...question.data.options];
    }
    model.value.questions = model.value.questions.map((q) => {
        if (q.id === question.id) {
            return JSON.parse(JSON.stringify(question));
        }
        return q;
    });
}

function saveSurvey() {
    store.dispatch("saveSurvey", model.value).then(({ data }) => {
        router.push({
            name: "SurveyView",
            params: { id: data.data.id },
        });
    });
};




</script>

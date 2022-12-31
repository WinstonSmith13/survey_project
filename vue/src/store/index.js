import { createStore } from "vuex";
import axiosClient from "../axios";

const tmpSurveys = [
  {
    id: 100,
    title: "Must session du Lundi",
    slug: "Must-session-du-Lundi",
    status: "draft",
    image: "https://picsum.photos/640/360",
    description: "Ceci est un formulaire de présence pour la prochaine séance du lundi.",
    created_at: "2022-12-22 18:00:00",
    updated_at: "2022-12-22 18:00:00",
    expire_date: "2022-12-29 18:00:00",
    questions: [
      {
        id: 1,
        type: "select",
        question: "Etes-vous présent pour la session du lundi 2 janvier 2023?",
        description: null,
        data: {
          option: [
            //utilisation de uuid pour la facilité de création et aussi pour eviter les erreurs de répétitions d'id.
            { uuid: "f8af96f2-1d80-4632-9e9e-b560670e52ea", text: "Oui" },
            { uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440", text: "Non" },

          ],
        },
      },
      {
        id: 2,
        type: "checkbox",
        question: "Pour quelles séances allez-vous etre présent pour la semaine du 2 janvier 2023?",
        description: "Planification de la semaine du 2 janvier 2023",
        data: {
          option: [
            //utilisation de uuid pour la facilité de création et aussi pour eviter les erreurs de répétitions d'id.
            { uuid: "f8af96f2-1d80-4632-9e9e-b560670e52ea", text: "Lundi" },
            { uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440", text: "Mercredi" },
            { uuid: "b5c09733-a49e-460a-ba8a-526863010729", text: "Vendredi" },
            { uuid: "2abf1cee-f5fb-427c-a220-b5d159ad6513", text: "Samedi" },

          ],
        },
      },
      {
        id: 4,
        type: "checkbox",
        question: "Pour quelles séances allez-vous etre présent pour la semaine du 9 janvier 2023?",
        description: "Planification de la semaine du 9 janvier 2023",
        data: {
          option: [
            //utilisation de uuid pour la facilité de création et aussi pour eviter les erreurs de répétitions d'id.
            { uuid: "f8af96f2-1d80-4632-9e9e-b560670e52ea", text: "Lundi" },
            { uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440", text: "Mercredi" },
            { uuid: "b5c09733-a49e-460a-ba8a-526863010729", text: "Vendredi" },
            { uuid: "2abf1cee-f5fb-427c-a220-b5d159ad6513", text: "Samedi" },

          ],
        },
      },
      {
        id: 5,
        type: "checkbox",
        question: "Pour quelles séances allez-vous etre présent pour la semaine du 16 janvier 2023?",
        description: "Planification de la semaine du 16 janvier 2023",
        data: {
          option: [
            //utilisation de uuid pour la facilité de création et aussi pour eviter les erreurs de répétitions d'id.
            { uuid: "f8af96f2-1d80-4632-9e9e-b560670e52ea", text: "Lundi" },
            { uuid: "201c1ff5-23c9-42f9-bfb5-bbc850536440", text: "Mercredi" },
            { uuid: "b5c09733-a49e-460a-ba8a-526863010729", text: "Vendredi" },
            { uuid: "2abf1cee-f5fb-427c-a220-b5d159ad6513", text: "Samedi" },

          ],
        },
      },
      {
        id: 6,
        type: "text",
        question: "Quel exercice souhaitez-vous faire pour le prochain entrainement?",
        description: null,
        data: {},
      },
      {
        id: 7,
        type: "textarea",
        question: "Des recommandations/opinions pour les séances football du MUST?",
        description: "Donnez-nous votre opinion, cela nous intéresse.",
        data: {},
      },
    ],
  },
  {
    id: 200,
    title: "Must session du Mercredi",
    slug: "Must-session-du-Mercredi",
    status: "active",
    image: "https://picsum.photos/640/360",
    description: "Ceci est un formulaire de présence pour la prochaine séance du Mercredi.",
    created_at: "2022-12-22 18:00:00",
    updated_at: "2022-12-22 18:00:00",
    expire_date: "2022-12-29 18:00:00",
    questions: [],
  },
  {
    id: 300,
    title: "Must session du Vendredi",
    slug: "Must-session-du-Vendredi",
    status: "active",
    image: "https://picsum.photos/640/360",
    description: "Ceci est un formulaire de présence pour la prochaine séance du Vendredi.",
    created_at: "2022-12-22 18:00:00",
    updated_at: "2022-12-22 18:00:00",
    expire_date: "2022-12-29 18:00:00",
    questions: [],
  },
  {
    id: 400,
    title: "Must session du Samedi",
    slug: "Must-session-du-Samedi",
    status: "active",
    image: "https://picsum.photos/640/360",
    description: "Ceci est un formulaire de présence pour la prochaine séance du Samedi.",
    created_at: "2022-12-22 18:00:00",
    updated_at: "2022-12-22 18:00:00",
    expire_date: "2022-12-29 18:00:00",
    questions: [],
  },
];

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
    surveys: [...tmpSurveys],
  },
  getters: {},
  actions: {

    register({ commit }, user) {
      return axiosClient.post('/register', user)
        .then(({ data }) => {
          commit('setUser', data.user);
          commit('setToken', data.token)
          return data;
        })
    },

    login({ commit }, user) {
      return axiosClient.post('/login', user)
        .then(({ data }) => {
          commit('setUser', data.user);
          commit('setToken', data.token)
          return data;
        })
    },
    logout({ commit }) {
      return axiosClient.post('/logout')
        .then(response => {
          commit('logout')
          return response;
        })
    }
  },
  mutations: {
    logout: (state) => {
      state.user.data = {};
      state.user.token = null;
      sessionStorage.removeItem("TOKEN");
    },
    setUser: (state, user) => {
      state.user.data = user;
    },
    setToken: (state, token) => {
      state.user.token = token;
      sessionStorage.setItem('TOKEN', token);
    },
  },
  modules: {}
})

export default store;

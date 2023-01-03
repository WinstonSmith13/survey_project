import { createStore } from "vuex";
import axiosClient from "../axios";



const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
    currentSurvey: {
      loading: false,
      data: {},
    },
    surveys: {
      loading: false,
      data: [],
    },
    questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
    notification: {
      show: false,
      type: null,
      message: null
    },
  },
  getters: {},
  actions: {
    //Make an Http request
    getSurvey({ commit }, id) {
      //To show some loading text.
      commit("setCurrentSurveyLoading", true);
      //Make an Http request and with the get method we pass the id. 
      return axiosClient
        .get(`/survey/${id}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          //if we get a response the loading has to stop. 
          commit("setCurrentSurveyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        });
    },
    saveSurvey({ commit }, survey) {

      delete survey.image_url;

      let response;
      //sur le formulaire a une id alors on est entrain de modifier un formulaire, sinon on est entrain de créer un nouveau formulaire. 
      if (survey.id) {
        response = axiosClient
          .put(`/survey/${survey.id}`, survey)
          .then((res) => {
            commit('setCurrentSurvey', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/survey", survey).then((res) => {
          commit('setCurrentSurvey', res.data)
          return res;
        });
      }

      return response;
    },
    deleteSurvey({ }, id) {
      return axiosClient.delete(`/survey/${id}`);
    },
    getSurveys({ commit }) {
      //To show some loading text.
      commit("setSurveysLoading", true);
      return axiosClient.get("/survey").then((res) => {
        //if we get a response the loading has to stop. 
        commit("setSurveysLoading", false);
        commit("setSurveys", res.data);

        return res;
      })
    },
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
    },
    saveSurveyAnswer({ commit }, { surveyId, answers }) {
      return axiosClient.post(`/survey/${surveyId}/answer`, { answers });
    },
    getSurveyBySlug({ commit }, slug) {
      commit("setCurrentSurveyLoading", true);
      //on fait une requete http pour envoyer vers le back. 
      return axiosClient.get(`survey-by-slug/${slug}`).then((res) => {
        commit("setCurrentSurvey", res.data);
        commit("setCurrentSurveyLoading", false);
        //on retourne la reponse. 
        return res;
      })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        })
    },
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
    setCurrentSurveyLoading: (state, loading) => {
      state.currentSurvey.loading = loading;
    },
    setSurveysLoading: (state, loading) => {
      state.surveys.loading = loading;
    },
    setCurrentSurvey: (state, survey) => {
      state.currentSurvey.data = survey.data;
    },
    setSurveys: (state, surveys) => {

      state.surveys.data = surveys.data;
    },
    //deconstruction pour avoir pas que DATA mais message et type. 
    notify: (state, { message, type }) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      //After 3sec set the notification to false. 
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    }
  },
  modules: {}
})

export default store;

import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  // State property, which holds the data for the application
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
    dashboard: {
      loading: false,
      data: {},
    },
    answersView: {
      loading: false,
      data: {},
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
      message: null,
    },
  },
  getters: {},
  // Action property that perform asynchronous operations, such as making HTTP requests to the server
  actions: {
    // Action to get user data
    getUser({ commit }) {
      return axiosClient.get("/user").then((res) => {
        // Commit a mutation to set the user data in the store
        commit("setUser", res.data);
      });
    },
    // Action to get survey data by id
    getSurvey({ commit }, id) {
      commit("setCurrentSurveyLoading", true);
      // Make an Http request and with the get method we pass the id.
      return axiosClient
        .get(`/survey/${id}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          // If we get a response the loading stop.
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
      if (survey.id) {
        response = axiosClient
          .put(`/survey/${survey.id}`, survey)
          .then((res) => {
            commit("setCurrentSurvey", res.data);
            return res;
          });
      } else {
        response = axiosClient.post("/survey", survey).then((res) => {
          commit("setCurrentSurvey", res.data);
          return res;
        });
      }
      return response;
    },
    deleteSurvey({ dispatch }, id) {
      return axiosClient.delete(`/survey/${id}`).then((res) => {
        dispatch("getSurveys");
        return res;
      });
    },
    getSurveys({ commit }) {
      commit("setSurveysLoading", true);
      return axiosClient.get("/survey").then((res) => {
        // If we get a response the loading has to stop.
        commit("setSurveysLoading", false);
        commit("setSurveys", res.data);

        return res;
      });
    },
    register({ commit }, user) {
      return axiosClient.post("/register", user).then(({ data }) => {
        commit("setUser", data.user);
        commit("setToken", data.token);
        return data;
      });
    },
    login({ commit }, user) {
      return axiosClient.post("/login", user).then(({ data }) => {
        commit("setUser", data.user);
        commit("setToken", data.token);
        return data;
      });
    },
    logout({ commit }) {
      return axiosClient.post("/logout").then((response) => {
        commit("logout");
        return response;
      });
    },
    saveSurveyAnswer({ commit }, { surveyId, answers }) {
      return axiosClient.post(`/survey/${surveyId}/answer`, { answers });
    },
    getSurveyBySlug({ commit }, slug) {
      commit("setCurrentSurveyLoading", true);
      // I make an http request to send to the backend by passing the slug as a parameter
      return axiosClient
        .get(`survey-by-slug/${slug}`)
        .then((res) => {
          commit("setCurrentSurvey", res.data);
          commit("setCurrentSurveyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentSurveyLoading", false);
          throw err;
        });
    },
    getDashboardData({ commit }) {
      commit("dashboardLoading", true);
      return axiosClient
        .get("/dashboard")
        .then((res) => {
          commit("dashboardLoading", false);
          commit("setDashboardData", res.data);
          return res;
        })
        .catch((error) => {
          commit("dashboardLoading", false);
          throw error;
        });
    },
    getAnswersViewData({ commit }, id) {
      commit("answerViewLoading", true);
      return axiosClient
        .get(`/answer/${id}`)
        .then((res) => {
          commit("answerViewLoading", false);
          commit("setAnswersViewData", res.data);
          return res;
        })
        .catch((error) => {
          commit("answerViewLoading", false);
          throw error;
        });
    },
  },
  // Mutations are used to change the store state of a Vuex application.
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
      sessionStorage.setItem("TOKEN", token);
    },
    setCurrentSurveyLoading: (state, loading) => {
      state.currentSurvey.loading = loading;
    },
    setSurveysLoading: (state, loading) => {
      state.surveys.loading = loading;
    },
    dashboardLoading: (state, loading) => {
      state.dashboard.loading = loading;
    },
    setDashboardData: (state, data) => {
      state.dashboard.data = data;
    },
    answerViewLoading: (state, loading) => {
      state.answersView.loading = loading;
    },
    setAnswersViewData: (state, data) => {
      state.answersView.data = data;
    },
    setCurrentSurvey: (state, survey) => {
      state.currentSurvey.data = survey.data;
    },
    setSurveys: (state, surveys) => {
      state.surveys.data = surveys.data;
    },
    // Deconstruction to have not only DATA but also message and type.
    notify: (state, { message, type }) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      // After 3sec set the notification to false to make the notification disappear.
      setTimeout(() => {
        state.notification.show = false;
      }, 5000);
    },
  },
  modules: {},
});

export default store;

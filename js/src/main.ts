import Vue from "vue";
import Vuetify from "vuetify";
import App from "./App.vue";

import "vuetify/dist/vuetify.min.css";
import './sass/vuetify.sass';
import vuetify from "./plugins/vuetify";

Vue.use(Vuetify);
Vue.config.productionTip = false;

new Vue({
  vuetify,
  render: h => h(App)
}).$mount("#app");

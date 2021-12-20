import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import vuetify from './plugins/vuetify'
import router from './router'

//import library axios global
import Axios from './plugins/axios';
Vue.use(Axios);

//import library session
import session from './plugins/session';

Vue.config.productionTip = false
Vue.prototype.$apiUrl = 'https://naturalbeautycenter0018.000webhostapp.com/rest-api-nbc/index.php/'; 

new Vue({
  vuetify,
  router,
  session,
  render: h => h(App)
}).$mount('#app')

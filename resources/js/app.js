import './bootstrap';
import {createApp} from 'vue'
import App from './App.vue'

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import "vue3-json-viewer/dist/index.css";
import JsonViewer from "vue3-json-viewer";

const vuetify = createVuetify({
    components,
    directives,
})

createApp(App).use(vuetify).use(JsonViewer).mount('#app')

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/* Imported components */

import VueQrcode from '@chenfengyuan/vue-qrcode';
Vue.component(VueQrcode.name, VueQrcode);


import CKEditor from 'ckeditor4-vue';
Vue.use( CKEditor );

/* Custom components */

Vue.component('presentations', require('./components/Presentations.vue').default);
Vue.component('teammanager', require('./components/TeamManager.vue').default);
Vue.component('nemjelentkezett', require('./components/NoSignups.vue').default);
Vue.component('programok', require('./components/EventsTable.vue').default);
Vue.component('eventviewer', require('./components/EventViewer.vue').default);
Vue.component('attendanceopener', require('./components/AttendanceCheckerOpening.vue').default);
Vue.component('attendancechecker', require('./components/AttendanceChecker.vue').default);
//Vue.component('programokadmincol', require('./components/EventsTableAdminCols.vue').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

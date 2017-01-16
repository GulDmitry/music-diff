import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
import './css/font-awesome.css'
import './css/theme.css'
import 'babel-polyfill'

import Vue from 'vue'
import App from './components/App'
import store from './store'

// Application structure: https://github.com/vuejs/vuex/tree/dev/examples/shopping-cart
// Vue: https://ru.vuejs.org/v2/guide/index.html
// Vues: https://vuex.vuejs.org/ru/forms.html

$(document).ready(function() {
    new Vue({
        el: '#todo-app',
        store,
        render: h => h(App)
    });


    //   Vue.component('todo-item1', {
    //       props: ['todo'],
    //       template: '<li>{{ todo.text }}</li>'
    //   });
    //
    //   new Vue({
    //       delimiters: ['${', '}'],
    //       el: '#music-diff',
    //       data: {
    //           message: 'You loaded this page on ' + new Date(),
    //           seen: true,
    //           ok: true,
    //           todos: [
    //               {text: 'Learn JavaScript'},
    //               {text: 'Learn Vue'},
    //               {text: 'Build something awesome'}
    //           ],
    //           groceryList: [
    //               {text: 'Vegetables'},
    //               {text: 'Cheese'},
    //               {text: 'Whatever else humans are supposed to eat'}
    //           ],
    //           firstName: 'fname',
    //           lastName: 'lname',
    //       },
    //       computed: {
    //           // a computed getter
    //           reversedMessage() {
    //               // `this` points to the vm instance
    //               return this.message.split('').reverse().join('')
    //           },
    //           now: function() {
    //               return Date.now()
    //           },
    //           fullName: {
    //               // getter
    //               get() {
    //                   return this.firstName + ' ' + this.lastName
    //               },
    //               // setter
    //               set(newValue) {
    //                   let names = newValue.split(' ');
    //                   this.firstName = names[0];
    //                   this.lastName = names[names.length - 1]
    //               }
    //           },
    //       },
    //       methods: {
    //           reverseMessage() {
    //               this.message = this.message.split('').reverse().join('')
    //           }
    //       },
    //       filters: {
    //           capitalize(value) {
    //               if (!value) return '';
    //               value = value.toString();
    //               return value.charAt(0).toUpperCase() + value.slice(1)
    //           }
    //       },
    //       // before{the events}, mounted, updated, and destroyed
    //       created() {
    //           // `this` points to the vm instance
    //           console.log('message is: ' + this.message)
    //       }
    //   });
    //
    //   Vue.component('todo-item', {
    //       template: '\
    //   <li>\
    //     {{ title }}\
    //     <button v-on:click="$emit(\'remove\')">X</button>\
    //   </li>\
    // ',
    //       props: ['title']
    //   });
    //
    //   new Vue({
    //       el: '#todo-list-example',
    //       data: {
    //           newTodoText: '',
    //           todos: [
    //               'Do the dishes',
    //               'Take out the trash',
    //               'Mow the lawn'
    //           ]
    //       },
    //       components: {
    //           App,
    //           // <my-component> will only be available in parent's template
    //           'my-component': {
    //               props: {
    //                   'initialCounter': {Number, required: false},
    //                   'initialCounter2': Number
    //               },
    //               data() {
    //                   return {counter: this.initialCounter}
    //               },
    //               template: '<div>A custom component!</div>'
    //           }
    //       },
    //       methods: {
    //           addNewTodo() {
    //               this.todos.push(this.newTodoText)
    //               this.newTodoText = ''
    //           }
    //       }
    //   })
});

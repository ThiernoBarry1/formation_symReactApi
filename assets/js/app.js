/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
//
import React from 'react';
import ReactDOM from 'react-dom';

console.log('Hello Webpack Encore!!! Edit me in assets/js/app.js');

const App = () =>{
        return <h1>Bonjour !</h1>
}

const rootElement = document.querySelector('#app');
ReactDOM.render(<App></App>,rootElement);
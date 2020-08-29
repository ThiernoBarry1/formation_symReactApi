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
import NavBar from './Components/NavBar';
import HomePage from './Pages/HomePage';
import "bootswatch/dist/litera/bootstrap.min.css"; 
import { HashRouter, Switch, Route }  from "react-router-dom";
import CustomersPage from './Pages/CustomersPage';
import InvoicesPage from "./Pages/InvoicesPage";
import CustomersPageWithPagination from './Pages/CustomersPageWithPagination';



const App = () =>{
        return ( 
                // le HashRouter pour ajouter un # à la route et differencier avec symfony 
                // localhost:8000/#/customers
                // localhost:8000/#/invoices
                // localhost:8000/app avec symfony par exemple

                 <HashRouter>
                   <NavBar/> 
                   <main className="container pt-5">
                       <Switch>
                           {/*<Route path="/customers/WithPagination" component={ CustomersPageWithPagination }/>*/}
                           <Route path="/invoices" component={ InvoicesPage }/>
                           <Route path="/customers" component={ CustomersPage }/>
                           <Route path="/" component={ HomePage }/>
                       </Switch>        
                   </main>
                 </HashRouter>
        );
}

const rootElement = document.querySelector('#app');
ReactDOM.render(<App></App>,rootElement);
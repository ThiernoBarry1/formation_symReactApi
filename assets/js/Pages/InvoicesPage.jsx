import React, { useEffect, useState } from 'react';
import axios from "axios";
import Pagination from '../Components/Pagination';
import DataApi from '../Services/DataApi';
import moment from 'moment';
const InvoicesPages = (propos) => {
    const  [invoices, setInvoices] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalItems, setTotalItems] = useState(1);
    const itemPerPage = 5;
    const fetchInvoices = async ( )=>
    {
        try {
            const invoices = await DataApi.findAll("invoices");
            setInvoices(invoices);
        } catch (error) {
            console.log(error.response);
        }
    } 
    useEffect(()=>{
        /* CustomersApi.findAll()
         .then(data => setCustomers(data))
         .catch(error => console.log(error.response))
         */
        fetchInvoices();
     },[]);
     const format = (stringDate) =>{
         return moment(stringDate).format('DD/MM/YYYY');
     }
     const colorStatut = (status) =>{
           switch (status) {
               case "PAID":
                    return "success";
               case "SENT":
                    return "info";
               case "CANCELLED":
                    return "danger";
               default:
                   return "";
           }
     }
     const descriptionStatus = (status)=>{
        switch (status) {
            case "PAID":
                 return "payée";
            case "SENT":
                 return "envoyée";
            case "CANCELLED":
                 return "annulée";
            default:
                return "";
        }
     }
     Pagination.getData(filteredCustomers,currentPage,itemPerPage)
    return ( 
            <>
                <h1> La liste des factures </h1>
                <table className="table table-hover">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date envoie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {invoices.map((invoice) =>
                            <tr  key={invoice.id}>
                                <td >{invoice.chrono}</td>
                                <td>{invoice.customer.firstName} {invoice.customer.lastName}</td>
                                <td >{invoice.amount.toLocaleString()} ‎€</td>
                                <td >
                                    <span className={"badge badge-"+colorStatut(invoice.status)}>{descriptionStatus(invoice.status)}</span></td>
                                <td >{format(invoice.sentAt) }</td>
                                <td> 
                                    <button className="btn btn-sm btn-danger mr-3">supprimer</button>
                        
                                    <button className="btn btn-sm btn-info">éditer</button>
                                </td>
                           </tr> 
                        )
                        }
                    </tbody>
                </table>
                {<Pagination currentPage={currentPage}  length={filteredCustomers.length} itemPerPage ={itemPerPage} 
                  handlePageChange={handlePageChange}/> }
            </>
        );
}
 
export default InvoicesPages;
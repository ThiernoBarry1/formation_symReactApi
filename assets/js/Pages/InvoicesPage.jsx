import React, { useEffect, useState } from 'react';
import axios from "axios";
import Pagination from '../Components/Pagination';
import DataApi from '../Services/DataApi';
import moment from 'moment';
const STATUS = {
    PAID: "Payée",
    CANCELLED: "Annulée",
    SENT: "Envoyée"
}
const InvoicesPages = (propos) => {
    const  [invoices, setInvoices] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalItems, setTotalItems] = useState(1);
    const itemPerPage = 20;
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
     
     const handlePageChange = (page)=>
     {
         setCurrentPage(page);
     }
     const handleDelete = async  id=>{
         try {
            await CustomersApi.deleteElement(id);
         } catch (error) {
            setInvoices(invoices.filter(invoice=>invoice.id !== id ))
         }
     }
     const paginatedInvoices = Pagination.getData(invoices,currentPage,itemPerPage)
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
                        {paginatedInvoices.map((invoice) =>
                            <tr  key={invoice.id}>
                                <td >{invoice.chrono}</td>
                                <td>{invoice.customer.firstName} {invoice.customer.lastName}</td>
                                <td >{invoice.amount.toLocaleString()} ‎€</td>
                                <td >
                                    <span className={"badge badge-"+colorStatut(invoice.status)}>{STATUS[invoice.status]}</span></td>
                                <td >{format(invoice.sentAt) }</td>
                                <td> 
                                    <button className="btn btn-sm btn-danger mr-3" onClick={ ()=>handleDelete(invoice.id)}>supprimer</button>
                        
                                    <button className="btn btn-sm btn-info">éditer</button>
                                </td>
                           </tr> 
                        )
                        }
                    </tbody>
                </table>
                { 
                    <Pagination currentPage={currentPage}  length={invoices.length} itemPerPage ={itemPerPage} 
                  handlePageChange={handlePageChange}/> }
            </>
        );
}
 
export default InvoicesPages;
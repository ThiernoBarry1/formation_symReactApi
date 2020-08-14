import React, { useEffect, useState } from 'react';
import Axios from 'axios';
const CustomersPage = (props) => {
    const [ customers,  setCustomers ] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);

    useEffect(()=>{
        Axios.get("http://127.0.0.1:8000/api/customers")
        .then((response)=>response.data['hydra:member'],[])
        .then(data => setCustomers(data))
        .catch(error => console.log(error.response))
    });
    const handleDelete = id=>{
         Axios.delete("http://127.0.0.1:8000/api/customers/"+id).then((response)=> {
             setCustomers(customers.filter(customer=>customer.id !== id ))
         });
     }
     const handlePageChange = (page)=>
     {
         setCurrentPage(page);
     }
     const itemPerPage = 10;
     const nbrPages = Math.ceil(customers.length/itemPerPage);
     const pages = [];
     for(let i=1;i< nbrPages;i++){
        pages.push(i);
     }
     const start = currentPage * itemPerPage - itemPerPage ;
     //              3         * 10  - 10 = 20
     //              4         *  10 - 10 = 30    
     const paginatedCustomers = customers.slice(start , start+itemPerPage);
    return ( 
        <>
        <h1> liste des clients</h1>
        <table className="table table-hover">
           <thead>
               <tr>
                   <th>Id</th>
                   <th>Client</th>
                   <th>Email</th>
                   <th>Entreprise</th>
                   <th  className="text-center">Factures</th>
                   <th  className="text-center">Montant total</th>
                   <th/>
               </tr>
           </thead>
           <tbody>
         { paginatedCustomers.map((customer)=>(
               <tr key={customer.id}>
                   <td>{customer.id}</td>
                   <td>
                     <a href="#"> { customer.firstName} { customer.lastName}</a>
                   </td>
                    <td>{ customer.email}</td>
                    <td>{customer.compagny }</td>
                    <td className="text-center"><span className="badge badge-primary">{customer.invoices.length}</span></td>
                    <td className="text-center">{customer.montantTotal.toLocaleString()}</td>
                   <td>
                       <button className="btn btn-sm btn-danger" onClick={()=>handleDelete(customer.id)} disabled={ customer.invoices.length > 0}>Supprimer</button>
                   </td>
               </tr>
         ))
          } 
           </tbody>
        </table>
        <div>
            <ul className="pagination pagination-sm">
                <li className={"page-item  "+(currentPage === 1 && " disabled")}>
                     <button className="page-link" onClick={()=>handlePageChange(currentPage-1)}>&laquo;</button>
                </li>
                
                { pages.map((page) => 
                     <li className={"page-item "+(currentPage === page && "active")} key={page} onClick={()=>handlePageChange(page)}>
                      <button className="page-link">
                        {page}
                      </button>
                    </li>
                )}
                <li className={"page-item  "+(currentPage === nbrPages && "disabled")}>
                   <button className="page-link" onClick= {()=>handlePageChange(currentPage+1)}>&raquo;</button>
                </li>
            </ul>
        </div>
        </>
     );
}
 
export default CustomersPage;
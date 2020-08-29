import React, { useEffect, useState } from 'react';
import Pagination from '../Components/Pagination';
import CustomersApi from '../Services/DataApi';

const CustomersPage = (props) => {
    const [ customers,  setCustomers ] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [search, setSearch] = useState("");
    /** fetch all customers  from API  */
    const fetchCustomers = async ( )=>
    {
        try {
            const data  =  await CustomersApi.findAll("customers")
            setCustomers(data);
        } catch (error) {
            console.log(error.response);
        }
    }
    useEffect(()=>{
       /* CustomersApi.findAll()
        .then(data => setCustomers(data))
        .catch(error => console.log(error.response))
        */
        fetchCustomers();
    });
    const handleDelete = async  id=>{
        /*CustomersApi.deleteCustomers(id)
         .then((response)=> {
             setCustomers(customers.filter(customer=>customer.id !== id ))
         });*/
         try {
            await CustomersApi.deleteElement(id);
         } catch (error) {
            setCustomers(customers.filter(customer=>customer.id !== id ))
         }
     }
     const handlePageChange = (page)=>
     {
         setCurrentPage(page);
     }
     const handleSearch = ( {currentTarget}) =>{
         const value = currentTarget.value;
         setSearch(value);
     }
     const itemPerPage = 10;
     const filteredCustomers = customers.filter(c=>
         c.firstName.toLowerCase().includes(search.toLowerCase()) || 
         c.lastName.toLowerCase().includes(search.toLowerCase()) ||
         c.email.toLowerCase().includes(search.toLowerCase()) ||
         c.compagny !== null && c.compagny.toLowerCase().includes(search.toLowerCase())
         )
     const paginatedCustomers = filteredCustomers.length > itemPerPage ? Pagination.getData(filteredCustomers,currentPage,itemPerPage) :filteredCustomers;
    return ( 
        <>
        <h1> liste des clients</h1>
        <div className="form-group">   
          <input type="text" className="form-control" onChange={handleSearch} value={search} 
                  placeholder="rechercher ..."/>
        </div>
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
        {itemPerPage <= filteredCustomers.length && <Pagination currentPage={currentPage}  length={filteredCustomers.length} itemPerPage ={itemPerPage} 
                  handlePageChange={handlePageChange}/> }
        
        </>
     );
}
 
export default CustomersPage;
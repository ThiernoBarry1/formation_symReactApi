import React from 'react';
const Pagination = (propos) => {
    const   {currentPage,length,itemPerPage,handlePageChange} = propos;
    const nbrPages = Math.ceil(length/itemPerPage);
     const pages = [];
     for(let i=1;i< nbrPages;i++){
        pages.push(i);
     }
    return ( 
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
     );
};
/**
 * allows to calculate the table to be  returned for next page according the current page
 */
 Pagination.getData = (items, currentPage, itemPerPage)=>{
    const start = currentPage * itemPerPage - itemPerPage ;
    //              3         * 10  - 10 = 20
    //              4         *  10 - 10 = 30    
    return  items.slice(start , start+itemPerPage);
 }
export default Pagination;
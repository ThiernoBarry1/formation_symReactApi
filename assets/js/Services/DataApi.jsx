import React from 'react';
import Axios from "axios";


function findAll(names){
    return Axios.get("http://127.0.0.1:8000/api/"+names)
    .then((response)=>response.data['hydra:member'],[]);
}
function deleteElement(id){
    return Axios.delete("http://127.0.0.1:8000/api/customers/"+id);
}
export  default {
    findAll
}
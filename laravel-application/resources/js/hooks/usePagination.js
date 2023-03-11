import React, {useState} from "react";
//Este HOOK ENGINE de paginación sirve para evitar la repetición de código en los componentes que usan paginación

const usePagination = (totalPages) => {
    //Este state contiene la pagina actual de la paginación que vendra del back
    const [currentPage, setCurrentPage] = useState(1);

    //Estos handlers se ocupan de navegar por la paginación, estos se pasarán al componente que invoquen este HOOK ENGINE
    const NextPageHandler = () => {
        if(currentPage < totalPages)
            setCurrentPage(currentPage + 1)
        else
            setCurrentPage(1)
        
    }


    const PrevPageHandler = () => {
        if(currentPage > 1)
            setCurrentPage(currentPage - 1)
        else
            setCurrentPage(totalPages)
    }

    return { currentPage, NextPageHandler, PrevPageHandler, setCurrentPage };
}

export default usePagination;
import React from 'react'
import bisontito from "../../../../public/images/bisontito.png"
//Este engine será usado especificamente para mostrar una pantalla en la carga de solicitudes
//Cualquier uso o abuso indebido de este engine será sancionado
const LoadingEngine = ()=>{
    return(
        <div className="d-flex flex-column justify-content-center align-items-center  vh-100">
        <img src={bisontito} style={{width:"120px"}} />
        <h1 className='text-center'>Estamos procesando tu solicitud</h1>
        <div className="spinner-border" role="status">
            <span className="sr-only">Loading...</span>
        </div>
    </div>
    )
}

export default LoadingEngine;
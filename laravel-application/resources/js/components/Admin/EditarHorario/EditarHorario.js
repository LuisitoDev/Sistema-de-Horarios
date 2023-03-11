import React from "react"
import ReactDOM from 'react-dom';
import AsesorDias from "./AsesorDias";
import AsesorInfo from "./AsesorInfo";
import AsesorHoras from "./AsesorHoras";
const EditarHorario = ()=>{

    return(
        <>
        <h1 className="text-center my-5 display-4  ">Confirmar solicitud</h1>
        
        <AsesorInfo/>

        <AsesorDias/>

        <AsesorHoras/>
        <div className="d-flex justify-content-between col-12 col-lg-8 container p-0 mt-lg-4 px-4 mb-5">
            <button className="btn btn-danger w-100 mb-3 border-bottom-danger mx-1">Rechazar</button>
            <button className="btn btn-primary ss-btn w-100 mb-3">Aceptar</button>
        </div></>
    )
}

export default EditarHorario;

if(document.getElementById("EditarHorario")){
    ReactDOM.render(<EditarHorario/>,document.getElementById("EditarHorario"))
}
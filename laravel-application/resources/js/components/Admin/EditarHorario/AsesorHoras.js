import React from 'react'
import View from '../../../utils/ViewEnum';
import Tabla from '../Tabla/Tabla';
const AsesorHoras = ()=>{
    const view=View.Horario;
    return(
        <div className="col-12 col-lg-8 container p-0 mt-lg-4 bg-white shadow-lg px-4 mb-5">
            <h1 className="my-5 fs-3">Horarios semanales</h1>
            <Tabla view={view}/>
        </div>
    )
}
export default AsesorHoras;
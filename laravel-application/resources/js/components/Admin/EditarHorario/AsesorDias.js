import React from 'react';
import SeleccionHorario from './SeleccionHorario';
import SeleccionDias from './SeleccionDias';


const AsesorDias= ()=>{
    return(<div className="col-12 col-lg-8 container p-0 mt-lg-4 bg-white shadow-lg px-4 mb-5">
    <h1 className="my-5 fs-3">Horario</h1>
   
   <SeleccionHorario/>
    <SeleccionDias/>

      <button className="btn btn-primary ss-btn w-100 mb-3">Guardar y asignar</button>

</div>)
}
export default AsesorDias;
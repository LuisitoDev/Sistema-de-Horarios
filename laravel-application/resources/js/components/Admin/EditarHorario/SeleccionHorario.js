import React from "react";

const SeleccionHorario = ()=>{

    return(
        <>
         <div className="d-md-flex justify-content-between align-items-center  mb-5">
        <div className="d-flex justify-content-center align-items-center mb-5 mb-md-0">

        
        <select name="horario" className="p-1 ss-btn text-white ">
            <option value="Martes-Jueves">Martes-Jueves</option>
            <option value="Completo">Completo</option>

            <option value="Lunes-Miercoles-Viernes">Lunes-Miercoles-Viernes</option>
            <option value="Personalizado">Personalizado</option>

        </select>
        </div>
        <div className="text-center">

            <input type="text" className="form-control mb-3" placeholder="Nombre de horario"/>
        <button className="btn btn-primary ss-btn w-100">Crear nuevo</button>
    </div>
    </div></>
    )
}
export default SeleccionHorario;
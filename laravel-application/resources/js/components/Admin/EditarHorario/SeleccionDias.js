import React from 'react'

const SeleccionDias= ()=>{
    return(
        <div className="d-md-flex justify-content-between mb-5" role="group" aria-label="Basic checkbox toggle button group">
        <div className="mb-2">


        <input type="checkbox" className="btn-check" id="btncheck1" autoComplete="off"/>
        <label className="btn btn-outline-primary  w-100" htmlFor="btncheck1">Lunes</label>
        </div>
    <div className="mb-2">


        <input type="checkbox" className="btn-check" id="btncheck2" autoComplete="off"/>
        <label className="btn btn-outline-primary w-100" htmlFor="btncheck2">Martes</label>
    </div>
    <div className="mb-2">


        <input type="checkbox" className="btn-check" id="btncheck3" autoComplete="off"/>
        <label className="btn btn-outline-primary w-100" htmlFor="btncheck3">Miercoles</label>
    </div>
    <div className="mb-2">


        <input type="checkbox" className="btn-check" id="btncheck4" autoComplete="off"/>
        <label className="btn btn-outline-primary w-100" htmlFor="btncheck4">Jueves</label>
    </div>
    <div className="mb-2">


        <input type="checkbox" className="btn-check" id="btncheck5" autoComplete="off"/>
        <label className="btn btn-outline-primary w-100" htmlFor="btncheck5">Viernes</label>
    </div>
      </div>
    )
}
export default SeleccionDias;
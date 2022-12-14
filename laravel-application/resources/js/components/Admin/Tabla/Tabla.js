import React from "react";
import TablaCabecera from "./TablaCabecera";
import TablaCuerpo from "./TablaCuerpo";
const Tabla = (props)=>{
    // console.log('Este es el puntero al evento de acciones', props.onActions, 'con view: ', props.view)
    

    return(
      <div className=" py-3 d-flex align-items-center">
        {props.content.length > 0 &&
          <table className="table table-borderless table-curved " style={{borderCollapse: 'separate',borderSpacing:'0px 15px'}} >
          <TablaCabecera view={props.view}/>
          <TablaCuerpo view={props.view} content={props.content} onActions={props.onActions}/>
          </table>
        }
        {
          props.content.length == 0 &&
          <div className="w-100 d-block">
            <hr/>
            <p className="h3 fw-bold text-center">No se encontraron registros.</p>
          </div>
        }
      </div>
    )
    
}
export default Tabla;
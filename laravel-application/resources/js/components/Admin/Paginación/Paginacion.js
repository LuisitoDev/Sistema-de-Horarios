import React from 'react';
import View from '../../../utils/ViewEnum';

const Paginacion = (props)=>{


    return(

    <>
    {
      props.totalPages > 0 &&
      <>
        <div className="row mt-3 mb-5">

          <div className="col-12  d-flex justify-content-between">
              <button onClick={props.PrevPageHandler} className="btn btn-light button-white__color" ><i className="fa-solid fa-chevron-left text-blue__color"></i></button>


              <h5 className="text-center">Página <span>{props.currentPage}</span> de <span>{props.totalPages}</span></h5>


              <button onClick={props.NextPageHandler} className="btn btn-light button-white__color" ><i className="fa-solid fa-chevron-right text-blue__color"></i></button>
          </div>


        </div>
      </>

    }

    {(props.view === View.Alumnos || props.view === View.AlumnosEntradas) &&
        <div className="d-flex justify-content-center">
        <button type="button" className="btn btn-lg ss-btn text-white mx-4 mx-sm-5 mb-5" onClick={props.clickHandlerButtonFile}>Importar</button>
        <input type="file" className="d-none" id="id_ile" onChange={props.submitHandlerButtonFile}/>
        <button type="button" className="btn btn-lg ss-btn text-white mx-4 mx-sm-5 mb-5" onClick={props.clickHandlerButtonExport}>Exportar</button>
        </div>
    }

    </>
    )
}
export default Paginacion;

import React from 'react';
import View from '../../../utils/ViewEnum';
const MatriculaBusqueda = (props)=>{
    const placeHolderText = props.view === View.RelojAdmin ? 'Matricula' : 'Buscar matricula o nombre'

    const onChangeHandler = (e)=>{
        const {name, value} = e.target;

        if (value == "")
            props.setTuition(null);
        else
            props.setTuition(value);


    }

    return(
        <>
        <h1 className="text-center my-4 display-4 ">{ props.view === View.SolicitudesDispositivos ? View.Solicitudes : props.view === View.AlumnosEntradas ? View.Alumnos : props.view}</h1>
            <div className="text-center w-100">
                {(props.view === View.Alumnos ||props.view === View.AlumnosEntradas) && <button className='btn ss-btn text-white mb-2' onClick={props.deleteOldRegistersHandler}>Eliminar Registros Antiguos</button>}
            </div>
            <div className="mb-3 d-flex ">

                <div className="input-group p-0">
                    <form className='w-100 d-flex'  onSubmit={props.searchHandler ?? null}>
                        <input type="text" className="form-control p-2 text-box" placeholder={placeHolderText} onChange={onChangeHandler}/>
                        <button className="btn ss-btn border-bottom-0" type="submit"><i className="fas fa-search text-white"></i></button>
                    </form>

                </div>


        </div>


       
                </>
    )
}

export default MatriculaBusqueda;

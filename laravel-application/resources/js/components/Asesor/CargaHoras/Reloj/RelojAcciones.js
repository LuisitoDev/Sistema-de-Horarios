import React from "react"

const RelojAcciones = (props)=>{

    return( 
        <div className="row justify-content-center mb-4 px-2">
        <div className="col text-center">
            {!(props.isActive) && <button className="btn ss-btn text-white w-100 mb-3" onClick={props.handleStart}>Empezar</button> } 

            {props.isActive && <button type="button" className="btn btn-danger border-bottom-danger text-white w-100 mb-3" onClick={()=>props.setIsModalActive(true)}>Terminar</button>}
        </div>
    </div>
    )
}

export default RelojAcciones;
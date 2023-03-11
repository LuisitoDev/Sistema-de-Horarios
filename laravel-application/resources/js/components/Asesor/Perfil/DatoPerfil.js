import React from 'react'

const DatoPerfil = (props)=>{

    return(
        <>
        <div className=" mb-2">
                    <span className="bg-transparent border-0" id="basic-addon1">
                        <i className={"fa-solid me-1 " + props.icon}></i>
                    </span>

                    <label>{props.info}</label>
        </div>
        </>
    )
}

export default DatoPerfil;
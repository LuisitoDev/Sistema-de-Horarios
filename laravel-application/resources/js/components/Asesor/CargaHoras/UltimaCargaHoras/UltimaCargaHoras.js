import React from "react"
import {getTime, formatHoursMinutes, isCurrentDay} from "../../../../utils/TimeFunctions"

const UltimaCargaHoras = (props)=>{
    let { entradas } = props
    let { turnosDiarios } = props

    const diaEntradaText = ($entradas) =>{
        if ($entradas === null)
            return "día de hoy"
        else if ($entradas.length == 0)
            return "día de hoy"
        else{
            let $hora_entrada = entradas[0]?.hora_entrada
            if (isCurrentDay($hora_entrada))
                return "día de hoy"
            else
                return "día pasado"
        }
    }

    return (

        <section className="progress-dashboard  pb-5 pb-xl-0">
                <div className="row align-content-center my-2">
                    <div className="col">
                        <p className="ms-2 mb-0 fs-5">Entradas del {diaEntradaText(entradas)}</p>
                    </div>
                </div>
                <div className="row justify-content-between align-items-center box-blue__background mx-1 text-white gx-0 rounded">
                    <span className="col py-2 text-center">Hora inicio program.</span>
                    <span className="col py-2 text-center">Hora final program.</span>
                    <span className="col py-2 text-center">Hora inicio</span>
                    <span className="col py-2 text-center">Hora final</span>
                </div>
                <div className="cards-container">
                {turnosDiarios && turnosDiarios.map((turnoDiario, index) =>
                    <div className="hour-card row gx-0 mx-1 my-3 text-blue__color bg-white rounded" key={turnoDiario.id}>

                            <>
                                <span className="col p-2 text-center">{formatHoursMinutes(turnoDiario.hora_entrada)}</span>
                                <span className="col p-2 text-center">{formatHoursMinutes(turnoDiario.hora_salida)}</span>
                                <span className="col p-2 text-center">{getTime(entradas[index]?.hora_entrada)}</span>
                                <span className="col p-2 text-center">{getTime(entradas[index]?.hora_salida)}</span>
                            </>


                    </div>
                    )
                }
                </div>
         </section>

    )
}
export default UltimaCargaHoras;

import React from "react"
import {getDate, getTime, isCurrentDay, decimalHoursToHoursMinutes} from "../../../utils/TimeFunctions"
import StatusType from "../../../utils/StatusEnum"

const TablaRowHoras = (props)=>{
    const { hour } = props;

    return (
    <tr className={`${
            hour.id_status === StatusType.TRABAJANDO && isCurrentDay(hour.hora_entrada_programada) ? "text-white bg-success" :
            hour.horas_realizadas >= hour.horas_realizadas_programada ? "text-blue__color"  : "text-white bg-danger"}
            text-center`}
    >
        <td scope="row" className="">{getDate(hour.hora_entrada_programada)}</td>
        <td className="">{getTime(hour.hora_entrada_programada)}</td>
        <td className="">{getTime(hour.hora_salida_programada)}</td>
        <td className="">{getTime(hour.hora_entrada)}</td>
        <td className="">{getTime(hour.hora_salida)}</td>
        <td className="">{decimalHoursToHoursMinutes(hour.horas_realizadas)}</td>
    </tr>)
}
export default TablaRowHoras;

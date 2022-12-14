import PreviousMap from "postcss/lib/previous-map";
import React from "react";
import {decimalHoursToHoursMinutes} from "../../../utils/TimeFunctions"

const TablaRowAlumnosEntradas = (props)=>{
    const { student } = props;

    return (  <tr className="text-blue__color ">
    <td scope="row "><a href="">{student.tuition}</a></td>
    <td className="d-none d-lg-table-cell">{ `${student.names} ${student.firstLastName} ${student.secondLastName}` }</td>
    <td className="d-none d-lg-table-cell">{student.email}</td>
    <td className="d-none d-lg-table-cell">{student.program}</td>
    <td>{student.career}</td>
    <td className="d-lg-table-cell">{student.checkIns}</td>
    <td className="d-lg-table-cell">{decimalHoursToHoursMinutes(student.pendingHours)}</td>

    {/* <td scope="row "><a href="">191231</a></td>
    <td className="d-none d-lg-table-cell">Elias Carlos Hernandez Gonzalez</td>
    <td className="d-none d-lg-table-cell">Elias@hotmail.com</td>
    <td className="d-none d-lg-table-cell">Asesorias</td>
    <td className="d-none d-lg-table-cell">LMAD</td>
    <td className="d-lg-table-cell">5</td> */}
  </tr>)
}
export default TablaRowAlumnosEntradas;

import React, {useRef} from "react"
import * as AlumnosServices from '../../../services/AlumnosServices'

const TablaRowAlumno = (props)=>{
    const { student } = props;
    const tuitionRef = useRef(null)

    // TODO: copy tuition to clipboard
    
    const tuitionClickHandler = (e) => {
      e.preventDefault();
      navigator.clipboard.writeText(student.tuition)

    }

    return (  <tr className="text-blue__color " >
    <td scope="row "><i className="fa-regular fa-copy"></i><a href="#" title='Haz click para copiar al portapapeles' ref={tuitionRef} onClick={tuitionClickHandler}>{student.tuition}</a></td>
    <td className="d-none d-lg-table-cell">{ `${student.names} ${student.firstLastName} ${student.secondLastName}` }</td>
    <td className="d-none d-lg-table-cell">{student.email}</td>
    <td className="d-none d-lg-table-cell">{student.program}</td>
    <td>{student.career}</td>
    <td>   
    <button className="btn btn-warning me-1" type="button" onClick={()=>{props.onActions('edit', student.tuition)}}><i className="fa-solid fa-pen"></i></button>
    <button className="btn btn-danger" type="button" onClick={()=>{props.onActions('delete', student.tuition)}}><i className="fa-solid fa-trash-can"></i></button>
     
     </td>
  </tr>)
}
export default TablaRowAlumno;
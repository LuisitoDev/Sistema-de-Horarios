import React from "react"

const TablaRowSolicitudDispositivo = (props)=>{
    const { request } = props;

    return (  <tr className="text-blue__color " >
    <td scope="row "><a href="">{request.tuition}</a></td>
    <td className="d-none d-lg-table-cell">{ `${request.names} ${request.firstLastName} ${request.secondLastName}` }</td>
    <td className="d-none d-lg-table-cell">{request.email}</td>
    <td className="d-none d-lg-table-cell">{request.program}</td>
    <td className="d-none d-lg-table-cell">{request.career}</td>
    <td className="d-none d-lg-table-cell">{request.id}</td>
    <td>{request.device}</td>
    <td  >   
      <button className="btn btn-success me-1" type="button" onClick={()=>{props.onActions('accept', request.id)}}><i className="fa-solid fa-check"></i></button>
      <button className="btn btn-danger" type="button" onClick={()=>{props.onActions('reject', request.id)}}><i className="fa-solid fa-xmark"></i></button>
     
     </td>
  </tr>)
}
export default TablaRowSolicitudDispositivo;
import PreviousMap from "postcss/lib/previous-map";
import React from "react"
import { motion } from 'framer-motion';
const TablaRowSolicitud = (props)=>{
    const { request } = props;

    return (  <motion.tr
      animate={{ x: 0, opacity: 1, }}
      exit={{ x: 100, opacity: 0 }} className="text-blue__color " >
    <td scope="row "><a href="">{request.tuition}</a></td>
    <td className="d-none d-lg-table-cell">{ `${request.names} ${request.firstLastName} ${request.secondLastName}` }</td>
    <td className="d-none d-lg-table-cell">{request.email}</td>
    <td className="d-none d-lg-table-cell">{request.program}</td>
    <td>{request.career}</td>
    <td className="d-lg-table-cell">{request.id}</td>
    <td className="d-lg-table-cell">{request.device}</td>
    <td  >   
      <button className="btn btn-success me-1" type="button" onClick={()=>{props.onActions('accept', request.id)}}><i className="fa-solid fa-check"></i></button>
      <button className="btn btn-danger" type="button" onClick={()=>{props.onActions('reject', request.id)}}><i className="fa-solid fa-xmark"></i></button>
     
     </td>
  </motion.tr>)
}
export default TablaRowSolicitud;
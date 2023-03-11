import React from "react"
import ReactDOM from 'react-dom';
const ModalEngine= (props)=>{


    return ReactDOM.createPortal(
        <div className="modal " style={{display:"block"}}>
  <div className="modal-dialog">
    <div className="modal-content">
      <div className="modal-header">
        <h5 className="modal-title" id="exampleModalLabel">{props.title}</h5>
        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick={()=>props.setIsModalActive(false)}></button>
      </div>
      <div className="modal-body">
        {props.message}
      </div>
      <div className="modal-footer">
        <button type="button" className="btn btn-secondary" onClick={()=>props.setIsModalActive(false)}>Cancelar</button>
        <button type="button" className="btn text-white ss-btn" onClick={()=>props.modalHandler()}>Aceptar</button>
      </div>
    </div>
  </div>
</div>,document.getElementById("ModalEngine")
    )
}

export default ModalEngine;
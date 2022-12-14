import React from "react"

const RelojTiempo = (props)=>{
    return(<div className="mx-3">
    <h2 className="border-1 fw-normal border-bottom pb-2 border-secondary mx-auto text-center mb-3"
    style={{fontSize: '4.2rem'}}>
        <span className="digits">
    {("0" + Math.floor((props.time / 3600000)))}<span className="fs-4 fw-bold">h:</span>
  </span>
        <span className="digits">
    {("0" + Math.floor((props.time / 60000) % 60)).slice(-2)}<span className="fs-4 fw-bold">m:</span>
  </span>
  <span className="digits">
    {("0" + Math.floor((props.time / 1000) % 60)).slice(-2)}<span className="fs-4 fw-bold">s</span>
  </span>
  <span className="digits mili-sec d-none">
    :
    {("0" + ((props.time / 10) % 100)).slice(-2)}
  </span></h2>
</div>)
}

export default RelojTiempo;

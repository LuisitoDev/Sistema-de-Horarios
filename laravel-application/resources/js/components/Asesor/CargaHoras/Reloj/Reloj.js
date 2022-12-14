import React from "react"
import RelojTiempo from "./RelojTiempo";
import RelojAcciones from "./RelojAcciones"

const Reloj= (props)=>{

    return(
    <section className="clock-container" style={{marginBottom: '20px'}}>
    <RelojTiempo time={props.time}/>
    {props.isLoading &&
        <div className="text-center">
            <div className="spinner-grow" role="status" style={{color: '#1e4ea1'}}>
                <span className="visually-hidden">Loading...</span>
            </div>
        </div>
    }
    {!props.isLoading &&
        <div className="text-center mb-2 w-100 fw-light" style={{fontSize: '14px'}}>{props.message}</div>
    }
    <RelojAcciones isActive={props.isActive}
        handleStart={props.handleStart}
        setIsModalActive={props.setIsModalActive}/>
    </section>
    );
}
export default Reloj;

import React, { useEffect, useState } from "react"
import ReactDOM from 'react-dom';
import { motion } from 'framer-motion';
import { variantsWebFade } from "../../utils/AnimationVariants";
const AlertEngine= (props)=>{
    const message=props.message;
    const [type, setType]=useState("");
    const [alertType,setAlertType]=useState("");
    
    useEffect(()=>{
        switch(props.type){
            case "Success":{
                setType("Éxito");
                setAlertType("alert-success");
            }

            break;
            case "Failure":{
                setType("Error");
                setAlertType("alert-danger");
                
            }
            break;
            case "Warning":{
                setType("Advertencia");
                setAlertType("alert-warning");
            }

            default:
        }
    })
    return (
        <motion.div  variants={variantsWebFade}
            initial="fadeOut"
            animate="fadeIn"
            exit="fadeOut" className={"alert my-2 alert-dismissible fade show " + 
            alertType }  role="alert">
        <strong>{type}</strong> {message}
    <button type="button" onClick={()=>props.setIsAlertActive(false)} className="btn-close"></button>
</motion.div>)
    
}

export default AlertEngine;
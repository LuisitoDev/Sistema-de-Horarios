import React, {useRef, useEffect, useState} from 'react'
import {AnimatePresence, motion} from 'framer-motion'
import ReactDOM from 'react-dom'
import axios from 'axios'

import logoFcfm from "../../../../../public/assets/Logo-FCFM.png"
import * as DevicesServices from '../../../services/DeviceServices'


const AgregarDispositivo = () => {
    const emailInputRef = useRef(null)
    //// const email = useState(null)

   const [error, setError] = useState(false)
   const [errDesc, setErrDesc] = useState("")

    const submitHandler = (e) => {
        e.preventDefault();
       

        DevicesServices.SignInDevice(emailInputRef.current.value).then(res => {
            const {data} = res
            console.log(res)
            if(data.STATUS === 'SUCCESS'){  
               document.location.reload()
            }
        }).catch(err => {
            setError(true)
            setErrDesc(err.response.data.MESSAGE + err.response.data.FILE)
        })

    }

    return (
        <>
            <div className="row justify-content-center">
                <div className="col col-md-10 col-xl-7 p-4">
                    <img src={logoFcfm} className="img-fluid logo-registro m-auto d-block mt-5"  alt="Logo De la facutldad de ciencias fisico matematicas" />
                    <h5 className="text-center h2 my-2 display-3">Agregar Dispositivo</h5>
                    <hr/>
                    <p className='fw-light text-center'>
                        Ingresa tu correo universitario para mandar una solicitud al administrador para agregar un 
                        nuevo dispositivo.
                    </p>
                    <form action="" className="w-100" onSubmit={submitHandler}>
                        <label className="form-label fw-bold w-100 text-center box" htmlFor="email">
                                Correo universitario:
                        </label>
                        <div className="input-group mb-3">
                            <span className="input-group-text bg-transparent border-0">
                                <i className="fa-solid fs-3 fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                id="email"
                                className="form-control rounded-2"
                                name="correo"
                                placeholder="correo@uanl.edu.mx"
                                ref={emailInputRef}
                                required
                            />
                        </div>
                        <AnimatePresence> 
                        { error && 
                          <motion.div
                          initial={{
                            opacity:0
                          }}
                          animate={{
                            opacity:1,
                            transition:{
                              duration:0.5
                            }
                          }}
                          exit={{
                            opacity:0,
                            transition:{
                              duration:0.5
                            }
                          }}
                          >
                            <span className="ms-5 fw-bold text-danger">{errDesc}</span>
                          </motion.div>
                        }
                        </AnimatePresence>
                        <button className="m-auto btn button-color text-white fs-5 px-4 py-2 d-block"
                            style={{
                                background: "#1e4ea1",
                                borderBottom: "5px solid",
                                borderBottomColor: "#163C7C",
                            }}
                        >
                            Enviar solicitud
                        </button>
                    </form>
                </div>
            </div>
        </>
    );
}

export default AgregarDispositivo

if(document.getElementById('AgregarDispositivo')){
    ReactDOM.render(<AgregarDispositivo/>, document.getElementById('AgregarDispositivo'));
}
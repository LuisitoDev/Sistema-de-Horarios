import React, {useState } from "react";
import axios from "axios"
import {AnimatePresence, motion} from "framer-motion"

import ReactDOM from 'react-dom'
import logoFcfm from "../../../../../public/assets/Logo-FCFM.png"
import MobileNavbar from "../../General/MobileNavbar";

import * as AdminServices from '../../../services/AdminServices'

const Login = (props)=>{

  const [formValues, setfromValues] = useState({
    username: "",
    password: ""
  })

  const handleOnChange = (e) => {
    const {name, value} = e.target

    setfromValues(old => {
      return{
        ...old,
        [name]: value
      }
    })
  }

  const [error, setError] = useState(false)
  const [errDesc, setErrdesc] = useState("")

  const loginSubmit = async (e) => {
    e.preventDefault()
    setError(false)

    if(formValues.username == "" || formValues.password == ""){
      setError(true)
      setErrdesc("Error!, todos los campos son requeridos")

    }else{

      AdminServices.SignIn(formValues.username, formValues.password).then(response => {
        const {data} = response

        if(data.STATUS === 'SUCCESS'){
          window.location.replace('/admin')

        } 


      }).catch(error => {
          setError(true)
          setErrdesc(error.response.data.MESSAGE)
      })


    }
  }

    return (<>
        <main className="container-fluid min-vh-100">
          <div className="row justify-content-center">
              <div className="col col-md-10 col-xl-7 p-0">
                
              <div className="text-center my-5">
                  <img src={logoFcfm} alt="logo" width="150px"/>
                  <h4 className="my-3 display-4">Departamento de Asesorías</h4>
              </div>
        
                <div className="mb-3 d-flex">
                  <div className="container">
                    <form id="login" onSubmit={loginSubmit}>
                    
                      <label className="form-label fw-bold ms-5">Username</label>
                      <div className="input-group mb-3">
                        <span className="input-group-text bg-transparent border-0" id="basic-addon1">
                          <i className="fa-solid fa-user"></i>
                        </span>
                        <input
                          type="text"
                          className="form-control rounded-2"
                          name="username"
                          placeholder="Ingresa tu usuario"
                          onChange = {handleOnChange}
                        />
                      </div>
                      
                      <label  className="form-label fw-bold ms-5">Password</label>
                      <div className="input-group mb-3">
                        <span className="input-group-text bg-transparent border-0" id="basic-addon1">
                          <i className="fa-solid fa-lock"></i>
                        </span>
                        <input
                          type="password"
                          className="form-control rounded-2"
                          name="password"
                          placeholder="Ingresa tu contraseña"
                          onChange= {handleOnChange}
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
                      <br/>
                      <br/>
                      <div className="d-flex justify-content-center mb-3">
                        <button  className="btn button-color text-white fs-5 px-4 py-2" type="submit">
                          Login
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </main>
        <MobileNavbar/>
    </>)
}
export default Login;

if(document.getElementById('Login')){
    ReactDOM.render(<Login/>, document.getElementById('Login'));
}
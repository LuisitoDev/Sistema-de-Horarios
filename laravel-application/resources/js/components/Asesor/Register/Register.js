import React from "react";
import ReactDOM from 'react-dom'


const Register = ()=>{
    return (
      <>
        <h4 className="text-center h2 my-5 display-3">Registro</h4>
        <div className="w-50 m-auto text-center">
          <p>¿Ya estas registrado?</p>
          <form method="post" action="/signin-device">
            <div className="form-group mt-3 mb-3">
              <label htmlFor="email" className="form-label">Ingresa tu correo universitario:</label>
              <input type="email" id="email" className="form-control" placeholder="Corre universitario"/>
            </div>
            <button className="btn button-color text-white fs-5 px-4 py-2">Haz click aqui para agregar tu dispositivo</button>
          </form>

        </div>
      </>
    )
}

export default Register;

if(document.getElementById('Register')){
  ReactDOM.render(<Register/>, document.getElementById('Register'));
}
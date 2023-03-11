import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import RegistroAlumno from "./Registros/RegistroAlumno";
import RegistroDispositivo from "./Registros/RegistroDispositivo";
import logoFcfm from "../../../../../public/assets/Logo-FCFM.png"

const Registro = () => {
    return (
        <div className="row justify-content-center">
            <div className="col col-md-10 col-xl-7 p-0">
            <img src={logoFcfm} className="img-fluid logo-registro m-auto d-block mt-5"  alt="Logo De la facutldad de ciencias fisico matematicas" />
                <h5 className="text-center h2 my-2 display-3">Registro</h5>
                <p className=" d-block w-75 m-auto text-center no-device mb-2"><b>¿No te inicia sesión en automático y ya estas registrado?</b> Tal vez no tienes este dispositivo registrado. </p>
                {/* <br/><a href="#">Haz click aquí</a> */}
                <div className="no-device w-100 text-center">
                    <a href="/registro/dispositivo" >Haz click aqui</a>
                </div>
                {/* <form action="" className="no-device w-100">
                    <button className="m-auto d-block">Haz click aqui</button>
                </form> */}
                <hr />
                {/* <div className="mb-3 d-flex">
                    <div className="container">
                        <form action="#" id="registro">
                            <label className="form-label fw-bold ms-5">
                                Matricula:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                    <i className="fa-solid fs-3 fa-hashtag"></i>
                                </span>
                                <input
                                    type="text"
                                    className="form-control rounded-2"
                                    name="matricula"
                                    placeholder="Matricula"
                                    required
                                    input-field="true"
                                />
                            </div>

                            <label className="form-label fw-bold ms-5">
                                Nombre completo:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                    <i className="fa-solid fs-3 fa-user"></i>
                                </span>
                                <input
                                    type="text"
                                    className="form-control rounded-2"
                                    name="nombre"
                                    placeholder="Nombre"
                                    required
                                />
                            </div>

                            <label className="form-label fw-bold ms-5">
                                Apellido Paterno:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                <i className="fa-solid fs-3 fa-user"></i>
                                </span>
                                <input
                                    type="text"
                                    className="form-control rounded-2"
                                    name="apellidop"
                                    placeholder="Apellido Paterno"
                                    required
                                />
                            </div>

                            <label className="form-label fw-bold ms-5">
                                Apellido Materno:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                    <i className="fa-solid fs-3 fa-user"></i>
                                </span>
                                <input
                                    type="text"
                                    className="form-control rounded-2"
                                    name="apellidop"
                                    placeholder="Apellido Materno"
                                    required
                                />
                            </div>

                            <label className="form-label fw-bold ms-5">
                                Correo universitario:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                    <i className="fa-solid fs-3 fa-envelope"></i>
                                </span>
                                <input
                                    type="text"
                                    className="form-control rounded-2"
                                    name="correo"
                                    placeholder="Correo@uanl.edu.mx"
                                    required
                                />
                            </div>

                            <label className="form-label fw-bold ms-5 ">
                                Carrera:
                            </label>
                            <div className="input-group">
                                <span className="input-group-text bg-transparent border-0">
                                    <i className="fa-solid fs-3 fa-landmark"></i>
                                </span>
                                <select
                                    name="carrera"
                                    className="form-select rounded-2"
                                >
                                    <option value="1">LMAD</option>
                                    <option value="2">LCC</option>
                                    <option value="3">LSTI</option>
                                    <option value="4">LA</option>
                                    <option value="5">LM</option>
                                    <option value="6">LF</option>
                                </select>
                            </div>
                            <br></br>

                            <label className="form-label fw-bold ms-5">
                                Servicio:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                    <i className="fa-solid fs-3 fa-paste"></i>
                                </span>
                                <select
                                    name="servicio"
                                    className="form-select rounded-2"
                                >
                                    <option value="1">Servicio social</option>
                                    <option value="2">Talentos</option>
                                    <option value="3">Becarios</option>
                                </select>
                            </div>
                            <label className="form-label fw-bold ms-5">
                                Programa:
                            </label>
                            <div className="input-group mb-3">
                                <span className="input-group-text bg-transparent border-0">
                                <i className="fa-solid fs-3 fa-display"></i>
                                </span>
                                <select
                                    name="servicio"
                                    className="form-select rounded-2"
                                >
                                    <option value="1">Creacion de videos</option>
                                    <option value="2">Asesor</option>
                                </select>
                            </div>
                            <br></br>
                            <div className="d-flex justify-content-center mb-4">
                                <button
                                    className="btn button-color text-white fs-5 px-4 py-2"
                                    type="submit"
                                    style={{
                                        background: "#1e4ea1",
                                        borderBottom: "5px solid",
                                        borderBottomColor: "#163C7C",
                                    }}
                                >
                                    Registrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div> */}
                {/* Esta linea de abajo se tiene que descomentar cuando Moncayo le de Mantenimiento */}
                {/* <RegistroAlumno></RegistroAlumno> */}
            </div>
        </div>
    );
};

export default Registro;

if (document.getElementById("Registro")) {
    ReactDOM.render(<Registro />, document.getElementById("Registro"));
}

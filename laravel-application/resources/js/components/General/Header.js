import axios from 'axios';
import React, { useEffect, useState } from 'react'
import UserType from '../../utils/UserEnum';
import View from '../../utils/ViewEnum';
import { readableDateMonthYear } from '../../utils/TimeFunctions'
import ModalEngine from './ModalEngine';

import CicloEscolar from '../Admin/CicloEscolar/CicloEscolar';

import * as AdminServices from '../../services/AdminServices';

const Header = (props) => {


    const { dayFrom, dayTo } = props;

    const [schoolCycles, setSchoolCycles] = useState([]);


    //Modal
    const [isModalActive, setIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");

    const modalHandler=()=>{
        setIsModalActive(false);
    }

    const showUpExceptionModal = (exception) => {
        if(!exception.response.data.MESSAGE === "") setModalMessage(exception.response.data.MESSAGE);
        else setModalMessage("Hubo un error en el servidor");
        setIsModalActive(true);
    }

    // useEffect(() => {
    //     if(props.userType === UserType.Admin) {
    //         AdminServices.GetSchoolCycles().then(response => {
    //             setSchoolCycles(response.data.ciclosEscolares);

    //             dayFrom.current = response.data.ciclosEscolares[0].fecha_ingreso;
    //             dayTo.current = response.data.ciclosEscolares[0].fecha_salida;

    //             props.setSelectedSchoolCycle(response.data.ciclosEscolares[0].id);
    //         }).catch(exception => {
    //             showUpExceptionModal(exception)
    //         })
    //     }
    // }, [])

    // const handleChange = (e) => {
    //     props.setSelectedSchoolCycle(e.target.value);
    // };

    // useEffect(() => {
    //     if(props.userType === UserType.Admin) {
    //         if (props.selectedSchoolCycle !== 0){
    //             var formData = new FormData();
    //             formData.append("Id_Ciclo_Escolar", props.selectedSchoolCycle);
    //             AdminServices.SetSchoolCycles(formData).then((response)=>{
    //                 console.log(response);
    //             }).catch((exception)=>{
    //                 console.log(exception);
    //                 showUpExceptionModal(exception);
    //             });
    //             console.log(props.selectedSchoolCycle)
    //         }
    //     }
    // }, [props.selectedSchoolCycle])


    const logoutHandler = (e) => {
        e.preventDefault()
        if(props.userType === UserType.Admin) {
            AdminServices.SignOut().then(response => {
                window.location.replace('/admin/login');
            }).catch(exception => {
                showUpExceptionModal(exception);
                //   setError(true)
                //   setErrdesc(error.response.data.MESSAGE)
            })
        }
    }
    return(
        <>
            <header className="row position-fixed w-100" style={{marginBottom: "150px", zIndex: 10}}>
            <nav className="navbar navbar-expand-lg button-color col-lg-12 d-none d-lg-block">

                <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon  text-white "></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarText">
                    <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                        <li className="nav-item">
                            <span className="nav-link active text-white fw-bold me-4 " aria-current="page" href="#">Registro FCFM</span>
                        </li>

                        { props.userType === UserType.Admin &&
                            <>
                                <li className="nav-item">
                                    <a onClick={(e) => { props.setView(View.Alumnos) }} className="nav-link text-white " href="#">Alumnos</a>
                                </li>
                                <li className="nav-item">
                                    <a onClick={(e) => { props.setView(View.Solicitudes) }} className="nav-link text-white " href="#">Solicitudes</a>
                                </li>
                                <li className="nav-item">
                                    <a onClick={(e) => { props.setView(View.RelojAdmin) }} className="nav-link text-white " href="#">Reloj</a>
                                </li>
                                <li className="nav-item">
                                    {/* <form action='/admin/logout' method='POST'>
                                        <button className='nav-link text-white'>Cerrar sesión</button>
                                    </form> */}
                                    <a className="nav-link text-white" onClick={logoutHandler} style={{cursor:'pointer'}}>Cerrar sesión</a>
                                </li>
                                <li className="nav-item d-flex justify-content-center">
                                    {/* <select className="bg-transparent text-white border-0 " onChange={handleChange} value={selectedSchoolCycle} name="select">
                                        {schoolCycles &&
                                            schoolCycles.map((schoolCycle) => (
                                                <option key={schoolCycle.id} value={schoolCycle.id}>{readableDateMonthYear(schoolCycle.fecha_ingreso) + " - " +  readableDateMonthYear(schoolCycle.fecha_salida)}</option>
                                            ))
                                        }
                                    </select> */}
                                     <CicloEscolar selectedSchoolCycle={props.selectedSchoolCycle} setSelectedSchoolCycle={props.setSelectedSchoolCycle} dayFrom={dayFrom} dayTo={dayTo}/>
                                </li>
                            </>
                        }

                        { props.userType === UserType.Asesor &&
                            <>
                                <li className="nav-item">
                                    <a onClick={(e) => { props.setView(View.Perfil) }} className="nav-link text-white " href="#">Perfil</a>
                                </li>
                                <li className="nav-item">
                                    <a onClick={(e) => { props.setView(View.CargaHoras) }} className="nav-link text-white " href="#">Carga de horas</a>
                                </li>
                                <li className="nav-item">
                                    <a onClick={(e) => { props.setView(View.Progreso) }} className="nav-link text-white " href="#">Progreso</a>
                                </li>
                            </>
                        }

                    </ul>

                </div>
            </nav>
            </header>
            { isModalActive &&  <ModalEngine  modalHandler={modalHandler} setIsModalActive={setIsModalActive}
            title="Error"
            message={modalMessage}
            /> }
        </>
    )
}

export default Header;

import React, { useEffect, useRef, useState } from 'react'
import ReactDOM from 'react-dom'
import View from '../../../utils/ViewEnum';
import usePagination from '../../../hooks/usePagination';
import { motion } from 'framer-motion';
import { useIsViewportLarge } from '../../../hooks/useMediaQuery';
import { variantsMobileSwipe, variantsWebFade } from "../../../utils/AnimationVariants";
import bisontito from "../../../../../public/images/bisontito.png"
import HeaderProgreso from './HeaderProgreso';
import InfoProgreso from './InfoProgreso';
import {getDateToParam, createDateFromString} from "../../../utils/TimeFunctions"

import * as AlumnosServices from '../../../services/AlumnosServices';
import LoadingEngine from '../../General/LoadingEngine';

import ModalEngine from '../../General/ModalEngine';

const Progreso = (props) => {
    //Es el enum de la vista en la que nos encontramos
    const view = View.Progreso;

    //Total de Horas que ha hecho el alumno, y Horas que tendrá que hacer el servicio
    const [totalHours, setTotalHours] = useState(0);
    const [hoursService, setHoursService] = useState(0);
    const [pendingHours, setPendingHours] = useState(0);
    const [valueCalendar, setValueCalendar] = useState(new Date());
    const dayFrom = useRef(null);
    const dayTo = useRef(null);


    //Este será el número total de páginas que tendra nuestra paginación
    const [totalPages, setTotalPages] = useState(0);

    const [isLoadingRequest,setIsLoadingRequest] = useState(true)


    //Este hook devuelve 2 funciones que manejan los eventos para navegar hacia enfrente o hacia atras en la paginación
    //Tambien devuelve la variable que contiene el número de la página actual
    const { currentPage, NextPageHandler, PrevPageHandler, setCurrentPage } = usePagination(totalPages);

    //Horas realizadas en cada entrada
    const [hours, setHours] = useState([]);

    const isViewportLarge = useIsViewportLarge();
    //Estos arreglos dummies simulan ser el resultado de la petición que se generará aquí
    const applyFilterEntradas = () => {
        setCurrentPage(1);
        setIsLoadingRequest(true);
        searchEntradas();
        updateInfoProgress();
    }

    //Modal
    const [isModalActive, setIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");

    const modalHandler=()=>{
        setIsModalActive(false);
    }

    const showUpExceptionModal = (exception) => {
        setIsLoadingRequest(false);
        setModalMessage(exception.response.data.MESSAGE);
        setIsModalActive(true);
    }

    const searchEntradas = () => {

        AlumnosServices.getEntradas(5, currentPage, getDateToParam(createDateFromString(dayFrom.current)), getDateToParam(createDateFromString(dayTo.current))).then(response => {
            const {data} = response

            setHours(data.entradas);

        }).catch((exception)=>{
            setIsLoadingRequest(false)
            showUpExceptionModal(exception)
        })
    }

    //Por ahora solo se hace un flip flop entre los dummies de arriba pero en teoría aquí se haría la petición
    useEffect(() => {
        searchEntradas();
    }, [currentPage])

    const updateInfoProgress = () => {
        AlumnosServices.getProgresoGeneral(5, getDateToParam(createDateFromString(dayFrom.current)), getDateToParam(createDateFromString(dayTo.current))).then(response => {
            const {data} = response

            setTotalPages(data.cantidad_paginas);
            setTotalHours(data.horas_realizadas);
            setHoursService(data.horas_servicio);
            setPendingHours(data.horas_pendientes);
            setIsLoadingRequest(false);

        }).catch(err => {
            setIsLoadingRequest(false)
            alert(err.response.data.MESSAGE)
        })

    }

    useEffect(()=>{
        updateInfoProgress();
    }, [])

    return(
        <>
        { !isViewportLarge &&

        <motion.div className="row justify-content-center pt-lg-5"
        variants={variantsMobileSwipe}
        initial="swipeOutToRight"
        animate="swipeIn"
        exit="swipeOutToRight"
        >
            <div className="col col-md-10 col-xl-8 mt-lg-4">
                <HeaderProgreso totalHours={totalHours} hoursService={hoursService} pendingHours={pendingHours}/>
                {
                    isLoadingRequest &&
                    <LoadingEngine/>
                }
                {!isLoadingRequest &&
                    <InfoProgreso view={view} totalPages={totalPages} hours={hours}
                        currentPage={currentPage} NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}
                        dayFrom={dayFrom} dayTo={dayTo} applyFilter={applyFilterEntradas}
                        valueCalendar={valueCalendar} setValueCalendar={setValueCalendar}/>
                }
            </div>
        </motion.div>
        }

        {
        isViewportLarge && <motion.div className="row justify-content-center pt-lg-5"
        variants={variantsWebFade}
        initial="fadeOut"
        animate="fadeIn"
        exit="fadeOut"
        >
            <div className="col col-md-10 col-xl-8 mt-lg-4">
                <HeaderProgreso totalHours={totalHours} hoursService={hoursService} pendingHours={pendingHours}/>
                {
                    isLoadingRequest &&
                    <LoadingEngine/>
                }
                {!isLoadingRequest &&
                    <InfoProgreso view={view} totalPages={totalPages} hours={hours}
                        currentPage={currentPage} NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}
                        dayFrom={dayFrom} dayTo={dayTo} applyFilter={applyFilterEntradas}
                        valueCalendar={valueCalendar} setValueCalendar={setValueCalendar}/>
                }
            </div>
        </motion.div>
        }
        { isModalActive &&  <ModalEngine  modalHandler={modalHandler} setIsModalActive={setIsModalActive}
        title="Error"
        message={modalMessage}
        /> }
        </>

    )
}

export default Progreso;

if(document.getElementById('Progreso')){
    ReactDOM.render(<Progreso/>, document.getElementById('Progreso'))
}

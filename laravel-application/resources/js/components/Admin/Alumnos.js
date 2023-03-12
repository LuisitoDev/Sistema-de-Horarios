import React, { useEffect, useRef, useState } from 'react';
import ReactDOM from 'react-dom';
import usePagination from '../../hooks/usePagination';
import View from '../../utils/ViewEnum';
import MatriculaBusqueda from './MatriculaBusqueda/MatriculaBusqueda';
import Paginacion from './Paginación/Paginacion';
import Tabla from './Tabla/Tabla';
import ButtonTabs from '../../utils/ButtonTabs';
import { motion } from 'framer-motion';
import bisontito from "../../../../public/images/bisontito.png"
import { useIsViewportLarge } from "../../hooks/useMediaQuery";
import { variantsMobileSwipe, variantsWebFade } from "../../utils/AnimationVariants";
import ModalEngine from '../General/ModalEngine';

import * as AlumnosServices from '../../services/AlumnosServices'
import CicloEscolar from './CicloEscolar/CicloEscolar';
import DatePickerEngine from '../General/DatePickerEngine';

const Alumnos = (props)=>{
    const selectedSchoolCycle = props.selectedSchoolCycle;
    const setSelectedSchoolCycle = props.setSelectedSchoolCycle;

    const dayFrom = props.dayFrom ;
    const dayTo = props.dayTo;

    //Es el enum de la vista en la que nos encontramos
    const [view, setView] = useState(View.Alumnos);
    //Este será el número total de páginas que tendra nuestra paginación, el valor de la variable DEBE venir del back
    // const totalPages = 2;
    const [totalPages, setTotalPages] = useState(0)
    //Este hook devuelve 2 funciones que manejan los eventos para navegar hacia enfrente o hacia atras en la paginación
    //Tambien devuelve la variable que contiene el número de la página actual
    const { currentPage, NextPageHandler, PrevPageHandler, setCurrentPage } = usePagination(totalPages);
    //Este state manejara el arreglo de objetos que contendran el registro de horas, en este momento llenado por los dummies de abajo.
    const [students, setStudents] = useState([]);

    const [tuition, setTuition] = useState(null);

    //Este hook nos dira si la pantalla cambia de tamaño y con ello podremos usar animaciones responsivas, llamenlo ResponsiveAnimationsEngine
    const isViewportLarge = useIsViewportLarge();

    const [isLoadingRequest, setIsLoadingRequest] = useState(true);

    //Exception Modal
    const [isModalActive, setIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");


    // Deleting Confirmation Modal
    const [isDeletingModalActive, setIsDeletingModalActive] = useState(false);
    const [deletingTuition, setDeletingTuition] = useState(null)
    const [deletingModalMessage, setDeletingModalMessage] = useState("");

    const [isDeletingOldDataModalActive, setIsDeletingOldDataModalActive] = useState(false)

    const isInitialMount = useRef(true);

    const [valueCalendar, setValueCalendar] = useState(new Date());

    const searchData = () => {

        setIsLoadingRequest(true);

        if(currentPage === 1) {
            if(view === View.Alumnos){
                getAlumnos();
            } else if(view === View.AlumnosEntradas) {
                getAlumnosEntradas();
            }
        }

        setCurrentPage(1);
    }

    const applyFilterEntradas = () => {
        searchData();
    }

    useEffect(() => {
        if (isInitialMount.current && dayFrom.current !== null && dayTo.current !== null) {
            searchData();
            isInitialMount.current  = false
        }

    }, [dayFrom])


    useEffect(() => {
        searchData();

    }, [selectedSchoolCycle])


    const modalErrorHandler=()=>{
        setIsModalActive(false);
    }

    const modalDeletingHandler = () => {
        setIsDeletingModalActive(false);
        AlumnosServices.DeleteStudent(deletingTuition).then(res => {
            const {data} = res

            if(data.STATUS === 'SUCCESS'){
                setStudents(oldStudents => {
                    return oldStudents.filter(s => s.tuition !== deletingTuition)
                })


            } else if(data.STATUS === 'ERROR'){
                throw new Error('Algo ha salido mal');
            }
        }).catch((exception)=>{
            showUpExceptionModal(exception)
        });
    }

    const modalOldDataHandler = () => {
        setIsDeletingOldDataModalActive(false);
        AlumnosServices.DeleteOldData().then(res => {
            const {data} = res
            if(data.STATUS === 'SUCCESS'){
                setCurrentPage(1)
                getAlumnnos()
            }
        }).catch(exception => {
            showUpExceptionModal(exception)
        });
    }

    const showUpExceptionModal = (exception) => {
        setIsLoadingRequest(false);
        if(!exception.response.data.MESSAGE === "") setModalMessage(exception.response.data.MESSAGE);
        else setModalMessage("Hubo un error en el servidor")
        setIsModalActive(true);
    }

    const showUpDeletingModal = (tuition) => {
        setDeletingModalMessage("¿Esta seguro que desea eliminar a este alumno?")
        setDeletingTuition(tuition);
        setIsDeletingModalActive(true);
    }

    const showUpDeletingOldDataModal = () => {
        setDeletingModalMessage("¿Esta seguro que desea eliminar todos los registros de hace mas de 3 años, esto incluye el registro de todos los alumnos sus entradas asi como turnos y horarios?")
        setIsDeletingOldDataModalActive(true);
    }

    const getAlumnos = () => {
        if (dayFrom.current === null || dayTo.current === null)
            return;

        AlumnosServices.GetStudents(5, currentPage, tuition, dayFrom.current, dayTo.current).then(response => {
            const {data} = response

            setIsLoadingRequest(false)
            const totalPagesBack = data.totalPages;

            setTotalPages(totalPagesBack)

            const studentsMapped = data.students.map(student =>  {
                return {
                    tuition: student.matricula,
                    names: student.nombre,
                    firstLastName: student.apellido_pat,
                    secondLastName: student.apellido_mat,
                    email: student.correo_universitario,
                    career: student.carrera.abreviacion,
                    program: student.servicios[0].nombre,
                }
            })


            setStudents(studentsMapped)
        }).catch((exception)=>{
            setIsLoadingRequest(false);
            //TODO: Checar porque marca 3 errores antes de que sea un success da un falso negativo
            // setModalMessage(exception.response.data.MESSAGE);
            // setIsModalActive(true);
        })
    }

    const getAlumnosEntradas = () => {
        if (dayFrom.current === null || dayTo.current === null)
            return;

        AlumnosServices.GetStudentsChecks(5, currentPage, tuition, dayFrom.current, dayTo.current).then(response => {
            const {data} = response

            setIsLoadingRequest(false)
            const totalPagesBack = data.totalPages;

            setTotalPages(totalPagesBack)
            
            if (data.students !== null){

                const studentsMapped = data.students.map(student =>  {
                    return {
                        tuition: student.matricula,
                        names: student.nombre,
                        firstLastName: student.apellido_pat,
                        secondLastName: student.apellido_mat,
                        email: student.correo_universitario,
                        career: student.carrera.abreviacion,
                        program: student.servicios[0].nombre,
                        checkIns: student.cant_entradas,
                        pendingHours: student.horas_pendientes === null ? 0 : student.horas_pendientes,
                        // completeChecks: student.entradas_completadas,
                        // incompleteChecks: student.entradas_incompletadas,
                        // missingChecks: student.entradas_faltantes,
                    }
                })

                setStudents(studentsMapped)
            }
        }).catch((exception)=>{
            showUpExceptionModal(exception)
        })
    }

    useEffect(() => {
        if(view === View.Alumnos){
            getAlumnos();
        } else if(view === View.AlumnosEntradas) {
            getAlumnosEntradas()
        }
    }, [currentPage])

    useEffect(() => {
        searchData();
    }, [view])

    useEffect(()=> {
        if(view === View.Alumnos){
            setCurrentPage(1);
            getAlumnos();
        } else if(view === View.AlumnosEntradas) {
            setCurrentPage(1);
            getAlumnosEntradas();
        }
    }, [tuition])


    const actionsHandler = (action, tuition) => {
        switch(action) {
            case 'edit':

                break
            case 'delete':
               showUpDeletingModal(tuition)
        }
    }

    const clickHandlerButtonFile = (e)=>{
        e.preventDefault();
        let button = document.getElementById('id_ile');
        button.click();
    }

    const submitHandlerButtonFile = (e)=>{
        e.preventDefault();

        let button = document.getElementById('id_ile');

        if(button.value != ""){
            console.log("Si tiene archivo");
            setIsLoadingRequest(true);

            var formData = new FormData();
            var excelFile = document.querySelector('#id_ile');
            formData.append("select_file", excelFile.files[0]);

            if (view == View.Alumnos){
                AlumnosServices.ImportStudents(formData).then(response => {
                    const {data} = response;

                    getAlumnos();

                }).catch((exception)=>{
                    showUpExceptionModal(exception)
                })
            }
            else if (view == View.AlumnosEntradas){
                AlumnosServices.ImportEntradas(formData).then(response => {
                    const {data} = response;

                    getAlumnosEntradas();

                }).catch((exception)=>{
                    showUpExceptionModal(exception)
                })
            }

        }
    }

    const clickHandlerButtonExportAlumnos = (e) => {
        e.preventDefault();

        if (dayFrom.current === null || dayTo.current === null)
            return;

        AlumnosServices.ExportStudents(dayFrom.current, dayTo.current)
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `Reporte Alumnos ${dayFrom.current} - ${dayTo.current}.xlsx`);
            document.body.appendChild(link);
            link.click();
        })
        .catch((exception)=> {
            showUpExceptionModal(exception)
        })
    }

    const clickHandlerButtonExportAlumnosEntradas = (e) => {
        e.preventDefault();

        if (dayFrom.current === null || dayTo.current === null)
            return;

        AlumnosServices.ExportEntradas(dayFrom.current, dayTo.current)
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `Reporte Entradas  ${dayFrom.current} - ${dayTo.current}.xlsx`);
            document.body.appendChild(link);
            link.click();
        })
        .catch((exception)=> {
            showUpExceptionModal(exception)
        })
    }


    const deleteOldRegistersHandler = (e) => {
        showUpDeletingOldDataModal()
    }

return (
    <>
    { !isViewportLarge &&
        <motion.div className="col-12 col-lg-10 container p-0 mt-lg-4"
        variants={variantsMobileSwipe}
        initial="swipeOutToLeft"
        animate="swipeIn"
        exit="swipeOutToLeft"
        >
            {isLoadingRequest &&
                <div className="d-flex flex-column justify-content-center align-items-center  vh-100">
                    <img src={bisontito} style={{height:"200px"}} />
                    <h1 >Estamos procesando tu solicitud</h1>
                    <div className="spinner-border" role="status">
                        <span className="sr-only">Loading...</span>
                    </div>
                </div>
            }
            {!isLoadingRequest &&
            <>
                <DatePickerEngine valueCalendar={valueCalendar} setValueCalendar={setValueCalendar} applyFilter={applyFilterEntradas} dayFrom={dayFrom} dayTo={dayTo} />
                <MatriculaBusqueda view={view} setTuition={setTuition} deleteOldRegistersHandler={deleteOldRegistersHandler}/>
                <CicloEscolar selectedSchoolCycle={selectedSchoolCycle} setSelectedSchoolCycle={setSelectedSchoolCycle} dayFrom={dayFrom} dayTo={dayTo}/>
                <ButtonTabs view={view} setView={setView}/>
                <Tabla view={view} content={students} onActions={actionsHandler}/>

                {view === View.Alumnos &&
                    <Paginacion view={view} totalPages={totalPages} currentPage={currentPage}
                    clickHandlerButtonFile={clickHandlerButtonFile} submitHandlerButtonFile={submitHandlerButtonFile}
                    clickHandlerButtonExport={clickHandlerButtonExportAlumnos}
                    NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}/>
                }

                {view === View.AlumnosEntradas &&
                    <Paginacion view={view} totalPages={totalPages} currentPage={currentPage}
                    clickHandlerButtonFile={clickHandlerButtonFile} submitHandlerButtonFile={submitHandlerButtonFile}
                    clickHandlerButtonExport={clickHandlerButtonExportAlumnosEntradas}
                    NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}/>
                }
                </>
            }

        </motion.div>
    }
    { isViewportLarge &&
        <motion.div className="col-12 col-lg-10 container p-0 mt-lg-4"
        variants={variantsWebFade}
        initial="fadeOut"
        animate="fadeIn"
        exit="fadeOut"
        >
            {isLoadingRequest &&

                    <div className="d-flex flex-column justify-content-center align-items-center  vh-100">
                        <img src={bisontito} style={{height:"200px"}} />
                        <h1 >Estamos procesando tu solicitud</h1>
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div>
            }
            {!isLoadingRequest &&
                <>
                    <DatePickerEngine valueCalendar={valueCalendar} setValueCalendar={setValueCalendar} applyFilter={applyFilterEntradas} dayFrom={dayFrom} dayTo={dayTo} />
                    <MatriculaBusqueda view={view} setTuition={setTuition} deleteOldRegistersHandler={deleteOldRegistersHandler}/>
                    <hr  style={{height:'2px',borderWidth:'100%',color:'gray',backgroundColor:'rgb(12, 12, 12)'}}/>
                    <ButtonTabs view={view} setView={setView}/>
                    <Tabla view={view} content={students} onActions={actionsHandler}/>

                    {view === View.Alumnos &&
                        <Paginacion view={view} totalPages={totalPages} currentPage={currentPage}
                        clickHandlerButtonFile={clickHandlerButtonFile} submitHandlerButtonFile={submitHandlerButtonFile}
                        clickHandlerButtonExport={clickHandlerButtonExportAlumnos}
                        NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}/>
                    }

                    {view === View.AlumnosEntradas &&
                        <Paginacion view={view} totalPages={totalPages} currentPage={currentPage}
                        clickHandlerButtonFile={clickHandlerButtonFile} submitHandlerButtonFile={submitHandlerButtonFile}
                        clickHandlerButtonExport={clickHandlerButtonExportAlumnosEntradas}
                        NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}/>
                    }
                </>
            }

        </motion.div>
    }

    { isModalActive &&  <ModalEngine  modalHandler={modalErrorHandler} setIsModalActive={setIsModalActive}
    title="Error"
    message={modalMessage}
    /> }

    {isDeletingModalActive && <ModalEngine modalHandler={modalDeletingHandler} setIsModalActive={setIsDeletingModalActive}
    title="Confirmar eliminacion"
    message={deletingModalMessage}
    />}

    {isDeletingOldDataModalActive && <ModalEngine modalHandler={modalOldDataHandler} setIsModalActive={setIsDeletingOldDataModalActive}
    title="Confirmar eliminacion"
    message={deletingModalMessage}
    />}

    </>
)
}
export default Alumnos;


if (document.getElementById('Alumnos')) {

    ReactDOM.render(<Alumnos />, document.getElementById('Alumnos'));
}


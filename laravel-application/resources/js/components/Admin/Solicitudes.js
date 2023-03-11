import React, {useState, useEffect, useRef} from "react";
import ReactDOM from 'react-dom';
import Tabla from "./Tabla/Tabla";
import MatriculaBusqueda from "./MatriculaBusqueda/MatriculaBusqueda";
import Paginacion from "./Paginación/Paginacion";
import View from "../../utils/ViewEnum";
import usePagination from "../../hooks/usePagination";
import { motion, AnimatePresence } from 'framer-motion';
import { useIsViewportLarge } from "../../hooks/useMediaQuery";
import { variantsMobileSwipe, variantsWebFade } from "../../utils/AnimationVariants";
import bisontito from "../../../../public/images/bisontito.png"
import * as DevicesServices from '../../services/DeviceServices'
import AlertEngine from "../General/AlertEngine";
import ButtonTabs from "../../utils/ButtonTabs";
import ModalEngine from "../General/ModalEngine";

const Solicitudes = (props)=>{
    const selectedSchoolCycle = props.selectedSchoolCycle;
    //Es el enum de la vista en la que nos encontramos
    const [view, setView] = useState(View.SolicitudesDispositivos)
    //Este será el número total de páginas que tendra nuestra paginación, el valor de la variable DEBE venir del back
    const [totalPages, setTotalPages] = useState(0)
    //Este hook devuelve 2 funciones que manejan los eventos para navegar hacia enfrente o hacia atras en la paginación
    //Tambien devuelve la variable que contiene el número de la página actual
    const { currentPage, NextPageHandler, PrevPageHandler, setCurrentPage } = usePagination(totalPages);

    //Este state manejara el arreglo de objetos que contendran el registro de horas, en este momento llenado por los dummies de abajo.
    const [requests, setRequests] = useState([]);

    const [isLoadingRequest,setIsLoadingRequest] = useState(true);
    const [viewChanged, setViewChanged] = useState(false);
    //Este hook nos dira si la pantalla cambia de tamaño y con ello podremos usar animaciones responsivas, llamenlo ResponsiveAnimationsEngine
    const isViewportLarge = useIsViewportLarge();
    const [isAlertActive,setIsAlertActive]=useState(false);

    const [alertType, setAlertType]=useState("");
    const [alertMessage,setAlertMessage]=useState("");

    //Modal
    const [isModalActive, setIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");

    const modalHandler=()=>{
        setIsModalActive(false);
    }

    const showUpExceptionModal = (exception) => {
        setIsLoadingRequest(false);
        if(!exception.response.data.MESSAGE === "") setModalMessage(exception.response.data.MESSAGE);
        else setModalMessage("Hubo un error en el servidor");
        setIsModalActive(true);
    }

    const getSolicitudesDispositivos = () => {

        if (selectedSchoolCycle === 0)
            return;

        //TODO: DE MOMENTO ESTA HARDCODEADO EL CICLO ESCOLAR, ARREGLAR
        DevicesServices.GetDevicesRequest(5, currentPage, 3).then(response=> {
            const {data} = response;

            // Numero total de paginas

            const totalPagesBack = data.totalPages;

            setTotalPages(totalPagesBack)
            // Iteracion de solicitudes
            const requestsMapped = data.requests.map(request => {return {
                id: request.id,
                tuition: request.matricula,
                names: request.nombre,
                firstLastName: request.apellido_pat,
                secondLastName: request.apellido_mat,
                email: request.correo_universitario,
                career: request.abreviacion,
                program: request.servicio_nombre,
                device: request.direccion_mac_dispositivo
            }})
            console.log(data)
            setRequests(requestsMapped);
            // setTimeout(()=>{
            //     setIsLoadingRequest(false);
            // },5000)
            setIsLoadingRequest(false);

        }).catch((exception)=>{
            setIsLoadingRequest(false);
            showUpExceptionModal(exception)
            console.log(exception)
        });
    }

    const getSolicitudes = () => {
        setTotalPages(0)
        setRequests([])
    }

    useEffect(() => {
        if(view === View.Solicitudes){
            getSolicitudes();
        } else if(view === View.SolicitudesDispositivos){
            getSolicitudesDispositivos();

        }

    }, [currentPage])

    useEffect(() => {
        // setIsLoadingRequest(true);

        if(currentPage === 1){
            if(view === View.Solicitudes){
                getSolicitudes();
            }
            else if(view === View.SolicitudesDispositivos) {
                getSolicitudesDispositivos();
            }
        }

        setCurrentPage(1);

    }, [view, selectedSchoolCycle])


    //Esta funcion se utiliza para activar el alert de exito o error de alguna peticion, recibe tres parametros
    //type: El tipo de mensaje, hay tres tipos; Success,Failure,Warning
    //message: Describe el mensaje de retroalimentación que quieres darle al usuario
    //active: Recibe un boleano que hace que la alerta se desaparezca del interfaz
    const setupAlert=(type,message,active)=>{
        setAlertType(type);
        setAlertMessage(message);
        setIsAlertActive(active);
        console.log(isAlertActive)
        if(active){

            setTimeout(()=>{
                setIsAlertActive(false);
            },7000)
        }
    }
    const actionsHandler = (action, id) => {
        switch(action) {
            case 'accept':
                DevicesServices.AcceptDeviceRequest(id).then(res => {
                    const {data} = res
                    // TODO Manejar respuestas erroneas y animaciones
                    if(data.STATUS === 'SUCCESS'){
                        setRequests(oldRequests => {
                            return oldRequests.filter(req => req.id !== id)
                        })
                        setTotalPages(old => old-1)
                        setupAlert("Success","La solicitud fue aceptada éxitosamente",true)

                        console.log("Aqui AAA")
                    } else if(data.STATUS === 'ERROR'){
                        throw new Error('Something got wrong');
                    }
                    console.log(data)
                }).catch(()=>{
                    setupAlert("Failure","Hubo un problema al aceptar la solicitud",true)

                })
            break;

            case 'reject':

                DevicesServices.RejectDeviceRequest(id).then(res => {
                    const {data} = res
                    // TODO Manejar respuestas erroneas y animaciones
                    if(data.STATUS === 'SUCCESS'){
                        setRequests(oldRequests => {
                            return oldRequests.filter(req => req.id !== id)
                        })
                        setTotalPages(old => old-1)
                        setupAlert("Success","La solicitud fue rechazada éxitosamente",true)
                    } else if(data.STATUS === 'ERROR'){
                        throw new Error('Something got wrong')
                    }
                    console.log(data)
                }).catch(()=>{
                    setupAlert("Failure","Hubo un problema al rechazar la solicitud",true)
                })
                break;
        }

    }

    return (
        <>

        { !isViewportLarge &&
            <motion.div className="col-12 col-lg-10 container p-0 mt-lg-4"
            variants={variantsMobileSwipe}
            initial="swipeOutToRight"
            animate="swipeIn"
            exit="swipeOutToRight"
            >


            <AnimatePresence>
            {isAlertActive &&
            <AlertEngine setIsAlertActive={setIsAlertActive} type={alertType} message={alertMessage}/>
            }
            </AnimatePresence>

            {isLoadingRequest &&

                    <div className="d-flex flex-column justify-content-center align-items-center  vh-100">
                        <img src={bisontito} style={{height:"200px"}} />
                        <h1 >Estamos procesando tu solicitud</h1>
                        <div className="spinner-border" role="status">
                            <span className="sr-only">Loading...</span>
                        </div>
                    </div>
            }
            {!isLoadingRequest && <>
                    <MatriculaBusqueda view={view}></MatriculaBusqueda>
                    <ButtonTabs view={view} setView={setView}/>
                    <Tabla view={view} content={requests} onActions={actionsHandler}/>
                    <Paginacion view={view} totalPages={totalPages} currentPage={currentPage}
                    NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}/></>
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
            <AnimatePresence>
            {isAlertActive &&

                <AlertEngine setIsAlertActive={setIsAlertActive} type={alertType} message={alertMessage}/>
            }
            </AnimatePresence>

            { isLoadingRequest &&

                <div className="d-flex flex-column justify-content-center align-items-center  vh-100">
                    <img src={bisontito} style={{height:"200px"}} />
                    <h1 >Estamos procesando tu solicitud</h1>
                    <div className="spinner-border" role="status">
                        <span className="sr-only">Loading...</span>
                    </div>
                </div>
            }
            {!isLoadingRequest && <>
                    <MatriculaBusqueda view={view}></MatriculaBusqueda>
                    <ButtonTabs view={view} setView={setView}/>
                    <Tabla view={view} content={requests} onActions={actionsHandler}/>
                    <Paginacion view={view} totalPages={totalPages} currentPage={currentPage}
                    NextPageHandler={NextPageHandler} PrevPageHandler={PrevPageHandler}/></>
            }
            </motion.div>
        }

        { isModalActive &&  <ModalEngine  modalHandler={modalHandler} setIsModalActive={setIsModalActive}
        title="Error"
        message={modalMessage}
        /> }
        </>
    )
}
export default Solicitudes;

if(document.getElementById('Solicitudes')){
    ReactDOM.render(<Solicitudes/>, document.getElementById('Solicitudes'));
}

import React, { useEffect, useState, useRef} from "react"
import ReactDOM from 'react-dom';
import ModalEngine from "../../General/ModalEngine";
import Reloj from "./Reloj/Reloj";
import UltimaCargaHoras from "./UltimaCargaHoras/UltimaCargaHoras";
import { motion } from 'framer-motion';
import { useIsViewportLarge } from '../../../hooks/useMediaQuery';
import { variantsMobileSwipe, variantsMobileSwipeFromLeft, variantsMobileSwipeFromRight, variantsWebFade } from "../../../utils/AnimationVariants";
import { ObtenerEntradasDiarias, RegistarHoraEntrada, RegistarHoraSalida } from "../../../services/EntradaServices";
import { getDifDatesInMilisec, isCurrentDay  } from "../../../utils/TimeFunctions"
import StatusType from "../../../utils/StatusEnum"
import { Exception } from "sass";

const CargaHoras = (props)=>{
  // Esto es para ocultar el encabezado que dice Carga de Horas, se añadio para eliminarlo cuando se reutiliza en el reloj de admin
    let {hideH1} = props
    if(!hideH1)
      hideH1 = false 

  //  Revisar si la propiedad de tuition esta seteada, en caso contrario se le asignara como nula
    let {tuition} = props
    
    
    const [isActive, setIsActive] = useState(false);
    const [time, setTime] = useState(0);
    const [startTime, setStartTime] = useState(null);

    const [message, setMessage] = useState('');
    const [entradas, setEntrada] = useState(null);
    const [turnosDiarios, setTurnosDiarios] = useState(null);
    const [difSecondsTime, setDifSecondsTime] = useState(0);
    const [isModalActive,setIsModalActive] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const ultimaEntrada = useRef(null);
    const isViewportLarge = useIsViewportLarge();


    //Modal
    const [isErrorModalActive, setErrorIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");

    const errorModalHandler=()=>{
      setErrorIsModalActive(false);
    }

    const showUpExceptionModal = (exception) => {

        if(exception.response.data.MESSAGE !== "") setModalMessage(exception.response.data.MESSAGE);
        else setModalMessage("Hubo un error en el servidor");
        setErrorIsModalActive(true);
    }

    const modalHandler=()=>{
      setIsModalActive(false);
      setTime(0);
      setIsActive(false)

      // PETICION DE TERMINAR HORAS
      RegistarHoraSalida(tuition).then((result)=>{
        console.log(result)
        ActualizarHora();
      });
    }

    const ActualizarHora = () => {
        ObtenerEntradasDiarias(tuition)
          .then((result)=>{
            setIsLoading(false)
            setEntrada(result.data.entradas);
            setTurnosDiarios(result.data.turnosDiarios);

            if (result.data.entradas.length > 0){
                ultimaEntrada.current = result.data.entradas[result.data.entradas.length - 1];

                if( !isCurrentDay(ultimaEntrada.current.hora_entrada) && ultimaEntrada.current.id_status !== StatusType.TRABAJANDO){
                  setTime(0);
                  setMessage('Presiona el boton para comenzar el turno de hoy.');
                  return;
                }
                else if (ultimaEntrada.current.id_status === StatusType.TRABAJANDO && ultimaEntrada.current.hora_salida === null){
                    setIsActive(true);
                    setMessage('Actualmente estas en horario laboral.')
                    setTime(getDifDatesInMilisec(ultimaEntrada.current.hora_entrada));
                }
                else{
                    setTime(ultimaEntrada.current.horas_realizadas * 60 * 60 * 1000);
                    setMessage('Haz finalizado tu turno del dia.');
                }
            }
          })
          .catch((exception) => {

            showUpExceptionModal(exception)
          });
    }

    const onTabChanged=()=>{

      if(document.visibilityState == 'visible'){
        ActualizarHora();
      }

    }
    useEffect(() => {
        let interval = null;

        if (isActive === true) {



          interval = setInterval(() => {
            setTime((time) => time + 1000);
          }, 1000);

        } else {
          clearInterval(interval);
        }

        return () => {
          clearInterval(interval);
        };
      }, [isActive]);

      useEffect(() => {
        document.addEventListener('visibilitychange', onTabChanged);

        ActualizarHora();
        return ()=> {
          document.removeEventListener('visibilitychange', onTabChanged);
        }
      }, []);

      const handleStart = () => {

        // PETICION DE EMPEZAR HORAS
        if (!isCurrentDay(ultimaEntrada.current?.hora_entrada) || turnosDiarios.length > entradas.length ){
            RegistarHoraEntrada(tuition).then((result)=>{
                console.log(result)
                ActualizarHora();
            });
        }
        else{
            alert("ya hiciste el turno de hoy :(")
        }

      };

    return (
        <>
        { !isViewportLarge &&
              <motion.div className="row justify-content-center pt-lg-5"
              variants={variantsMobileSwipe}
              initial="swipeOutToRight"
              animate="swipeIn"
              exit="swipeOutToRight">
                <div className="col col-md-6 col-lg-6 col-xl-4 mt-lg-4 mb-4">
                  <h1 className="text-center my-5 display-4">Carga de horas</h1>
                  <Reloj time={time} isActive={isActive}
                    handleStart={handleStart}
                    message={message}
                    isLoading={isLoading}
                    setIsModalActive={setIsModalActive}/>
                  <UltimaCargaHoras entradas={entradas} turnosDiarios={turnosDiarios}/>
                </div>
              </motion.div>
        }
         { isViewportLarge &&
              <motion.div className="row justify-content-center pt-lg-2"
              variants={variantsWebFade}
                initial="fadeOut"
                animate="fadeIn"
                exit="fadeOut">
                <div className="col col-md-6 col-lg-6 col-xl-4 mt-lg-4 mb-4">
                  {!hideH1 && <h1 className="text-center my-5 display-4">Carga de horas</h1>}
                  <Reloj time={time} isActive={isActive}
                    handleStart={handleStart}
                    message={message}
                    isLoading={isLoading}
                    setIsModalActive={setIsModalActive}/>
                  <UltimaCargaHoras entradas={entradas} turnosDiarios={turnosDiarios}/>
                </div>
              </motion.div>
        }
        {isModalActive &&  <ModalEngine  modalHandler={modalHandler} setIsModalActive={setIsModalActive}
        title="Advertencia"
        message="¿Estás seguro de que quieres terminar tus horas? No podrás cargar más horas el dia de hoy"
        /> }

        {isErrorModalActive &&  <ModalEngine  modalHandler={errorModalHandler} setIsModalActive={setErrorIsModalActive}
        title="Error"
        message={modalMessage}
        /> }

        </>

    )
}
export default CargaHoras;

if(document.getElementById("CargaHoras")){
    ReactDOM.render(<CargaHoras/>,document.getElementById("CargaHoras"));
}

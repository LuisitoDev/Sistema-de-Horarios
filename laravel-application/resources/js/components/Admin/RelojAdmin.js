import {useState} from 'react'
import MatriculaBusqueda from './MatriculaBusqueda/MatriculaBusqueda';
import DatoPerfil from '../Asesor/Perfil/DatoPerfil';
import CargaHoras from '../Asesor/CargaHoras/CargaHoras';
import * as AlumnosServices from '../../services/AlumnosServices'
import View from '../../utils/ViewEnum'
import { useIsViewportLarge } from "../../hooks/useMediaQuery";
import { variantsMobileSwipe, variantsWebFade } from "../../utils/AnimationVariants"
import { motion, AnimatePresence } from 'framer-motion'


const RelojAdmin = () => {

    const isViewportLarge = useIsViewportLarge()
    const [tuition, setTuition] = useState(null);
    const [lastTuition, setLastTuition] = useState(null)
    
    const [view, setView] = useState(View.RelojAdmin);
    const [isLoadingRequest, setIsLoadingRequest] = useState(false)
    const [userNotFound, setUserNotFound] = useState(false)

    const [profile, setProfile] = useState({});


    const searchHandler = (e) => {
        e.preventDefault()

        setLastTuition(tuition)

        AlumnosServices.GetStudentProfileInfo(tuition).then(res => {
            setProfile(res.data.usuario);
            setUserNotFound(false)
            // setIsLoadingRequest(false)
        }).catch((exception)=>{
            console.log(exception); 
            setUserNotFound(true)
            // showUpExceptionModal(exception)
        });
    }

    return (
        <>
        {
            !isViewportLarge &&
            <motion.div className="col-12 col-lg-10 container p-0 mt-lg-4"
            variants={variantsWebFade}
            initial="fadeOut"
            animate="fadeIn"
            exit="fadeOut"
            >
            {!isLoadingRequest && 
            <>
                <MatriculaBusqueda view={view} setTuition={setTuition} searchHandler={searchHandler}/>
                {/* TODO: Agregar la informacion del usuario */}
                <div className='text-center'>
                    <h3 className='text-center fw-light'>Alumno seleccionado para fichar: </h3>
                    {
                        userNotFound && <h1 className='text-center fw-bold'>No se ha encontrado un estudiante con esa matricula</h1>
                    }
                    {
                        !userNotFound &&
                        <>
                            <h1 className='text-center fw-bold'> {lastTuition == null ? 'No hay alumno seleccionado' : '#'+lastTuition}</h1>
                            {lastTuition != null &&
                            <div className='d-flex justify-content-around m-auto w-50 rounded align-items-center'>
                                <DatoPerfil icon="fa-user" info={profile.nombre + ' ' + profile.apellido_pat + ' '+ profile.apellido_mat}></DatoPerfil>
                                <DatoPerfil icon="fa-landmark" info={profile.abreviacion}></DatoPerfil>
                                <DatoPerfil icon="fa-at" info={profile.correo}></DatoPerfil>
                            </div>
                            }
                            {lastTuition != null ? 
                                <CargaHoras key={lastTuition} hideH1={true} tuition={lastTuition}/> : <></>
                            }
                        </>
                    }
                </div>
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
            {!isLoadingRequest && 
            <>
                <MatriculaBusqueda view={view} setTuition={setTuition} searchHandler={searchHandler}/>
                {/* TODO: Agregar la informacion del usuario */}
                <div className='text-center'>
                    <h3 className='text-center fw-light'>Alumno seleccionado para fichar: </h3>
                    {
                        userNotFound && <h1 className='text-center fw-bold'>No se ha encontrado un estudiante con esa matricula</h1>
                    }
                    {
                        !userNotFound &&
                        <>
                            <h1 className='text-center fw-bold'> {lastTuition == null ? 'No hay alumno seleccionado' : '#'+lastTuition}</h1>
                            {lastTuition != null &&
                            <div className='d-flex justify-content-around m-auto w-50 rounded align-items-center'>
                                <DatoPerfil icon="fa-user" info={profile.nombre + ' ' + profile.apellido_pat + ' '+ profile.apellido_mat}></DatoPerfil>
                                <DatoPerfil icon="fa-landmark" info={profile.abreviacion}></DatoPerfil>
                                <DatoPerfil icon="fa-at" info={profile.correo}></DatoPerfil>
                            </div>
                            }
                            {lastTuition != null ? 
                                <CargaHoras key={lastTuition} hideH1={true} tuition={lastTuition}/> : <></>
                            }
                        </>
                    }
                </div>
            </>
            }
            </motion.div>
        }
        </>
    )
}



export default RelojAdmin